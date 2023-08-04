<?php

namespace App\Http\Controllers\Api\OpenAi;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use OpenAi\OpenAiClient;
use OpenAi\OpenAiRequestConfig;

class ChatGptController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function completions(Request $request)
    {
        $prompt = $request->input('prompt', '写一篇日记');
        $config = new OpenAiRequestConfig();
        $config->prompt = $prompt;
        $config->temperature = 0;
        $config->top_p = 1;
        $config->n = 1;
        $config->max_tokens = 1024;
        $config->stream = false;
        //$config->stop = ["Human:", " AI:"];
        //$config->echo = true;

        $client = new OpenAiClient();
        $response = $client->completions($config);
        dump($response->toArray());
        if ($response->success()) {
            return jsonSuccess([
                '_id' => $response->id,
                'createdAt' => $response->created,
                'text' => $response->choices[0]['text']
            ]);
        } else {
            return jsonFail($response->error['message']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function edits(Request $request)
    {
        $config = new OpenAiRequestConfig();
        $config->input = $request->input('input');
        $config->instruction = $request->input('instruction');

        $client = new OpenAiClient();
        $response = $client->edits($config);
        if ($response->success()) {
            return jsonSuccess($response->choices[0]);
        } else {
            return jsonFail($response->error['message']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function chat(Request $request)
    {
        $config = new OpenAiRequestConfig();
        $config->messages = $request->input('messages', []);

        $client = new OpenAiClient();
        $response = $client->chatCompletions($config);
        if ($response->success()) {
            return jsonSuccess($response->choices[0]);
        } else {
            return jsonFail($response->error['message']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \OpenAi\OpenAiInvalidException
     */
    public function imageEdits(Request $request)
    {
        $file = $request->file('file');
        $filename = Str::random(40) . '.png';
        $image = Image::make($file->getRealPath());
        $image->fit(500);
        $image->save(material_path($filename), 0.9, 'png');

        $config = new OpenAiRequestConfig();
        $config->image = curl_file_create(material_path($filename));
        $config->size = '512x512';
        $config->n = 9;
        //$config->user = Auth::id().'';

        $client = new OpenAiClient();
        $response = $client->imageVariations($config);
        //删除本地图片
        Storage::delete($filename);

        if ($response->success()) {
            return jsonSuccess($response->data);
        }

        return jsonFail($response->error['message']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \OpenAi\OpenAiInvalidException
     */
    public function imageGenerations(Request $request)
    {
        $config = new OpenAiRequestConfig();
        $config->prompt = $request->input('prompt');
        $config->n = min($request->input('n', 1), 10);

        $client = new OpenAiClient();
        $response = $client->imageGenerations($config);
        if ($response->success()) {
            return jsonSuccess($response->data);
        }

        return jsonFail($response->error['message']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \OpenAi\OpenAiInvalidException
     */
    public function audioTranscription(Request $request)
    {
        $file = $request->file('file');
        $config = new OpenAiRequestConfig();
        $config->file = curl_file_create($file->getRealPath());
        $config->temperature = 1;
        $config->language = 'zh';

        $client = new OpenAiClient();
        $result = $client->audioTranscriptions($config);
        if ($result->success()) {
            return jsonSuccess($result->toArray());
        }

        return jsonFail($result->error['message']);
    }
}
