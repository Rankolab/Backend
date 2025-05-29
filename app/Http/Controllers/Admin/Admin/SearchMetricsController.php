<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchMetric;

class SearchMetricsController extends Controller
{
    public function index()
    {
        $metrics = SearchMetric::latest()->take(50)->get();
        return view('admin.searchmetrics.index', compact('metrics'));
    }
}
