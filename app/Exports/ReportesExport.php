<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Componente;
use Illuminate\Support\Facades\DB;


class ReportesExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
       $this->data = $data;
    }
    public function view(): View
    {
        return view('frontend.equipos.reportes_excel', ['data' => $this->data ]);
    }
}