<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sentinel;
use Cartalyst\Sentinel\Roles\RoleInterface;
use App\Cliente;
use Illuminate\Support\Facades\Schema;

class MaestrosController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /grupo
     *
     * @return Response
     */
    public function index(Request $request)
    {
       
        $data='';
        return view('frontend.maestros.index')->with('data',$data);
    }
    public function clientes(Request $request)
    {
        $columns = Schema::getColumnListing('contactos');
        $data=Cliente::get();
        return view('frontend.maestros.clientes ')->with(compact('data','columns'));
    }
    public function clientes_create()
    {
        $columns = Schema::getColumnListing('contactos');
        $data=Cliente::get();
        return view('frontend.maestros.clientes_create')->with(compact('data','columns'));
    }
}