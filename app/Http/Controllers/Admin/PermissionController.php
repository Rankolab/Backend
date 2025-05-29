<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Add permission check
        // if (!auth()->user()->hasPermission("manage_permissions")) {
        //     abort(403, "Unauthorized");
        // }
        $permissions = Permission::orderBy("name")->paginate(20);
        return view("admin.permissions.index", compact("permissions"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Add permission check
        return view("admin.permissions.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add permission check
        $request->validate([
            "name" => "required|string|max:255|unique:permissions,name",
            // Add description or group if needed
        ]);

        try {
            Permission::create(["name" => $request->name]);
            return redirect()->route("admin.permissions.index")->with("success", "Permission created successfully.");
        } catch (\Exception $e) {
            Log::error("Error creating permission: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to create permission. Please try again.");
        }
    }

    /**
     * Display the specified resource.
     * (Not typically needed for permissions, often managed via roles)
     */
    public function show(Permission $permission)
    {
        // Add permission check
        // return view("admin.permissions.show", compact("permission"));
        return redirect()->route("admin.permissions.index"); // Redirect as show view is often not needed
    }

    /**
     * Show the form for editing the specified resource.
     * (Not typically needed for permissions, name change might break logic)
     */
    public function edit(Permission $permission)
    {
        // Add permission check
        // return view("admin.permissions.edit", compact("permission"));
         return redirect()->route("admin.permissions.index")->with("info", "Editing permissions is generally discouraged. Delete and recreate if necessary.");
    }

    /**
     * Update the specified resource in storage.
     * (Not typically needed for permissions)
     */
    public function update(Request $request, Permission $permission)
    {
        // Add permission check
        // $request->validate([...
        // $permission->update(...);
        return redirect()->route("admin.permissions.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        // Add permission check
        try {
            // Detach from all roles before deleting
            $permission->roles()->detach();
            $permission->delete();
            return redirect()->route("admin.permissions.index")->with("success", "Permission deleted successfully.");
        } catch (\Exception $e) {
            Log::error("Error deleting permission {$permission->id}: " . $e->getMessage());
            return back()->with("error", "Failed to delete permission. It might be linked elsewhere.");
        }
    }
}

