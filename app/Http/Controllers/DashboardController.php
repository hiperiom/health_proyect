<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard(){
        return Inertia::render("Dashboard");
    }
    public function patients(){
        return Inertia::render("Dashboard/Patients/Index");
    }
    public function doctors(){
        return Inertia::render("Dashboard/Doctors");
    }
}
