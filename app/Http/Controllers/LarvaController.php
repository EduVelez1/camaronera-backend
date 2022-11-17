<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Larva;

class LarvaController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('api.auth.admin', ['except' =>
        [
            'obtenerLarvas',
            'ObtenerLarvaPorId',

        ]]);
    
    }

    public function registrarLarva(Request $request)
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto


        
        $larva = new Larva();
        $larva->id_proveedor = $params->id_proveedor;
        $larva->tipo = $params->tipo;
        $larva->cantidad = $params->cantidad;
        $larva->precio = $params->precio;
        $larva->usadas = 0;
        $larva->disponibles = $params->cantidad;
        $larva->save();    
      
        $data = array(
          'icono' => 'success',
          'title' => 'Registro Correcto',
          'mensaje' => 'Producto creado',
  
      );      


      return response()->json($data, 200);        
    }

    public function obtenerLarvas()
    { 
      
        return response()->json(Larva::select('*')->get()->load('proveedor'));
   
    }

    public function ObtenerLarvaPorId($id)
    {
        return response()->json(Larva::find($id)->load('proveedor'));
   
    } 


    public function EditarLarva(Request $request, $id)
    {
      $larva = Larva::find($id);

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

        $larva->id_proveedor = $params->id_proveedor;
        $larva->tipo = $params->tipo;
        $larva->cantidad = $params->cantidad;
        $larva->precio = $params->precio;        
        $larva->save();  

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Larva Modificada',

    ); 
      return response()->json($data, 200);       
        
    }

    public function EliminarLarva($id)
    {
        $larva = Larva::find($id);  

        if ($larva->usadas == 0) {
          $larva->delete();   
  
          $data = array(
            'icono' => 'success',
            'title' => 'Eliminación Correcta',
            'mensaje' => 'Larva Eliminada',
    
        ); 
        }else{
          $data = array(
            'icono' => 'error',
            'title' => 'Larvas en uso',
            'mensaje' => 'Larvas estan siendo usadas en el proceso',
    
        );
        }
        return response()->json($data, 200); 
        
    }

}
