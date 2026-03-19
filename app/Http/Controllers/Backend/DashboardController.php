<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalSalary = User::sum('salary');
        $recentUsers = User::latest()->take(5)->get();

        return view('backend.pages.index', compact('totalUsers', 'totalRoles', 'totalSalary', 'recentUsers'));
    }
}
