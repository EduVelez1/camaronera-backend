<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{

    public function __construct()
    {
        // $this->middleware('api.auth.admin');
    
}

    public function registrarUsuarios(Request $request )
    {

      $json = $request->input('json', null);
      $params = json_decode($json); //objeto

     $cedula = Usuario::where('cedula', $params->cedula)->first();



      if ($cedula) {
      //  return "Cedula existente";
      $data = array(
          'icono' => 'error',
          'title' => 'Algo salió mal',
          'mensaje' => 'Cedula existente',

      );
        return response()->json($data, 200);;

      }

      $correo = Usuario::where('correo', $params->correo)->first();
     
      if ($correo) {
        $data = array(
            'icono' => 'error',
            'title' => 'Algo salió mal',
            'mensaje' => 'Correo existente',
  
        );      
          return response()->json($data, 200);

     }

      //crear area
      $usuario = new Usuario();
      $pwd = hash('sha256', $params->contrasena);

      $usuario->cedula = $params->cedula;
      $usuario->nombres = $params->nombres;
      $usuario->nick = $params->nick;
      $usuario->correo = $params->correo;
      $usuario->contrasena = $pwd;
      $usuario->telefono = $params->telefono;
      $usuario->descripcion = $params->descripcion;
      $usuario->role_id = $params->role_id;  
      $usuario->estado_id = $params->estado_id;              
      $usuario->save();    

      $data = array(
        'icono' => 'success',
        'title' => 'Registro Correcto',
        'mensaje' => 'Usuario creado',

    ); 
      return response()->json($data, 200);

    }
    public function ObtenerUsuarioPorTipoRole($idRole){        
        return response()->json(Usuario::select('id','cedula','nombres','correo','telefono','role_id', 'estado_id')->orderBy('estado_id')->where('role_id', $idRole)->get()->load('role')->load('estado'));
    }

    public function ObtenerUsuarioPorId($id){        
        return response()->json(Usuario::find($id));
    }

    public function EditarUsuario(Request $request, $id )
    {

        $usuario = Usuario::find($id);

        if($usuario){

            $json = $request->input('json', null);
            $params = json_decode($json); //objeto
           
            $usuario->nombres = $params->nombres;
            $usuario->nick = $params->nick;        
            $usuario->telefono = $params->telefono;
            $usuario->descripcion = $params->descripcion;
            $usuario->role_id = $params->role_id;  
            $usuario->estado_id = $params->estado_id;                
            $usuario->save(); 
            $data = array(
                'icono' => 'success',
                'title' => 'Edición Correcta',
                'mensaje' => 'Usuario editado',
        
            ); 
              return response()->json($data, 200);
        } else {
            return false;

        }
     
        
    }

    public function CambiarContrasena(Request $request, $id )
    {

        $usuario = Usuario::find($id);

        if($usuario){

            $json = $request->input('json', null);
            $params = json_decode($json); //objeto
            
            $pwd = hash('sha256', $params->contrasena);
            $usuario->contrasena = $pwd;                         
            $usuario->save(); 
       
            return true;

        } else {
            return false;

        }
     
        
    }

    public function HabilitarDeshabilitarUsuario($id)
    {
    $usuario = Usuario::find($id);
    if ($usuario->estado_id == 1) {
        $usuario->estado_id = 2;

        $data = array(
            'icono' => 'success',
            'title' => 'Usuario deshabilitado',
            'mensaje' => 'Este usuario está deshabilitado',
    
        );       
    } else{
        $usuario->estado_id = 1;
        $data = array(
            'icono' => 'success',
            'title' => 'Usuario habilitado',
            'mensaje' => 'Este usuario está habilitado',    
        ); 
      
    }
    $usuario->save();
    return response()->json($data, 200);   
    }

    public function totalUsuarios()
    {
    $administradores = Usuario::all()->where('role_id', 1)->count();
    $empleados = Usuario::all()->where('role_id', 2)->count();
    $proveedores = Usuario::all()->where('role_id', 3)->count();
    $propietarios = Usuario::all()->where('role_id', 4)->count();

    $data = array(
       'administradores' => $administradores,
       'empleados' =>  $empleados,
       'proveedores' =>  $proveedores,
       'propietarios' =>  $propietarios
    );
  
    return $data;
   
    }

    public function obtenerProveedores()
    {  
  
    return Usuario::where('role_id', 3)->get();
   
    }





}
