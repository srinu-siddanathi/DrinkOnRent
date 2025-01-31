<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return response()->json(['plans' => $plans]);
    }

    public function show(Plan $plan)
    {
        return response()->json(['plan' => $plan]);
    }
} 