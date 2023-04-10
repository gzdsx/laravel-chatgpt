<?php

namespace App\Http\Controllers\Admin\OpenAi;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AiQuicklyCategory;
use Illuminate\Http\Request;

class QuicklyCategoryController extends BaseController
{
    /**
     * @return AiQuicklyCategory|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return AiQuicklyCategory::query();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        return jsonSuccess(['items' => $this->repository()->get()]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $category = $request->input('category', []);
        $model = $this->repository()->findOrNew($category['cate_id'] ?? 0);
        $model->fill($category)->save();

        return jsonSuccess($model);
    }

    /**
     * @param Request $request
     */
    public function delete(Request $request)
    {
        if ($model = $this->repository()->find($request->input('cate_id'))) {
            $model->delete();
        }
    }
}
