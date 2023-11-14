<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Componente;
use Illuminate\Support\Facades\DB;


class SinHidratarExport implements FromView
{


    public function __construct()
    {
      
    }
    public function view(): View
    {
        $query="SELECT DISTINCT(componente_id) FROM
        form_carga_bateria_view
        WHERE fecha >=DATE_ADD(NOW(), INTERVAL -60 DAY) AND h2o<>''";
        $hidrated=\DB::select(DB::Raw($query));
        $hidatados=array();
        foreach($hidrated as $key=>$h){
            $hidatados[$key]=$h->componente_id;
        }
        $data=Componente::whereTipoComponenteId(2)->whereNotIn('id',$hidatados)->get();

        return view('frontend.baterias.sinhidratar', [
          'data' => $data
        ]);
    }
}