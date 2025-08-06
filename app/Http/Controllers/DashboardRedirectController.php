<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardRedirectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Admin users go to Filament admin panel
        if ($user->isAdmin()) {
            return redirect('/admin');
        }
        
        // Students go to student dashboard
        if ($user->isStudent()) {
            return redirect()->route('frontend.student.dashboard');
        }
        
        // Teachers go to admin panel with limited access
        if ($user->isTeacher()) {
            return redirect('/admin');
        }
        
        // Default: redirect to home
        return redirect()->route('frontend.home');
    }
}