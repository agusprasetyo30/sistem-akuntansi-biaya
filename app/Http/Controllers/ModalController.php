<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function getModal()
    {
        return view('components.modal');
    }
}
