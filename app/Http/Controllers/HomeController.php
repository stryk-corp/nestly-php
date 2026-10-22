<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Hostel;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /** Landing page with live totals. Mirrors Django's home() view. */
    public function index()
    {
        return view('home', [
            'students_count'      => User::where('role', User::ROLE_STUDENT)->count(),
            'hostels_count'       => Hostel::where('is_active', true)->count(),
            'complaints_resolved' => Complaint::where('status', Complaint::STATUS_RESOLVED)->count(),
        ]);
    }
}
