<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class DigitalMenuController extends Controller
{
    public function index(){
        $tables=Table::get();
        return view('admin.digitalmenu.index',compact('tables'));
    }
}
