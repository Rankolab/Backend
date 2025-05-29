<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting; // Import the Setting model
use Illuminate\Support\Facades\Cache; // For clearing settings cache if implemented
use Illuminate\Support\Facades\Artisan; // To potentially call config:clear or cache:clear

class SettingsController extends Controller
{
    /**
     * Display the settings page, grouped by category.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch all settings, grouped by the 'group' column
        $settings = Setting::all()->groupBy("group");

        // Pass the grouped settings to the view
        return view("admin.settings.index", compact("settings"));
    }

    /**
     * Update the specified settings in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        // This validation can be basic, as we loop through settings anyway
        // More specific validation could be added if needed (e.g., email format for admin_email)
        $validatedData = $request->validate([
            "settings" => "required|array",
            "settings.*" => "nullable|string", // Allow null/empty strings
        ]);

        foreach ($validatedData["settings"] as $key => $value) {
            $setting = Setting::where("key", $key)->first();

            if ($setting) {
                // Handle boolean settings specifically (checkboxes might not send value if unchecked)
                if ($setting->type === "boolean") {
                    $setting->value = $request->has("settings." . $key) ? "true" : "false";
                } else {
                    $setting->value = $value;
                }
                $setting->save();
            }
        }

        // Clear relevant caches if settings affect configuration
        // Cache::forget("app_settings"); // Example if using a specific cache key
        // Artisan::call("config:clear"); // If settings directly influence config files
        // Artisan::call("cache:clear"); // More general cache clear

        return redirect()->route("admin.settings.index")
                         ->with("success", "Settings updated successfully.");
    }
}

