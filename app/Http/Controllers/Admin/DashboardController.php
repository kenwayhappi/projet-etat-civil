<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $centersCount = Center::count();
        $regionsCount = Region::count();
        $departmentsCount = Department::count();
        $usersCount = User::count();

        return view('admin.dashboard.index', compact('centersCount', 'regionsCount', 'departmentsCount', 'usersCount'));
    }
}
