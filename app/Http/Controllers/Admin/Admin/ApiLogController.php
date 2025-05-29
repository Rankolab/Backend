<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;

class ApiLogController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermission('manage_api')) {
            abort(403, 'Unauthorized');
        }
        
        $logs = ApiLog::latest()->paginate(30);
        return view('admin.apilogs.index', compact('logs'));
    }
}
