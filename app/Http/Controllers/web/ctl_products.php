<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;

class ctl_products extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return view('web.products_index', []);
    }

    public function list_a(Request $request)
    {
        return view('web.products_a_index', []);
    }

    public function list_a1(Request $request)
    {
        return view('web.products_a1_index', []);
    }

    public function list_a2(Request $request)
    {
        return view('web.products_a2_index', []);
    }

    public function list_a3(Request $request)
    {
        return view('web.products_a3_index', []);
    }

    public function list_a4(Request $request)
    {
        return view('web.products_a4_index', []);
    }

    public function list_b(Request $request)
    {
        return view('web.products_b_index', []);
    }

    public function list_b1(Request $request)
    {
        return view('web.products_b1_index', []);
    }

    public function list_b2(Request $request)
    {
        return view('web.products_b2_index', []);
    }
}
