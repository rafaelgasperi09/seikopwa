<?php

namespace App\Http\Controllers;

use App\Componente;
use Illuminate\Http\Request;

class BateriaController extends Controller
{
    public function index(){

        $data = Componente::whereTipoComponenteId(2)->paginate(10);
        return view('frontend.baterias.index',compact('data'));
    }

    public function page(){

        $data = Componente::whereTipoComponenteId(2)->paginate(10);
        return view('frontend.baterias.page',compact('data'));
    }

    public function search(Request $request){

        $data = Componente::whereTipoComponenteId(2)->where('id_componente','LIKE',"%".$request->q."%")->paginate(10);
        return view('frontend.baterias.page',compact('data'));
    }


    protected function detail($id){

        $data = Componente::findOrFail($id);
        return view('frontend.baterias.detail')->with('data',$data);
    }
}
