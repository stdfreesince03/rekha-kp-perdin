<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\BusinessTrip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class HRTripController extends Controller
{
    public function history()
    {
        $trips = BusinessTrip::whereIn('status', ['APPROVED', 'REJECTED'])
            ->orderBy('approved_at', 'desc')
            ->with(['user', 'originCity', 'destinationCity'])
            ->get();

        return view('hr.trips-history', compact('trips'));
    }
}

