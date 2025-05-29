<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Apply throttling to prevent abuse
        $this->middleware('throttle:60,1');
        
        // Cache the results for 10 minutes to improve performance
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        
        $cacheKey = 'users_page_' . $page . '_limit_' . $limit;
        
        $users = Cache::remember($cacheKey, 600, function () use ($page, $limit) {
            return User::paginate($limit);
        });
        
        return ApiResponse::success($users, 'Users retrieved successfully');
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $cacheKey = 'user_' . $id;
        
        $user = Cache::remember($cacheKey, 600, function () use ($id) {
            return User::find($id);
        });
        
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }
        
        return ApiResponse::success($user, 'User retrieved successfully');
    }

    /**
     * Update the specified user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return ApiResponse::notFound('User not found');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|string|in:user,admin,super_admin',
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }
        
        $user->update($request->only(['name', 'email', 'role']));
        
        // Clear the cache for this user
        Cache::forget('user_' . $id);
        
        return ApiResponse::success($user, 'User updated successfully');
    }

    /**
     * Get user roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles()
    {
        $roles = ['user', 'admin', 'super_admin'];
        
        return ApiResponse::success($roles, 'Roles retrieved successfully');
    }

    /**
     * Get user permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissions()
    {
        // In a real application, this would fetch from a permissions table
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_content',
            'manage_settings',
            'view_reports',
            'manage_api',
        ];
        
        return ApiResponse::success($permissions, 'Permissions retrieved successfully');
    }
}
