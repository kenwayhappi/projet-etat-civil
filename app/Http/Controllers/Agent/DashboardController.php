<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Act;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $center = $user->center;
        $created_acts = Act::where('created_by', $user->id)->count();
        $validated_acts = Act::where('created_by', $user->id)->where('status', 'validated')->count();
        $pending_acts = Act::where('created_by', $user->id)->where('status', 'pending')->count();

        return view('agent.dashboard', compact('center', 'created_acts', 'validated_acts', 'pending_acts'));
    }
}
