<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Usuario;
use App\Mail\UsuarioRecuperarPass;
use App\Mail\UsuarioCambioPass;

use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    
    private function getIdentity($request)
    {

        //datos de usuario
        $jwtAuth = new \JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }

    //
    public function login(Request $request)
    {

        //$jwtAuth = new \App\Helpers\JwtAuth();
        $jwtAuth =  new \JwtAuth();

        //recibir datos por post

        $json = $request->input('json', null);
        
        if (!$json) {
         

            $signup = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Ingrese correo y contrasena',
            );

    } else {
        $params = json_decode($json);
        $params_array = json_decode($json, true); // array


       

        //validar datos

        if (!empty($params) && !empty($params_array)) {


            $validate = \Validator::make($params_array, [

                'correo'      =>  'required|email|',
                'contrasena'   =>  'required'
            ]);
        } 

        if ($validate->fails()) {

            $signup = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'El usuario no se ha podido loguear',
                'errors'    =>  $validate->errors()
            );
        } else {

            //cifrar contraseña
           $pwd = hash('sha256', $params->contrasena);

            //devolver token o datos
            $signup =  $jwtAuth->signup($params->correo, $pwd);


            // verificar estado de cuenta de usuario (activa o inactiva)



            if (!empty($params->gettoken)) {

               // $signup =  $jwtAuth->signup($params->correo, $pwd, true);
                $signup =  $jwtAuth->signup($params->correo, $pwd, true);

            }
        }
    }


        return response()->json($signup, 200);
    }




      //recuperar contraseña
      public function recuperarPass(Request $request)
      {
  
          //recoger datos por post
          $json = $request->input('json', null);
          $params = json_decode($json);
          $params_array = json_decode($json, true);
  

              $usuario = Usuario::where('correo', $params_array['correo'])->count();
  
              if ($usuario == '1') {
  
                  $usuario = Usuario::where('correo', $params_array['correo'])->first();
  
                  $code = rand(100000, 999999);
  
                  $usuario->codigo = $code;
  
                  $usuario->save();
  
                  $data = array(
                    'icono' => 'success',
                    'title' => 'Codigo Enviado',
                    'mensaje' => 'Se ha enviado un codigo de verificación a su correo',
            
                ); 
                  $dataTemp = array(                  
                      'usuario'   =>  $usuario,
                      'codigoAletario'   => $code  
                  );

                  Mail::to($usuario->correo)->send(new UsuarioRecuperarPass($dataTemp));
              } else {
  
                  $data = array(
                      'icono'    =>  'error',
                      'title' => 'Usuario encontrado',
                      'mensaje'   =>  'El usuario no ha sido encontrado'
  
                  );
              }
          
  
          return response()->json($data, 200);
      }
  
  
      public function IngresarCodigo(Request $request)
      {
  
          //recoger datos por post
          $json = $request->input('json', null);
          $params = json_decode($json);
          $params_array = json_decode($json, true);
  
        
  
              $usuario = Usuario::where('correo', $params_array['correo'])->count();
  
              if ($usuario == '1') {
  
                  $usuario = Usuario::where('correo', $params_array['correo'])->first();
  
  
  
  
                  if ($usuario->codigo == $params_array['codigo']) {
  
  
  
                      $longitud = 8; // longitud del password  
                      $pass = substr(md5(rand()), 0, $longitud);
  
                      $pwd = hash('sha256', $pass);
  
                      $usuario->contrasena = $pwd;
                      $usuario->codigo = '';
  
  
  
                      $usuario->save();
  
                   

                      $data = array(
                        'icono' => 'success',
                        'title' => 'Código correcto',
                        'mensaje' => 'Contraseña temporal enviada al correo',
                
                    ); 
                      $dataTemp = array(                  
                        'usuario'   =>  $usuario,
                        'pass' => $pass 
                      );
                      Mail::to($usuario->correo)->send(new UsuarioCambioPass($dataTemp));
                  } else {  
               
                      
                  $data = array(
                    'icono' => 'error',
                    'title' => 'Código incorrecto',
                    'mensaje' => 'El código no coincide con el enviado',
            
                ); 
                  }
              } else {  
             

                  $data = array(
                    'icono' => 'error',
                    'title' => 'Usuario no encontrado',
                    'mensaje' => 'El usuario no esta registrado en el sistema',
            
                ); 
              }
          
  
          return response()->json($data, 200);
      }


      public function cambiarContrasena(Request $request )
      {
  
        $user = $this->getIdentity($request);
          $usuario = Usuario::find($user->sub);
  
  
              $json = $request->input('json', null);
              $params = json_decode($json); //objeto

              
              $pwd = hash('sha256', $params->contrasena);
              
              $usuario->contrasena = $pwd;
              $usuario->save(); 
              $data = array(
                  'icono' => 'success',
                  'title' => 'Registro Correcto',
                  'mensaje' => 'Contraseña cambiada',
          
              ); 
                return response()->json($data, 200);
       
       
          
      }
}
