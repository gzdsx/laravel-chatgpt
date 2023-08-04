<?php

namespace App\Http\Controllers\Admin\OpenAi;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\OpenAiRequestLog;
use Illuminate\Http\Request;

class RequestLogController extends BaseController
{
    /**
     * @return OpenAiRequestLog|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return OpenAiRequestLog::query();
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
            'items' => $query->with(['user'])->offset($request->input('offset', 0))
                ->take($request->input('count', 15))->orderByDesc('id')->get()
        ]);
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
