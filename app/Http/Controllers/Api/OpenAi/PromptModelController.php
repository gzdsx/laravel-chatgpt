<?php

namespace App\Http\Controllers\Api\OpenAi;

use App\Http\Controllers\Api\BaseController;
use App\Models\OpenAiPromptCategory;
use App\Models\OpenAiPromptModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class PromptModelController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $query = OpenAiPromptCategory::query()->where('enable', 1);

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->with(['items', 'models'])->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(Request $request)
    {
        $model = OpenAiPromptModel::with('category')->whereKey($request->input('id'))->get();

        return jsonSuccess($model);
    }
}
