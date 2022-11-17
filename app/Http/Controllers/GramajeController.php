<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gramaje;
use App\Models\Datos_Gramaje;

class GramajeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('api.auth');
    
    }

    public function registrarGramaje(Request $request)
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto
      
      // $promedio = $this->obtenerPromedio($params->datos);

      $gramaje = new Gramaje();
      $gramaje->responsable = $params->responsable;
      $gramaje->fecha = $params->fecha;
      $gramaje->peso_promedio = 0;
      $gramaje->incremento = $params->incremento;
      $gramaje->dias_siembra = $params->dias_siembra;
      $gramaje->detalle = $params->detalle;
     // $gramaje->costo = $params->costo;

      $gramaje->produccion_id = $params->produccion_id;
      $gramaje->save();    

      // guardar los datos relacionados al gramaje
      // $this->registrarDatosGramaje($params->datos, $gramaje->id);

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Gramaje Registrado',

    ); 
      return response()->json($data, 200);          
    }

    public function obtenerPromedio($datos)
    {
        $suma = 0;
        $promedio=0;
        foreach ($datos as $d) {
            $suma = $suma + $d->peso;            
        }    
        $promedio = $suma/count($datos);         
      return $promedio;

    }

    public function registrarDatosGramaje($datos, $id)
    {    
        foreach ($datos as $d) {  
            $datosGramaje = new Datos_Gramaje();
            $datosGramaje->gramaje_id = $id;
            $datosGramaje->cantidad = $d->cantidad;
            $datosGramaje->peso = $d->peso;
            $datosGramaje->save();
        }
      return true;        
    }

    public function obtenerGramajePorProduccion($id)
    {
      $gramaje = Gramaje::where('produccion_id', $id)->get();      

      return $gramaje;     
    }

    public function obtenerGramajePorProduccionDetalle($idGramaje)
    {
      $datosGramaje = Datos_Gramaje::where('gramaje_id', $idGramaje)->get();  
      $gramaje = Gramaje::find($idGramaje);   
      $response = array(
        $gramaje, 
        $datosGramaje
      );   

      return $response;     
    }

    public function editarGramaje(Request $request, $idGramaje)
    {

      $gramaje = Gramaje::where('id', $idGramaje)->first();  
     
      $json = $request->input('json', null);
      $params = json_decode($json); //objeto
      
     // $promedio = $this->obtenerPromedio($params->datos);

   //   $gramaje = new Gramaje();
      $gramaje->responsable = $params->responsable;
      $gramaje->fecha = $params->fecha;
   //   $gramaje->peso_promedio = $promedio;
      $gramaje->incremento = $params->incremento;
      $gramaje->dias_siembra = $params->dias_siembra;
      $gramaje->detalle = $params->detalle;
   //   $gramaje->costo = $params->costo;

      $gramaje->produccion_id = $params->produccion_id;
      $gramaje->save();    

      // guardar los datos relacionados al gramaje
   //   $this->registrarDatosGramaje($params->datos, $gramaje->id);

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Gramaje Modificado',

    ); 
      return response()->json($data, 200); 
    }

    public function EliminarGramaje($id)
    { 
       $datos_gramaje = Datos_Gramaje::where('gramaje_id', $id)->get();

       foreach ($datos_gramaje as $dato ) {
          $dato->delete();   
       }
        $gramaje = Gramaje::find($id);  
        $gramaje->delete();   

        $data = array(
          'icono' => 'success',
          'title' => 'Eliminación Correcta',
          'mensaje' => 'Gramaje Eliminado',
  
      ); 
        return response()->json($data, 200); 
        
    }

}
