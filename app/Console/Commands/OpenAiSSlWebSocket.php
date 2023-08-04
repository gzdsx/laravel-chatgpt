<?php

namespace App\Console\Commands;


use App\Models\OpenAiDevice;
use App\Models\OpenAiPromptModel;
use App\Models\OpenAiRequestLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use OpenAi\OpenAiClient;
use OpenAi\OpenAiRequestConfig;
use OpenAi\OpenAiResponse;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class OpenAiSSlWebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole-ssl-websocket:openai {port}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'OpenAi SSL Webscket';

    protected $client;

    protected $buffer = '';
    protected $reply = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new OpenAiClient();
        $this->client->setProxy('127.0.0.1');
        $this->client->setProxyPort('1089');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server = new WebSocketServer("0.0.0.0", $this->argument('port'), SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_SSL);
        $server->set([
            'ssl_cert_file' => base_path('storage/ai.gzdsx.cn.pem'),
            'ssl_key_file' => base_path('storage/ai.gzdsx.cn.key'),
        ]);
        //连接成功回调
        $server->on('open', function (WebSocketServer $server, Request $request) {
            $this->info('客户端' . $request->fd . '链接成功');
        });

        //收到消息回调
        $server->on('message', function (WebSocketServer $server, Frame $frame) {
            //dump($frame->data);
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

            $words_filter = [
                '!', '@', '#', '$', '%', '&', '*', '(', ')', '-', '+', '_', '+',
                ',', '.', '?', '[', ']', '{', '}', '\\', '|', ';', '\'', ':', '"', '<', '>', '/', ' '
            ];

            $chechstr = str_replace($words_filter, '', $content->prompt);
            if (!$chechstr) {
                $message = new AiChoiceMessage();
                $message->text = '请输入有实际意义的提示词';
                $message->index = 0;

                $server->push($frame->fd, $message->toJson());
                $server->push($frame->fd, '[DONE]');
                return;
            }

            $words_filter = ['你好', '您好!', 'Hello', 'hello', '!'];
            $chechstr = str_replace($words_filter, '', $content->prompt);

            if (!$chechstr) {
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

            if (!$device = OpenAiDevice::where('device_id', $content->device_id)->first()) {
                $device = new OpenAiDevice();
                $device->device_id = $content->device_id;
            }

            $log = new OpenAiRequestLog();
            $log->prompt = $content->prompt;
            $isFree = $device->amount < env('AI_FREE_AMOUNT');
            $user = User::where('websocket_token', $content->access_token)->first();
            if ($user) {
                $device->uid = $user->uid;

                $log->user()->associate($user);
                //先看看每日免费次数是否用完
                if (!$isFree) {
                    //如果包月过期则看剩余点数
                    if ($user->member_expires_at == null || now()->isAfter($user->member_expires_at->format('Y-m-d 23:59:59'))) {
                        if ($user->free_plan_times < 1) {
                            $message = new AiChoiceMessage();
                            $message->text = '您的免费次数已用完，请购买付费计划';
                            $message->index = 0;

                            $server->push($frame->fd, $message->toJson());
                            $server->push($frame->fd, '[DONE]');
                            return;
                        } else {
                            $user->free_plan_times -= 1;
                            $user->save();
                        }
                    }
                }
            }
            $log->save();
            $device->save();

            $config = new OpenAiRequestConfig();
            if ($content->type == AiRequestContent::TYPE_COMPLETION || $content->type == 'compeletion') {
                $model = OpenAiPromptModel::find($content->model_id ?: $content->quickly_id);
                $options = parse_ini_string($model->options, false, INI_SCANNER_TYPED);
                if (is_array($options)) {
                    foreach ($options as $key => $val) {
                        if (!is_numeric($key) && !empty($val)) {
                            $config->__set($key, $val);
                        }
                    }
                }

                $config->prompt = str_replace('{prompt}', $content->prompt, $model->template);
                $max_tokens = $content->max_tokens ?: 0;
                if ($max_tokens > 0) {
                    $max_tokens = min($max_tokens, $model->max_tokens);
                } else {
                    $max_tokens = $model->max_tokens;
                }

                $config->max_tokens = $max_tokens;
            } else {
                //$config->prompt = "你是ChatGPT中文引擎，我用中文和你对话，我的问题是“" . $content->prompt . "”。";
                $config->prompt = $content->prompt;
                $config->max_tokens = 1000;
            }

            $config->temperature = 0;
            $config->stream = true;
            $config->n = 1;
            $config->model = 'text-davinci-003';
            //$config->stop = ["\n", "\r"," "];

            $reply = '';
            $imperfect = '';
            $this->client->completions($config, function ($ch, $vdata) use ($server, $frame, $log, &$reply, $isFree, $device, &$imperfect) {
                //$this->info($vdata);
                //$this->info('-----------------------------');
                $datalen = strlen($vdata);
                $vdata = str_replace('data: ', '', $vdata);
                foreach (explode("\n\n", $vdata) as $str) {
                    if ($str == '[DONE]') {
                        $server->push($frame->fd, $str);
                        $log->reply = $this->reply;
                        $log->save();

                        if ($isFree) {
                            $device->amount++;
                            $device->save();
                        }

                        return $datalen;
                    }

                    $this->buffer .= $str;
                    if (!is_null(json_decode($this->buffer))) {
                        $this->pushMessage($this->buffer, $server, $frame->fd);
                        $this->buffer = '';
                    }
                }

                return $datalen;
            });
        });

        //关闭链接回调
        $server->on('close', function (WebSocketServer $server, $fd) {
            $this->info('客户端' . $fd . '断开链接');
        });

        $server->start();
        return 0;
    }

    protected function pushMessage($json, $server, $fd)
    {
        if ($server->isEstablished($fd)) {
            $response = new OpenAiResponse(json_decode($json, true));
            if ($response->success()) {
                //dump($response->choices);
                if (is_array($response->choices)) {
                    $this->info(json_encode($response->choices[0]));
                    $this->reply .= $response->choices[0]['text'] ?? '';
                    $server->push($fd, json_encode($response->choices[0]));
                }
            } else {
                $message = new AiChoiceMessage();
                $message->text = $response->error['message'] ?? '';
                $message->index = 0;
                $server->push($fd, $message->toJson());
                $server->push($fd, '[DONE]');
            }
        }
    }
}
