<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piscina;
use Illuminate\Support\Facades\DB;

class PiscinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth.admin', ['except' =>
        [
            'ObtenerPiscinasActivas',
        ]]);
    
}
    //
    public function registrarPiscina(Request $request )
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

      $piscina = new Piscina();
      $piscina->nombre = $params->nombre;
      $piscina->area = $params->area;  
      $piscina->camaronera_id = $params->camaronera_id;  
      $piscina->estado_id = 1;         
         
      $piscina->save();    

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Piscina creada',

    ); 
      return response()->json($data, 200);        
    }

    public function ObtenerPiscinas()
    {
        $data = DB::table('piscina')->select('piscina.id','piscina.nombre as piscina', 'piscina.area', 'estado.tipo as estado', 'camaronera.nombre as camaronera', 'usuario.nombres as propietario')
        ->join('estado', 'piscina.estado_id', 'estado.id')
        ->join('camaronera', 'piscina.camaronera_id', 'camaronera.id')
        ->join('usuario', 'camaronera.propietario_id', 'usuario.id')
        ->get();
        return response()->json($data);
   
    } 

    public function ObtenerPiscinasActivas()
    {
        $data = DB::table('piscina')->select('piscina.id','piscina.nombre as piscina', 'piscina.area', 'estado.tipo as estado', 'camaronera.nombre as camaronera', 'usuario.nombres as propietario')
        ->join('estado', 'piscina.estado_id', 'estado.id')
        ->join('camaronera', 'piscina.camaronera_id', 'camaronera.id')
        ->join('usuario', 'camaronera.propietario_id', 'usuario.id')
        ->where('piscina.estado_id','1')
        ->get();
        return response()->json($data);
   
    }

    


    public function ObtenerPiscinaPorId($id)
    {
        return response()->json(Piscina::find($id)->load('estado')->load('camaronera'));
   
    }

    public function HabilitarDeshabilitarPiscina($id)
    {
        $piscina = Piscina::find($id);
        if ($piscina->estado_id == 1) {
        $piscina->estado_id = 2;
        $data = array(
          'icono' => 'success',
          'title' => 'Piscina deshabilitada',
          'mensaje' => 'Esta piscina está deshabilitada',
  
      );       
    } else{
        $piscina->estado_id = 1;
        $data = array(
          'icono' => 'success',
          'title' => 'Piscina habilitada',
          'mensaje' => 'Esta piscina está habilitada',
  
      ); 
    }
    $piscina->save();
    return response()->json($data, 200);   
   
    }



    public function EditarPiscina(Request $request, $id)
    {
      $piscina = Piscina::find($id);

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

      $piscina->nombre = $params->nombre;
      $piscina->area = $params->area;  
      $piscina->camaronera_id = $params->camaronera_id;  
      $piscina->estado_id = $params->estado_id;           
      $piscina->save();  

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Piscina Modificada',

    ); 
      return response()->json($data, 200);   

    
        
    }

}
