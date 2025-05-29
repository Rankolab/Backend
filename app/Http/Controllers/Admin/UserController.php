<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log; // Added for logging errors

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with("roles");

        // Search functionality
        if ($request->filled("search")) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where("name", "like", "%{$searchTerm}%")
                  ->orWhere("email", "like", "%{$searchTerm}%");
            });
        }

        // Role filter
        if ($request->filled("role")) {
            $roleId = $request->role;
            $query->whereHas("roles", function ($q) use ($roleId) {
                $q->where("roles.id", $roleId);
            });
        }

        // Status filter (if status column exists)
        // if ($request->filled("status")) {
        //     $query->where("status", $request->status);
        // }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view("admin.users.index", compact("users"));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::orderBy("name")->get();
        return view("admin.users.create", compact("roles"));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", "confirmed", Password::defaults()],
            "roles" => ["nullable", "array"],
            "roles.*" => ["integer", "exists:roles,id"],
        ]);

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"]),
        ]);

        if (!empty($validated["roles"])) {
            $user->roles()->sync($validated["roles"]);
        }

        return redirect()->route("admin.users.index")->with("success", "User created successfully.");
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(["roles", "websites", "licenses", "purchases"]); // Removed undefined 'activity' relationship
        return view("admin.users.show", compact("user"));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load(["roles", "websites", "licenses", "purchases"]);
        $roles = Role::orderBy("name")->get();
        return view("admin.users.edit", compact("user", "roles"));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", Rule::unique("users")->ignore($user->id)],
            "password" => ["nullable", "confirmed", Password::defaults()],
            "roles" => ["nullable", "array"],
            "roles.*" => ["integer", "exists:roles,id"],
        ]);

        $updateData = [
            "name" => $validated["name"],
            "email" => $validated["email"],
        ];

        if (!empty($validated["password"])) {
            $updateData["password"] = Hash::make($validated["password"]);
        }

        $user->update($updateData);
        $user->roles()->sync($request->input("roles", []));

        return redirect()->route("admin.users.index")->with("success", "User updated successfully.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route("admin.users.index")->with("error", "You cannot delete yourself.");
        }
        if ($user->hasRole("superadmin")) {
             return redirect()->route("admin.users.index")->with("error", "Cannot delete Super Admin.");
        }

        // Activity is logged automatically by the trait
        $userName = $user->name; // Store name before deleting
        $user->roles()->detach();
        $user->delete();

        // Manually log deletion if needed for more context, though trait handles basic deletion log
        // activity()->causedBy(auth()->user())->log("Deleted user: {$userName}");

        return redirect()->route("admin.users.index")->with("success", "User deleted successfully.");
    }

    /**
     * Handle bulk actions on users.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            "action" => ["required", Rule::in(["delete"])], // Add other actions like "activate", "deactivate" here
            "selected_users" => ["required", "json"],
        ]);

        $selectedUserIds = json_decode($validated["selected_users"], true);

        if (empty($selectedUserIds)) {
            return redirect()->route("admin.users.index")->with("error", "No users selected.");
        }

        $action = $validated["action"];
        $users = User::whereIn("id", $selectedUserIds)->get();
        $count = 0;
        $skippedSelf = false;
        $skippedSuperAdmin = false;

        foreach ($users as $user) {
            // Safety checks
            if ($user->id === auth()->id()) {
                $skippedSelf = true;
                continue;
            }
            if ($user->hasRole("superadmin")) {
                $skippedSuperAdmin = true;
                continue;
            }

            try {
                if ($action === "delete") {
                    $user->roles()->detach();
                    $user->delete(); // Activity log trait should handle this
                    $count++;
                }
                // Add other actions here (e.g., activate, deactivate)
                // elseif ($action === "activate") { ... }

            } catch (\Exception $e) {
                Log::error("Bulk action error for user ID {$user->id}: " . $e->getMessage());
                // Optionally collect errors to display to the user
            }
        }

        $message = "Bulk action \"{$action}\" completed. {$count} users affected.";
        if ($skippedSelf) {
            $message .= " You cannot perform actions on yourself.";
        }
        if ($skippedSuperAdmin) {
            $message .= " Super Admins cannot be affected by bulk actions.";
        }

        return redirect()->route("admin.users.index")->with("success", $message);
    }
}

