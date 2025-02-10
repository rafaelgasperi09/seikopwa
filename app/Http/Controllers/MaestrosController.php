<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sentinel;
use Cartalyst\Sentinel\Roles\RoleInterface;

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
}