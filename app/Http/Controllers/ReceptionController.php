<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class ReceptionController extends Controller
{
    public function list()
    {
        return view('admin.reception.list');
    }
    public function create()
    {
        $brands = Brand::with('carModels')->get();

        return view('admin.reception.create', compact('brands'));
    }
    public function quotation()
    {
        return view('admin.reception.quotation');
    }
}
