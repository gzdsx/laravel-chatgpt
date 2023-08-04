<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends BaseController
{
    public function getDetail()
    {
        $user = Auth::user();

        return jsonSuccess([
            'free_plan_times' => $user->free_plan_times,
            'member_expires_at' => $user->member_expires_at ? $user->member_expires_at->format('Y-m-d') : null
        ]);
    }
}
