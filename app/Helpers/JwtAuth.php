<?php

namespace App\Helpers;

//require_once "vendor/autoload.php";

use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Firebase\JWT\JWT;

class JwtAuth
{

    public $key;

    public function __construct(){
        
        //$this->key = 'clave_token_votaciones_2020_uleam';
        $this->key = env('KEY_JWT');

    }


    public function signup($correo, $contrasena,$getToken=null)
    {

        // buscar si existe el usuario
        $user = Usuario::where([
            'correo' => $correo,
            'contrasena' => $contrasena
        ])->first();

        if (!$user) {
            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Credencial o usuario incorrecto',
            );        
        } else {

       

       


         // verificar estado de cuenta de usuario (activa o inactiva)

         if ($user->estado->tipo != 'Activo') {

            $data = array(
                'status'    =>  'error',
                'code'      =>  404,
                'message'   =>  'Esta cuenta ha sido suspendida',
            );

            return $data;
       
        }
        //comprobar si son correctas
        $signup = false;

        if (is_object($user)) {
            $signup = true;
        }

        //generar token
        if ($signup) {

            
          
            $token = array(
                'sub'        =>      $user->id,
               // 'cedula'       =>      $user->cedula,
                'correo'      =>      $user->correo,
                'nombre'       =>      $user->nombres,
               // 'estado'   =>      $user->estado->tipo,
                'role'      =>      $user->role->tipo,
                'expirado'        =>  date("Y-m-d",time() + (7 * 24 * 60 * 60)), 
                'iat'        =>      time(),
                'exp'        =>      time() + (7 * 24 * 60 * 60)
 

            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);

            if (is_null($getToken)) {
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'login incorrecto'
            );
        }
    }
        //devolver datos decodificados o token


        return $data;
    }


//comprobar si el token es correcto 
public function checkToken($jwt, $getIdentity = false){

    $auth = false;

    try {
        $jwt = str_replace('"', '', $jwt);
        $decoded = JWT::decode($jwt, $this->key, ['HS256']);
    } catch (\UnexpectedValueException $e) {    
        $auth = false;
    } catch (\DomainException $e){
        $auth = false;
    }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){

            $auth = true;

        }else {

            $auth = false;

        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;


}






}
