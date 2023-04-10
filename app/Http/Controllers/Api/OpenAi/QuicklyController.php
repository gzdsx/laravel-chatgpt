<?php

namespace App\Http\Controllers\Api\OpenAi;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AiQuickly;
use App\Models\AiQuicklyCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class QuicklyController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $query = AiQuicklyCategory::query()->where('enable', 1);

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->with(['items' => function (HasMany $builder) {
                return $builder->where('enable', 1);
            }])->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo(Request $request)
    {
        $model = AiQuickly::with('category')->whereKey($request->input('id'))->get();

        return jsonSuccess($model);
    }
}
