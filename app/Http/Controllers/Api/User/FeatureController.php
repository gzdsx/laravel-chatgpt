<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FeatureController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNickname(Request $request)
    {
        $nickname = $request->input('nickname');
        $user = Auth::user();
        $user->nickname = $nickname;
        $user->save();

        return jsonSuccess();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        if ($file = $request->file('file')) {
            Storage::makeDirectory('avatar');
            $filename = $file->hashName('avatar');
            $image = Image::make($file->getRealPath());
            if ($image->exif('Orientation')) {
                switch ($image->exif('Orientation')) {
                    case 8:
                        $image->rotate(90);
                        break;
                    case 3:
                        $image->rotate(180);
                        break;
                    case 6:
                        $image->rotate(-90);
                        break;
                }
            }
            $image->fit(500);
            $image->save(material_path($filename));

            Auth::user()->update(['avatar' => material_url($filename)]);
            return jsonSuccess();
        }

        return jsonFail('image upload failed');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        $profile = Auth::user()->profile()->firstOrCreate();
        return jsonSuccess($profile);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $profile = Auth::user()->profile()->firstOrNew();
        $profile->fill($request->input('profile', []));
        $profile->save();

        return jsonSuccess();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCodeImage(Request $request)
    {
        $user = Auth::user();
        if ($user->code_image) {
            return jsonSuccess($user->code_image);
        } else {
            if ($model = Auth::user()->code) {
                $code = $model->code;
            } else {
                $code = UserCode::generateCode();
                Auth::user()->code()->create(['code' => $code]);
            }

            Storage::makeDirectory('qrcode');
            $filename = 'qrcode/' . Str::random(40) . '.png';
            QrCode::format('png')
                ->size(1000)
                ->margin(2)
                ->generate('U_' . $code, material_path($filename));
            $user->code_image = material_url($filename);
            $user->save();
            return jsonSuccess($user->code_image);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAccount(Request $request)
    {
        Auth::user()->delete();

        if (Auth::user()->commissions()->where('platform', 'apple')->exists()) {
            Auth::user()->delete();
            return jsonSuccess();
        }

        return jsonFail('只有使用苹果ID创建的账户才能删除');

    }
}
