<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FAQsController extends Controller
{
    public function index()
    {
        return Inertia::render('FAQs');
    }

}
