<?php

namespace App\Exports\Horizontal;
use App\Models\Salr;
use App\Models\GLAccountFC;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class H_Salr implements FromView
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        // $data['exportsalr'] = Salr::all();
        // return view('pages.buku_besar.salr.exportsalr',$data);

        // $user = User::where('type', '=', 'BranchAdmin');
        // $branch = Branch::all();
        // return view('Branch.branchinfo')->with('branch',$branch)->with('user', $user);
        $dataGlaccount = GLAccountFC::all();
        $dataSalr = Salr::all();
        return view('pages.buku_besar.salr.exportsalr')->with('dataGlaccount',$dataGlaccount)->with('dataSalr', $dataSalr);
        // return view('pages.buku_besar.salr.exportsalr',$data);
        }
}
