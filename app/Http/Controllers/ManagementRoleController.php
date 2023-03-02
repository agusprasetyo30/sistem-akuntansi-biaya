<?php

namespace App\Http\Controllers;

use App\DataTables\Master\MapRoleDataTable;
use Illuminate\Http\Request;

class ManagementRoleController extends Controller
{
    public function index(Request $request, MapRoleDataTable $maproleDataTable)
    {
        if ($request->data == 'index') {
            return $maproleDataTable->render('pages.master.maprole.index');
        }
        return view('pages.master.maprole.index');
    }
}
