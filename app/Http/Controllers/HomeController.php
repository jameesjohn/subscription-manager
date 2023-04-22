<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home() {
        $apiKey = ApiKey::first();
        if ($apiKey) {
            return redirect()->route('dashboard');
        }

        return view('welcome');
    }

    public function dashboard() {
        $apiKey = ApiKey::first();
        if (!$apiKey) {
            return redirect()->route('home');
        }

        return view('dashboard');
    }
}
