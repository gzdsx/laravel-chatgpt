<?php

namespace App\Http\Controllers\Admin\OpenAi;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AiQuickly;
use Illuminate\Http\Request;

class QuicklyController extends BaseController
{
    /**
     * @return AiQuickly|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return AiQuickly::query();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $query = $this->repository();
        if ($cate_id = $request->input('cate_id')) {
            $query->where('cate_id', $cate_id);
        }

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->with(['category'])->offset($request->input('offset', 0))
                ->take($request->input('count', 15))->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $quickly = $request->input('quickly', []);
        $model = $this->repository()->findOrNew($quickly['id'] ?? 0);
        $model->fill($quickly)->save();

        return jsonSuccess($model);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDelete(Request $request)
    {
        $this->repository()->whereKey($request->input('ids', []))->delete();

        return jsonSuccess();
    }
}
