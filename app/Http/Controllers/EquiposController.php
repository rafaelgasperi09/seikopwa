<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\TipoEquipo;
use App\Equipo;
use App\SubEquipo;

class EquiposController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $subEquipos=SubEquipo::orderBy('id','desc')->get();
        $tipoEquipos=Equipo::select('equipos.sub_equipos_id','equipos.tipo_equipos_id','tipo_equipos.display_name')
                                ->join('tipo_equipos','equipos.tipo_equipos_id','=','tipo_equipos.id')
                                ->groupBy('equipos.sub_equipos_id','equipos.tipo_equipos_id')
                                ->get();
                                
        $tipoEquiposArray=array();
        foreach($tipoEquipos as $t){
            $tipoEquiposArray[$t->sub_equipos_id][$t->tipo_equipos_id]=$t->display_name;
        }
        return view('frontend.equipos.index')->with('tipos',$tipoEquiposArray)->with('subEquipos',$subEquipos);
  
    }

    public function tipo($sub,$id){
 
        $datos=array('sub'=>$sub,
                     'tipo'=>$id,
                     'subName'=>getSubEquipo($sub,'name'),
                     'tipoName'=>getTipoEquipo($id));
        if($id=='todos')
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->paginate(10);
        else
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->paginate(10);

            
        return view('frontend.equipos.index')->with('equipos',$equipos)->with('datos',$datos); 
    }  

    public function search(Request $request,$sub,$id){
        if($id=='todos')
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->where('numero_parte','like',"%".$request->q."%")->paginate(10);
        else
            $equipos=Equipo::where('sub_equipos_id',getSubEquipo($sub))->where('tipo_equipos_id',$id)->where('numero_parte','like',"%".$request->q."%")->paginate(10);

        return view('frontend.equipos.page')->with('data',$equipos); 
    }      
    
    
    public function detail($id){

        $data = Equipo::findOrFail($id);
        return view('frontend.equipos.detail')->with('data',$data);
    }
}
