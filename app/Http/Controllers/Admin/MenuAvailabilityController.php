<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuAvailabilityController extends Controller
{
    public function index()
    {
        return view('admin.menu-availability.index');
    }

    public function update(Request $request)
    {
        return redirect()->route('admin.menu-availability.index')->with('success', 'Menu availability updated successfully');
    }
}
