<?php

namespace App\Exports;
namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Componente;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GenericExcel implements FromView,ShouldAutoSize,WithColumnFormatting
{
    protected $data;
    protected $view;
    protected $format;

    /**
     * @param $data

     */

    public function __construct($data,$view='frontend.reportes.generico',$format=array())
    {
        $this->data = $data;
        $this->view = $view;
        $this->format = $format;

    }

    public function view(): View
    {


        return view($this->view, [
            'data' =>  $this->data
        ]);
    }

    public function columnFormats(): array
    {
        return $this->format;
    }

}
