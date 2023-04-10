<?php

namespace App\Http\Controllers\Admin\OpenAi;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AiPaymentPlan;
use Illuminate\Http\Request;

class PaymentPlanController extends BaseController
{
    /**
     * @return AiPaymentPlan|\Illuminate\Database\Eloquent\Builder
     */
    protected function repository()
    {
        return AiPaymentPlan::query();
    }

    public function getList(Request $request)
    {
        $query = $this->repository();
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        return jsonSuccess([
            'total' => $query->count(),
            'items' => $query->offset($request->input('offset', 0))
                ->limit($request->input('count', 15))->orderBy('value')->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $plan = $request->input('plan', []);
        $model = $this->repository()->findOrNew($plan['id'] ?? 0);
        $model->fill($plan)->save();

        return jsonSuccess($plan);
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

    public function batchUpdate(Request $request)
    {
        $this->repository()->whereKey($request->input('ids', []))->update($request->input('data', []));
        return jsonSuccess();
    }
}
