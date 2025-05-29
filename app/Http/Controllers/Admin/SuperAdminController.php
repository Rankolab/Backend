<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class SuperAdminController extends Controller
{
    public function superAgentView()
    {
        // Get system stats for super agent view
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $superAdmins = User::where('role', 'super_admin')->count();
        
        return view('admin.super.agent', compact('totalUsers', 'adminUsers', 'superAdmins'));
    }
    
    public function handleSuperAgentRequest(Request $request)
    {
        $request->validate([
            'command' => 'required|string',
        ]);
        
        // Process the super agent command
        $command = $request->command;
        $response = $this->processCommand($command);
        
        return response()->json(['response' => $response]);
    }
    
    private function processCommand($command)
    {
        // Simple command processor for demonstration
        if (stripos($command, 'user') !== false && stripos($command, 'count') !== false) {
            return 'There are ' . User::count() . ' users in the system.';
        }
        
        if (stripos($command, 'admin') !== false && stripos($command, 'list') !== false) {
            $admins = User::where('role', 'admin')->get()->pluck('name')->toArray();
            return 'Administrators: ' . implode(', ', $admins);
        }
        
        return 'I\'m sorry, I don\'t understand that command.';
    }
    
    public function delegationView()
    {
        $admins = User::where('role', 'admin')->get();
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.super.delegation', compact('admins', 'roles', 'permissions'));
    }
    
    public function updateAdminRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
        ]);
        
        $user = User::findOrFail($id);
        
        // Update user's role
        $user->roles()->sync([$request->role_id]);
        
        // Update user's permissions
        $user->permissions()->sync($request->permissions ?? []);
        
        return redirect()->route('admin.delegation')->with('success', 'Admin role and permissions updated successfully.');
    }
}
