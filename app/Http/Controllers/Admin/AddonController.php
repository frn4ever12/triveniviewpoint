<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    public function index()
    {
        return view('admin.addons.index');
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(Request $request)
    {
        // Add store logic here
        return redirect()->route('admin.addons.index')->with('success', 'Addon created successfully');
    }

    public function edit($id)
    {
        return view('admin.addons.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Add update logic here
        return redirect()->route('admin.addons.index')->with('success', 'Addon updated successfully');
    }

    public function destroy($id)
    {
        // Add destroy logic here
        return redirect()->route('admin.addons.index')->with('success', 'Addon deleted successfully');
    }
}
