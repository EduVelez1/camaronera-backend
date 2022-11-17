<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alimento;

class AlimentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    
    }
    public function registrarAlimento(Request $request)
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto      
      $alimento = new Alimento();
      $alimento->responsable = $params->responsable;
      $alimento->fecha = $params->fecha;
      $alimento->tipo = $params->tipo;
      $alimento->cantidad = $params->cantidad;
      $alimento->detalle = $params->detalle;
      $alimento->costo = $params->cantidad * 30;
      $alimento->produccion_id = $params->produccion_id;

      $alimento->save();   

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Alimento Registrado',

    ); 
      return response()->json($data, 200); 
    }

    public function obtenerAlimentoPorProduccion($id)
    {
      return Alimento::where('produccion_id', $id)->get();        
    }

    public function obtenerAlimento($id)
    {
      return Alimento::where('id', $id)->first();        
    }

    public function editarAlimento(Request $request, $id)
    {
      $alimento = Alimento::where('id', $id)->first();  
      
      $json = $request->input('json', null);
      $params = json_decode($json); //objeto      

      $alimento->responsable = $params->responsable;
      $alimento->fecha = $params->fecha;
      $alimento->tipo = $params->tipo;
      $alimento->cantidad = $params->cantidad;
      $alimento->detalle = $params->detalle;
      $alimento->costo = $params->costo;
      $alimento->produccion_id = $params->produccion_id;

      $alimento->save();   

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Alimento Modificado',

    ); 
      return response()->json($data, 200); 
    }

    public function EliminarAlimento($id)
    {
        $alimento = Alimento::find($id);  
        $alimento->delete();   

        $data = array(
          'icono' => 'success',
          'title' => 'Eliminación Correcta',
          'mensaje' => 'Alimento Eliminado',
  
      ); 
        return response()->json($data, 200); 
        
    }

}
