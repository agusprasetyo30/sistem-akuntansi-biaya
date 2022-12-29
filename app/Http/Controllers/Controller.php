<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function pageNotFound()
    {
        return response()->view('errors.404');
    }

    public function accessForbidden()
    {
        return response()->view('errors.403');
    }

    public function makeValidMsg($validator)
    {
        $msg = '';

        foreach ($validator->errors()->all() as $message)
            $msg .= '<p>' . $message . '</p>';

        return response()->json([
            'title' => 'Oopss...',
            'msg' => $msg,
            'type' => 'error'
        ], 400);
    }
}
