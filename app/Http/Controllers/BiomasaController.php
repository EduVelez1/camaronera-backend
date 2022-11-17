<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biomasa;
use App\Models\Datos_Biomasa;

class BiomasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth');
    
    }
    public function registrarBiomasa(Request $request)
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto
      
      // $cantidad = $this->sumaTotalDatos($params->datos);

      $biomasa = new Biomasa();
      $biomasa->responsable = $params->responsable;
      $biomasa->fecha = $params->fecha;
      $biomasa->area_red = $params->area_red;
      // $biomasa->cantidad_total = $cantidad;
      $biomasa->cantidad_total = 0;
      // $biomasa->cantidad_camarones = round(($cantidad / count($params->datos)) * $params->cantidad_camarones, 2);
      $biomasa->cantidad_camarones = 0;
      $biomasa->dias_siembra = $params->dias_siembra;
      $biomasa->detalle = $params->detalle;
    //  $biomasa->costo = $params->costo;

      $biomasa->produccion_id = $params->produccion_id;

      $biomasa->save();    

      // guardar los datos relacionados al gramaje
      // $this->registrarDatosBiomasa($params->datos, $biomasa->id);

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Bioamasa Registrada',

    ); 
      return response()->json($data, 200);     
    }

    public function sumaTotalDatos($datos)
    {
        $suma = 0;
        foreach ($datos as $d) {
            $suma = $suma + $d->cantidad;            
        }    
      return $suma;

    }

    public function registrarDatosBiomasa($datos, $id)
    {    
        foreach ($datos as $d) {  
            $datosBiomasa = new Datos_Biomasa();
            $datosBiomasa->biomasa_id = $id;
            $datosBiomasa->cantidad = $d->cantidad;
            $datosBiomasa->save();
        }
      return true;        
    }

    public function obtenerBiomasaPorProduccion($id)
    {
      $biomasa = Biomasa::where('produccion_id', $id)->get();  
      
      return $biomasa;     
    }

    public function obtenerBiomasaPorProduccionDetalle($idBiomasa)
    {
      $datosBiomasa = Datos_Biomasa::where('biomasa_id', $idBiomasa)->get();  
      $biomasa = Biomasa::find($idBiomasa);   
      $response = array(
        $biomasa,
        $datosBiomasa
      );   

      return $response;     
    }

    public function editarBiomasa(Request $request, $idBiomasa)
    {
     // $biomasa = Biomasa::find($idBiomasa);   
      $biomasa = Biomasa::where('id', $idBiomasa)->first();  
      $json = $request->input('json', null);
      $params = json_decode($json); //objeto
      
     // $cantidad = $this->sumaTotalDatos($params->datos);

      $biomasa->responsable = $params->responsable;
      $biomasa->fecha = $params->fecha;
      $biomasa->area_red = $params->area_red;
      //$biomasa->cantidad_total = $cantidad;
      $biomasa->cantidad_camarones = $params->cantidad_camarones;
      $biomasa->dias_siembra = $params->dias_siembra;
      $biomasa->detalle = $params->detalle;
     // $biomasa->costo = $params->costo;

      $biomasa->produccion_id = $params->produccion_id;

      $biomasa->save();    

      // guardar los datos relacionados al gramaje
    //  $this->registrarDatosBiomasa($params->datos, $biomasa->id);

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Bioamasa Modificada',

    ); 
      return response()->json($data, 200);

    }

    public function EliminarBiomasa($id)
    { 
       $datos_biomasa = Datos_Biomasa::where('biomasa_id', $id)->get();

       foreach ($datos_biomasa as $dato ) {
          $dato->delete();   
       }
        $biomasa = Biomasa::find($id);  
        $biomasa->delete();   

        $data = array(
          'icono' => 'success',
          'title' => 'Eliminación Correcta',
          'mensaje' => 'Biomasa Eliminada',
  
      ); 
        return response()->json($data, 200); 
        
    }
}
