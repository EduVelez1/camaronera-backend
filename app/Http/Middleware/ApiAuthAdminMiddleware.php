<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

       

        if ($checkToken) {

             // sacar identidad del usuario
             $user = $jwtAuth->checkToken($token, true);

             if ($user->role == 'Administrador') {
            
                return $next($request);           

             } else {
                $data = array(
                    'status'    =>  'error',
                    'code'      =>  401,
                    'mesaage'   =>  'No tiene permisos para realizar esta peticion'
    
                );

             }
        } else {          

            $data = array(
                'status'    =>  'error',
                'code'      =>  400,
                'mesaage'   =>  'El usuario no se ha identificado correctamente'

            );
        
        }
            return response()->json($data, $data['code']);
        
    }
}