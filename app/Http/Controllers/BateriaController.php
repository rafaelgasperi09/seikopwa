<?php

namespace App\Http\Controllers;

use App\Componente;
use Illuminate\Http\Request;

class BateriaController extends Controller
{
    public function index(){

        $data = Componente::whereTipoComponente(2)->get();
        return view('frontend.baterias.index')->with('data',$data);
    }
}
