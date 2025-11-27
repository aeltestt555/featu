<?php
// app/Http/Controllers/Api/DashboardController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify');
        $this->middleware('permission:view-dashboard');
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'recent_users' => User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']),
        ];

        return response()->json([
            'message' => 'Dashboard data retrieved successfully',
            'stats' => $stats,
        ]);
    }

    public function stats()
    {
        return response()->json([
            'users' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'today_logins' => User::whereDate('last_login_at', today())->count(),
        ]);
    }
}