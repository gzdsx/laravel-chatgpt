<?php

namespace App\Console\Commands;

use App\Models\AiDevice;
use App\Models\AiPromptLog;
use App\Models\AiQuickly;
use App\Models\User;
use Illuminate\Console\Command;
use OpenAi\OpenAiClient;
use OpenAi\OpenAiRequestConfig;
use OpenAi\OpenAiResponse;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class AiWebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole-websocket:ai {port}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ChatGPT webSocket';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new OpenAiClient();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server = new WebSocketServer("0.0.0.0", $this->argument('port'));
        //连接成功回调
        $server->on('open', function (WebSocketServer $server, Request $request) {
            $this->info('客户端' . $request->fd . '链接成功');
        });

        //收到消息回调
        $server->on('message', function (WebSocketServer $server, Frame $frame) {
            dump($frame->data);
            if ($frame->data == 'ping') {
                return;
            }

            $content = new AiRequestContent(json_decode($frame->data, true));
            if ($content->token != env('AI_WEBSOCKET_TOKEN')) {
                $message = new AiChoiceMessage();
                $message->text = '当前连接已失效，请升级APP版本';
                $message->index = 0;

                $server->push($frame->fd, $message->toJson());
                $server->push($frame->fd, '[DONE]');
                return;
            }

            if (!$content->prompt) {
                $message = new AiChoiceMessage();
                $message->text = '请输入提示内容再提交';
                $message->index = 0;

                $server->push($frame->fd, $message->toJson());
                $server->push($frame->fd, '[DONE]');
                return;
            }

            $words = ['你好', '您好!', 'Hello', 'hello'];
            if (in_array($content->prompt, $words)) {
                $message = new AiChoiceMessage();
                $message->text = '您好，请问有什么可以帮您?';
                $message->index = 0;

                $server->push($frame->fd, $message->toJson());
                $server->push($frame->fd, '[DONE]');
                return;
            }

            if (!$content->device_id) {
                $message = new AiChoiceMessage();
                $message->text = '参数传入错误，请重试';
                $message->index = 0;

                $server->push($frame->fd, $message->toJson());
                $server->push($frame->fd, '[DONE]');
                return;
            }

            if (!$device = AiDevice::where('device_id', $content->device_id)->first()) {
                $device = new AiDevice();
                $device->device_id = $content->device_id;
                $device->amount = 5;
                $device->save();
            }


            $isFree = $device->amount < env('AI_FREE_AMOUNT');
            $needPoints = $content->type === AiRequestContent::TYPE_CHAT ? 1 : 2;
            $user = User::where('websocket_token', $content->access_token)->first();
            if ($user) {
                $log = new AiPromptLog();
                $log->prompt = $content->prompt;
                $log->user()->associate($user);
                $log->save();
                //先看看每日免费次数是否用完
                if (!$isFree) {
                    //如果包月过期则看剩余点数
                    if ($user->payment_plan_expires_at == null || now()->isAfter($user->payment_plan_expires_at->format('Y-m-d 23:59:59'))) {
                        if ($user->payment_plan_points < $needPoints) {
                            $message = new AiChoiceMessage();
                            $message->text = '您的每日免费次数已用完，请购买付费计划';
                            $message->index = 0;

                            $server->push($frame->fd, $message->toJson());
                            $server->push($frame->fd, '[DONE]');
                            return;
                        }
                    }
                }
            }

            $config = new OpenAiRequestConfig();
            if ($content->type == AiRequestContent::TYPE_COMPLETION && $content->quickly_id) {
                $quickly = AiQuickly::find($content->quickly_id);
                $options = parse_ini_string($quickly->options, false, INI_SCANNER_TYPED);
                if (is_array($options)) {
                    foreach ($options as $key => $val) {
                        if (!is_numeric($key) && !empty($val)) {
                            $config->__set($key, $val);
                        }
                    }
                }

                $config->prompt = str_replace('{prompt}', $content->prompt, $quickly->template);
            } else {
                $config->prompt = "我用中文和你对话，回复请不要超过300字，我的问题是“" . $content->prompt . "”";
            }

            if (!$config->model) {
                $config->model = 'text-davinci-003';
            }

            if (!$config->max_tokens) {
                $config->max_tokens = 2048;
            }

            $config->stream = true;
            $config->top_p = 1;

            $hasDeducted = false;
            $this->client->completions($config, function ($ch, $vdata) use ($server, $frame, $user, $isFree, $needPoints, $device, &$hasDeducted) {
                $this->info($vdata);
                if (!$hasDeducted) {
                    $hasDeducted = true;
                    if ($isFree) {
                        $device->amount += 1;
                        $device->save();
                    } else {
                        if ($needPoints > 0) {
                            if ($user) {
                                $user->payment_plan_points -= $needPoints;
                                $user->save();
                            }
                        }
                    }
                }
                $json = trim(str_replace('data: ', '', $vdata));
                //$this->info($json);
                if ($server->isEstablished($frame->fd)) {
                    if ($json == '[DONE]') {
                        $server->push($frame->fd, $json);
                    } else {
                        $response = new OpenAiResponse(json_decode($json, true));
                        if ($response->success()) {
                            if (is_array($response->choices)) {
                                $server->push($frame->fd, json_encode($response->choices[0]));
                            }
                        } else {
                            $message = new AiChoiceMessage();
                            $message->text = $response->error['message'] ?? '';
                            $message->index = 0;
                            $server->push($frame->fd, $message->toJson());
                            $server->push($frame->fd, '[DONE]');
                        }
                    }
                }
                return strlen($vdata);
            });
        });

        //关闭链接回调
        $server->on('close', function (WebSocketServer $server, $fd) {
            $this->info('客户端' . $fd . '断开链接');
        });

        $server->start();
        return 0;
    }
}
