<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Models\Setting; // Assuming a Setting model or similar to track completion

class SetupWizardController extends Controller
{
    /**
     * Display the first step of the setup wizard.
     */
    public function index()
    {
        // Check if the setup has already been completed
        // This could be a flag in the database (e.g., settings table) or a file marker
        $is_configured = Setting::where("key", "setup_wizard_completed")->exists();

        if ($is_configured) {
            // Optionally redirect to dashboard if already configured
            // return redirect()->route("admin.dashboard")->with("info", "Setup already completed.");
        }

        // Pass configuration status to the view (though the check above might redirect)
        return view("admin.setup_wizard.index", compact("is_configured"));
    }

    /**
     * Process the current step and display the next step or complete.
     */
    public function processStep(Request $request)
    {
        $currentStep = $request->input("current_step", 1);

        try {
            switch ($currentStep) {
                case 1:
                    // Process Step 1 data (if any)
                    // No data needed for the welcome step, just proceed
                    return $this->showStep(2);
                    break;

                case 2:
                    // Process Step 2 data (e.g., Site Info, Admin Account)
                    $request->validate([
                        "site_name" => "required|string|max:255",
                        "admin_email" => "required|email",
                        // Add other validations for step 2
                    ]);
                    // Save settings (e.g., update .env, save to DB)
                    // Example: Setting::updateOrCreate(["key" => "site_name"], ["value" => $request->site_name]);
                    Log::info("Setup Wizard Step 2 processed.");
                    return $this->showStep(3);
                    break;

                case 3:
                    // Process Step 3 data (e.g., Email Config)
                    $request->validate([
                        "mail_mailer" => "required|string",
                        // Add other mail validations
                    ]);
                    // Save mail settings (e.g., update .env)
                    Log::info("Setup Wizard Step 3 processed.");
                    return $this->showStep(4); // Or redirect to complete if last step
                    break;
                
                // Add more cases for subsequent steps

                default:
                    // Invalid step, redirect back to start
                    return redirect()->route("admin.setup.wizard.index")->with("error", "Invalid setup step.");
            }
        } catch (\Exception $e) {
            Log::error("Setup Wizard Error (Step {$currentStep}): " . $e->getMessage());
            return redirect()->back()->withInput()->with("error", "An error occurred during setup: " . $e->getMessage());
        }
    }

    /**
     * Show a specific step view.
     */
    private function showStep(int $stepNumber)
    {
        $viewName = "admin.setup_wizard.step" . $stepNumber;
        if (!view()->exists($viewName)) {
            // If view doesn\"t exist, maybe it\"s the completion step
            if ($stepNumber > 3) { // Assuming 3 steps for now
                 return redirect()->route("admin.setup.wizard.complete");
            }
            Log::error("Setup wizard view not found: {$viewName}");
            return redirect()->route("admin.setup.wizard.index")->with("error", "Setup step view not found.");
        }
        
        // Pass any necessary data to the step view
        $data = [];
        if ($stepNumber == 2) {
            // Example: Load current site name if exists
            // $data["current_site_name"] = Setting::where("key", "site_name")->value("value");
        }
        
        return view($viewName, $data);
    }


    /**
     * Display the completion page.
     */
    public function complete()
    {
        // Mark setup as completed
        Setting::updateOrCreate(["key" => "setup_wizard_completed"], ["value" => "true"]);

        // Clear any specific setup cache/config if needed
        Artisan::call("config:clear");
        Artisan::call("cache:clear");

        Log::info("Setup Wizard Completed.");
        // Create the completion view
        return view("admin.setup_wizard.complete");
    }
}

