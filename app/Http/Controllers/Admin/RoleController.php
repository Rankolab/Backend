<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Add permission check
        // if (!auth()->user()->hasPermission("manage_roles")) {
        //     abort(403, "Unauthorized");
        // }
        $roles = Role::withCount("users", "permissions")->paginate(15);
        return view("admin.roles.index", compact("roles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Add permission check
        $permissions = Permission::orderBy("name")->get();
        return view("admin.roles.create", compact("permissions"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add permission check
        $request->validate([
            "name" => "required|string|max:255|unique:roles,name",
            "permissions" => "nullable|array",
            "permissions.*" => "exists:permissions,id", // Validate that permissions exist
        ]);

        try {
            $role = Role::create(["name" => $request->name]);
            if ($request->has("permissions")) {
                $role->permissions()->sync($request->permissions);
            }
            return redirect()->route("admin.roles.index")->with("success", "Role created successfully.");
        } catch (\Exception $e) {
            Log::error("Error creating role: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to create role. Please try again.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // Add permission check
        $role->load("permissions", "users"); // Eager load relationships
        return view("admin.roles.show", compact("role"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Add permission check
        $permissions = Permission::orderBy("name")->get();
        $role->load("permissions"); // Eager load current permissions
        return view("admin.roles.edit", compact("role", "permissions"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Add permission check
        $request->validate([
            "name" => "required|string|max:255|unique:roles,name," . $role->id,
            "permissions" => "nullable|array",
            "permissions.*" => "exists:permissions,id",
        ]);

        try {
            $role->update(["name" => $request->name]);
            $role->permissions()->sync($request->permissions ?? []); // Sync permissions, pass empty array if none selected
            return redirect()->route("admin.roles.index")->with("success", "Role updated successfully.");
        } catch (\Exception $e) {
            Log::error("Error updating role {$role->id}: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to update role. Please try again.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Add permission check
        // Prevent deleting core roles like 'admin' or 'user' if necessary
        if (in_array($role->name, ["admin", "superadmin", "user"])) {
             return redirect()->route("admin.roles.index")->with("error", "Cannot delete core roles.");
        }

        try {
            // Consider what happens to users with this role - maybe reassign to a default role?
            $role->permissions()->detach(); // Detach permissions first
            $role->users()->detach(); // Detach users
            $role->delete();
            return redirect()->route("admin.roles.index")->with("success", "Role deleted successfully.");
        } catch (\Exception $e) {
            Log::error("Error deleting role {$role->id}: " . $e->getMessage());
            return back()->with("error", "Failed to delete role. It might be in use.");
        }
    }

    /**
     * Assign permissions to a specific role.
     * Note: This functionality is integrated into store/update, but kept for potential direct use.
     */
    public function assignPermissions(Request $request, Role $role)
    {
        // Add permission check
        $request->validate([
            "permissions" => "required|array",
            "permissions.*" => "exists:permissions,id",
        ]);

        try {
            $role->permissions()->sync($request->permissions);
            return redirect()->route("admin.roles.edit", $role->id)->with("success", "Permissions updated successfully.");
        } catch (\Exception $e) {
            Log::error("Error assigning permissions to role {$role->id}: " . $e->getMessage());
            return back()->with("error", "Failed to update permissions.");
        }
    }
}

