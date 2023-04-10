<?php

namespace App\Http\Controllers\Api\OpenAi;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\CommonSetting;
use Illuminate\Http\Request;

class SettingsController extends BaseController
{
    public function getSettings()
    {
        $settings = CommonSetting::query()->where('skey', 'like', "ai_%")->get()->pluck('svalue', 'skey');

        return jsonSuccess($settings);
    }
}
