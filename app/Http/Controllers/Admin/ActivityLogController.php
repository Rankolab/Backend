<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Add permission check if needed
        // if (!auth()->user()->hasPermission("view_activity_logs")) {
        //     abort(403, "Unauthorized");
        // }

        $query = Activity::latest(); // Start with latest logs

        // Filtering (Example: by user)
        if ($request->filled("user_id")) {
            $query->where("causer_type", \App\Models\User::class)
                  ->where("causer_id", $request->user_id);
        }

        // Filtering (Example: by log name)
        if ($request->filled("log_name")) {
            $query->where("log_name", $request->log_name);
        }

        // Filtering (Example: by subject type)
        if ($request->filled("subject_type")) {
            // You might need to map a simple string to the full class name
            // e.g., "User" => \App\Models\User::class
            $query->where("subject_type", $request->subject_type);
        }

        $activities = $query->with(["causer", "subject"]) // Eager load relationships
                           ->paginate(25) // Paginate results
                           ->withQueryString(); // Append filter parameters to pagination links

        // Optional: Get distinct log names or user list for filter dropdowns
        $logNames = Activity::select("log_name")->distinct()->pluck("log_name");
        $users = \App\Models\User::orderBy("name")->get(["id", "name"]);

        return view("admin.activity_logs.index", compact("activities", "logNames", "users"));
    }

    /**
     * Display the specified activity log details.
     *
     * @param Activity $activity
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Activity $activity)
    {
        // Add permission check if needed
        $activity->load(["causer", "subject"]); // Ensure relationships are loaded
        return view("admin.activity_logs.show", compact("activity"));
    }
}

