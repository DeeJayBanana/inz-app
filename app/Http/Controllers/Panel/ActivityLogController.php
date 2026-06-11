<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request) {
        $q = Activity::with(['causer', 'subject'])->latest();

        if($request->filled('log_name')) {
            $q->where('log_name', $request->log_name);
        }

        $perPage = $request->get('per_page', 25);

        $categories = Activity::distinct()->pluck('log_name');

        $logs = $q->paginate($perPage)->withQueryString();

        return view('panel.logs', compact('logs', 'categories'));
    }
}
