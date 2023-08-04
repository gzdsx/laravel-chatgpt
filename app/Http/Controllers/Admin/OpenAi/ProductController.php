<?php

namespace App\Http\Controllers\Admin\OpenAi;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\OpenAiProduct;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * @return OpenAiProduct|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return OpenAiProduct::query();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $query = $this->repository();
        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->offset($request->input('offset', 0))
                ->limit($request->input('count', 15))->orderBy('id')->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $product = $request->input('product', []);
        $model = $this->repository()->findOrNew($product['id'] ?? 0);
        $model->fill($product)->save();

        return jsonSuccess($product);
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchUpdate(Request $request)
    {
        $this->repository()->whereKey($request->input('ids', []))->update($request->input('data', []));
        return jsonSuccess();
    }
}
