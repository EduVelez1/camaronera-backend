<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produccion;
use App\Models\Gramaje;
use App\Models\Biomasa;
use App\Models\Alimento;
use App\Models\Datos_Biomasa;
use App\Models\Larva;


use Illuminate\Support\Facades\DB;

class ProduccionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('api.auth');    
    }

    public function registrarProduccion(Request $request)
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

      if ($params->fecha_apertura != null) {

        
        $produccion = new Produccion();
        $produccion->piscina_id = $params->piscina_id;
        $produccion->fecha_apertura = $params->fecha_apertura;
        $produccion->larva = $params->larva;
        $produccion->cantidad = $params->cantidad;
        $produccion->costo_larva = $params->costo_larva;

        $larva = Larva::find($params->larva);

      

        if ((int)$params->cantidad <= (int)$larva->disponibles) {

          $larva->usadas = $params->cantidad;
          $larva->disponibles = $larva->disponibles - $params->cantidad;

          $larva->save();

        $produccion->save();    
      
        $data = array(
          'icono' => 'success',
          'title' => 'Registro Correcto',
          'mensaje' => 'Produccion creada',
  
      );      
    } else{
      $data = array(
        'icono' => 'error',
        'title' => 'Larvas Insuficientes',
        'mensaje' => 'La cantidad de larvas supera el stock',

    ); 
    }

      } else {
        $data = array(
          'icono' => 'error',
          'title' => 'Algo salió mal',
          'mensaje' => 'Asegurese de ingresar todos los datos',
  
      );      

      }

      return response()->json($data, 200);        
    }

   
public function ObtenerProduccionesActivas()
{      
 
   return DB::table('produccion')
        ->select('produccion.id','produccion.fecha_apertura','produccion.fecha_cierre', 'larva.tipo as larva', 'produccion.cantidad', 'produccion.costo_larva', 'piscina.nombre as piscina', 'camaronera.nombre as camaronera', 'usuario.nombres as propietario')
        ->where('produccion.fecha_cierre', null)
        ->join('piscina', 'piscina.id', '=', 'produccion.piscina_id')
        ->join('camaronera', 'camaronera.id', '=', 'piscina.camaronera_id')
        ->join('usuario', 'usuario.id', '=', 'camaronera.propietario_id')
        ->join('larva', 'larva.id', '=', 'produccion.larva')
        ->orderBy('produccion.fecha_apertura')
        ->get();
}

public function ObtenerProduccionesInactivas()
{      
 
   return DB::table('produccion')
        ->select('produccion.id','produccion.fecha_apertura','produccion.fecha_cierre','larva.tipo as larva', 'produccion.cantidad', 'produccion.costo_larva', 'piscina.nombre as piscina', 'camaronera.nombre as camaronera', 'usuario.nombres as propietario')
        ->where('produccion.fecha_cierre', '!=', null)
        ->join('piscina', 'piscina.id', '=', 'produccion.piscina_id')
        ->join('camaronera', 'camaronera.id', '=', 'piscina.camaronera_id')
        ->join('usuario', 'usuario.id', '=', 'camaronera.propietario_id')
        ->join('larva', 'larva.id', '=', 'produccion.larva')
        ->orderBy('produccion.fecha_apertura')
        ->get();
}

public function ObtenerProduccionPorId($id)
{
    return DB::table('produccion')
    ->select('produccion.id','produccion.fecha_apertura','produccion.fecha_cierre', 'produccion.cantidad', 'piscina.nombre as piscina', 'produccion.costo_larva', 'camaronera.nombre as camaronera', 'usuario.nombres as propietario')
    ->where('produccion.id', $id)
    ->join('piscina', 'piscina.id', '=', 'produccion.piscina_id')
    ->join('camaronera', 'camaronera.id', '=', 'piscina.camaronera_id')
    ->join('usuario', 'usuario.id', '=', 'camaronera.propietario_id')
    ->first();   
}





    public function EliminarProduccion($id)
    {
        $produccion = Produccion::find($id);

        if ($produccion && $produccion->fecha_cierre == null) {

             $gramaje = Gramaje::where('produccion_id', $id)->first();

            if (empty($gramaje)) {

                $biomasa = Biomasa::where('produccion_id', $id)->first();

                if (empty($biomasa)) {
                $produccion->delete();
                return true;   

                } else $response = 'Biomasa registrada';
                
            } else $response = 'Gramaje registrado';


        } else $response = 'Produccion cerrada';
        
        return $response;   

    }

    public function CerrarProduccion(Request $request, $id)
    {
        $json = $request->input('json', null);
        $params = json_decode($json); //objeto

      $produccion = Produccion::find($id);

      $produccion->fecha_cierre = $params->fecha_cierre;
      $produccion->save();    
    
      return true;
        
    }


    public function ObtenerDatosCalendario($id)
    {
      $result = array();
      
      $biomasa = Biomasa::select('id', DB::raw("CONCAT('Biomasa') as title"), 'fecha as start', DB::raw("CONCAT('#FFEF7E') as color"), DB::raw("CONCAT('black') as textColor"))
                          ->where('produccion_id', $id)
                          ->get();

      $gramaje = Gramaje::select('id', DB::raw("CONCAT('Gramaje') as title"), 'fecha as start', DB::raw("CONCAT('#8EE6FE') as color"), DB::raw("CONCAT('black') as textColor"))
                        ->where('produccion_id', $id)
                        ->get();  

      $alimento = Alimento::select('id', DB::raw("CONCAT('Alimento') as title"), 'fecha as start', DB::raw("CONCAT('#B2FB9B') as color"), DB::raw("CONCAT('black') as textColor"))
                        ->where('produccion_id', $id)
                        ->get();                    

      foreach ($biomasa as $bio) {
        $result[] = $bio;
      }

      foreach ($gramaje as $gram) {
        $result[] = $gram;
      }

      foreach ($alimento as $alim) {
        $result[] = $alim;
      }
 
      return $result;
        
    }


    public function reporteProduccion($idProduccion)
    {
      // $produccion =  DB::table('produccion')
      // ->select('produccion.fecha_apertura as fecha_apertura_produccion', 'produccion.fecha_cierre as fecha_cierre_produccion', 'piscina.nombre as piscina')
      // ->where('produccion.id', $idProduccion)
      // ->join('piscina', 'piscina.id', '=', 'produccion.piscina_id')
      // ->get();


      $biomasa =  DB::table('biomasa')
            ->select('produccion.fecha_apertura as fecha_apertura_produccion', 'produccion.fecha_cierre as fecha_cierre_produccion', 'piscina.nombre as piscina',
              'biomasa.id','biomasa.responsable as responsable_biomasa','biomasa.fecha as fecha_biomasa', 'biomasa.area_red', 'biomasa.cantidad_total', 'biomasa.cantidad_camarones', 'biomasa.dias_siembra', 'biomasa.detalle as detalle_biomasa')
            ->where('biomasa.produccion_id', $idProduccion)
            ->join('produccion', 'produccion.id', '=', 'biomasa.produccion_id')
            ->join('piscina', 'piscina.id', '=', 'produccion.piscina_id')

            ->get();
      
      $gramaje =  DB::table('gramaje')
      ->select('produccion.fecha_apertura as fecha_apertura_produccion', 'produccion.fecha_cierre as fecha_cierre_produccion', 'piscina.nombre as piscina',
        'gramaje.id','gramaje.responsable as responsable_gramaje','gramaje.fecha as fecha_gramaje', 'gramaje.peso_promedio', 'gramaje.incremento', 'gramaje.dias_siembra', 'gramaje.detalle as detalle_gramaje')
      ->where('gramaje.produccion_id', $idProduccion)
      ->join('produccion', 'produccion.id', '=', 'gramaje.produccion_id')
      ->join('piscina', 'piscina.id', '=', 'produccion.piscina_id')
      ->get(); 

      $alimento =  DB::table('alimento')
      ->select('alimento.id','alimento.responsable as responsable_alimento','alimento.tipo','alimento.fecha as fecha_alimento', 'alimento.cantidad as cantidad_alimento', 'alimento.detalle as alimento_detalle')
      ->where('alimento.produccion_id', $idProduccion)
      ->get();       
            
      // $result = array(
      //   'produccion' => $produccion,
      //   'biomasa' => $biomasa,
      //   'gramaje' => $gramaje,
      //   'alimento' => $alimento

      // );
      $result = array(
        $biomasa,
        $gramaje,
        $alimento

      );


      return $result;   
    }

    
    public function produccionActiva($idProduccion){
     $produccion = Produccion::where('id', $idProduccion)->first();

     if ($produccion->fecha_cierre == null) {
      return true;
       
     } else {
       return false;
     }



    }


    public function costosProduccion($id)
    {
      
      $c_alimento = 0.0;
      $c_biomasa = 0.0;
      $c_gramaje = 0.0;

      $alimento = Alimento::where('produccion_id', $id)->get();      
      $biomasa = Biomasa::where('produccion_id', $id)->get();      
      $gramaje = Gramaje::where('produccion_id', $id)->get();   

      foreach ($alimento as $valor) {
        # code...
        $c_alimento = $c_alimento + (float)$valor->costo;
      }
      foreach ($biomasa as $valor) {
        # code...
        $c_biomasa = $c_biomasa + (float)$valor->costo;
      }
      foreach ($gramaje as $valor) {
        # code...
        $c_gramaje = $c_gramaje + (float)$valor->costo;
      }
      
      $data = array(
        'costo_alimento' => $c_alimento,
        'costo_biomasa' => $c_biomasa,
        'costo_gramaje' => $c_gramaje
      );

      return $data;
      
    }






}
