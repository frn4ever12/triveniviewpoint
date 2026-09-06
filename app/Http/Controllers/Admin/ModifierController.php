<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    public function index()
    {
        return view('admin.modifiers.index');
    }

    public function create()
    {
        return view('admin.modifiers.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.modifiers.index')->with('success', 'Modifier created successfully');
    }

    public function edit($id)
    {
        return view('admin.modifiers.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.modifiers.index')->with('success', 'Modifier updated successfully');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.modifiers.index')->with('success', 'Modifier deleted successfully');
    }
}
