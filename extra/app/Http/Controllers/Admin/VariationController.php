<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function index()
    {
        return view('admin.variations.index');
    }

    public function create()
    {
        return view('admin.variations.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.variations.index')->with('success', 'Variation created successfully');
    }

    public function edit($id)
    {
        return view('admin.variations.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.variations.index')->with('success', 'Variation updated successfully');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.variations.index')->with('success', 'Variation deleted successfully');
    }
}
