<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camaronera;

class CamaroneraController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth.admin');
    
}
    //
    public function registrarCamaronera(Request $request )
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

      $camaronera = new Camaronera();
      $camaronera->propietario_id = $params->propietario_id;
      $camaronera->estado_id = 1;  
      $camaronera->nombre = $params->nombre;           
      $camaronera->save();    

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Camaronera creada',

    ); 
      return response()->json($data, 200);
        
    }

    public function ObtenerCamaroneras()
    {
      
        return response()->json(Camaronera::select('*')->orderBy('estado_id')->get()->load('estado')->load('propietario'));
   
    }

    public function ObtenerCamaroneraPorId($id)
    {
        return response()->json(Camaronera::find($id)->load('estado'));
   
    }

    public function HabilitarDeshabilitarCamaronera($id)
    {
    $camaronera = Camaronera::find($id);
    if ($camaronera->estado_id == 1) {
        $camaronera->estado_id = 2;
        $data = array(
          'icono' => 'success',
          'title' => 'Camaronera deshabilitada',
          'mensaje' => 'Esta camaronera está deshabilitada',
  
      );       
    } else{
        $camaronera->estado_id = 1;
        $data = array(
          'icono' => 'success',
          'title' => 'Camaronera habilitada',
          'mensaje' => 'Esta camaronera está habilitada',
  
      );  
    }
    $camaronera->save();
    return response()->json($data, 200);   
   
    }



    public function EditarCamaronera(Request $request, $id)
    {
      $camaronera = Camaronera::find($id);
      if ($camaronera) {
      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

      $camaronera->propietario_id = $params->propietario_id;
      $camaronera->estado_id = $params->estado_id;  
      $camaronera->nombre = $params->nombre;           
      $camaronera->save();  
      return true;

    } else {
      return false;
    }

    
        
    }
}
