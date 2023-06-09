<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Componente;
use Illuminate\Support\Facades\DB;


class BateriasExport implements FromView
{
    protected $id;

    public function __construct($id)
    {
       $this->id = $id;
    }
    public function view(): View
    {
        $bateria = Componente::findOrFail( $this->id);
        $data = DB::table('form_carga_bateria_view')
            ->where('componente_id', $this->id)
            ->orderBy('fecha','DESC')
            ->get()->take(100);

        return view('frontend.baterias.excel', [
            'bateria'=>$bateria,'data' => $data
        ]);
    }
}