<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    public function index()
    {
        return view('admin.combos.index');
    }

    public function create()
    {
        return view('admin.combos.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.combos.index')->with('success', 'Combo created successfully');
    }

    public function edit($id)
    {
        return view('admin.combos.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.combos.index')->with('success', 'Combo updated successfully');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.combos.index')->with('success', 'Combo deleted successfully');
    }
}
