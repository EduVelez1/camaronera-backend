<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CamaroneraController;
use App\Http\Controllers\PiscinaController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\GramajeController;
use App\Http\Controllers\BiomasaController;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\LarvaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/recuperar-contrasena', [AuthController::class, 'recuperarPass']);
Route::post('/insertar-codigo', [AuthController::class, 'IngresarCodigo']);
Route::post('/cambiar-contrasena', [AuthController::class, 'cambiarContrasena']);



// usuarios
Route::post('/usuario', [UsuarioController::class, 'registrarUsuarios']);
Route::get('/usuario/{idRole}', [UsuarioController::class, 'ObtenerUsuarioPorTipoRole']);
Route::get('/usuario/id/{id}', [UsuarioController::class, 'ObtenerUsuarioPorId']);
Route::put('/usuario/{id}', [UsuarioController::class, 'EditarUsuario']);
Route::put('/usuario/contrasena/{id}', [UsuarioController::class, 'CambiarContrasena']);
Route::delete('/usuario/{id}', [UsuarioController::class, 'HabilitarDeshabilitarUsuario']);
Route::get('/cantidad-usuario', [UsuarioController::class, 'totalUsuarios']);
Route::get('/proveedores', [UsuarioController::class, 'obtenerProveedores']);



// camaroneras

Route::post('/camaronera', [CamaroneraController::class, 'registrarCamaronera']);
Route::get('/camaronera', [CamaroneraController::class, 'ObtenerCamaroneras']);
Route::get('/camaronera/{id}', [CamaroneraController::class, 'ObtenerCamaroneraPorId']);
Route::delete('/camaronera/{id}', [CamaroneraController::class, 'HabilitarDeshabilitarCamaronera']);
Route::put('/camaronera/{id}', [CamaroneraController::class, 'EditarCamaronera']);


// piscina

Route::post('/piscina', [PiscinaController::class, 'registrarPiscina']);
Route::get('/piscina', [PiscinaController::class, 'ObtenerPiscinas']);
Route::get('/piscina/{id}', [PiscinaController::class, 'ObtenerPiscinaPorId']);
Route::delete('/piscina/{id}', [PiscinaController::class, 'HabilitarDeshabilitarPiscina']);
Route::put('/piscina/{id}', [PiscinaController::class, 'EditarPiscina']);
Route::get('/piscina-activas', [PiscinaController::class, 'ObtenerPiscinasActivas']);


// produccion
Route::post('/produccion', [ProduccionController::class, 'registrarProduccion']);
Route::get('/produccion', [ProduccionController::class, 'ObtenerProduccionesActivas']);
Route::get('/produccion-historial', [ProduccionController::class, 'ObtenerProduccionesInactivas']);

Route::get('/produccion/{id}', [ProduccionController::class, 'ObtenerProduccionPorId']);
Route::delete('/produccion/{id}', [ProduccionController::class, 'EliminarProduccion']);
Route::put('/produccion/{id}', [ProduccionController::class, 'CerrarProduccion']);
Route::get('/produccion/calendario/{id}', [ProduccionController::class, 'ObtenerDatosCalendario']);
Route::get('/produccion/reporte/{idProduccion}', [ProduccionController::class, 'reporteProduccion']);
Route::get('/produccion/activa/{idProduccion}', [ProduccionController::class, 'produccionActiva']);
Route::get('/produccion/costos/{id}', [ProduccionController::class, 'costosProduccion']);



// gramaje
Route::post('/gramaje', [GramajeController::class, 'registrarGramaje']);
Route::get('/gramaje/produccion/{id}', [GramajeController::class, 'obtenerGramajePorProduccion']);
Route::get('/gramaje/detalle/{idGramaje}', [GramajeController::class, 'obtenerGramajePorProduccionDetalle']);
Route::put('/gramaje/{idGramaje}', [GramajeController::class, 'editarGramaje']);
Route::delete('/gramaje/{id}', [GramajeController::class, 'EliminarGramaje']);



// biomasa
Route::post('/biomasa', [BiomasaController::class, 'registrarBiomasa']);
Route::get('/biomasa/produccion/{id}', [BiomasaController::class, 'obtenerBiomasaPorProduccion']);
Route::get('/biomasa/detalle/{idBiomasa}', [BiomasaController::class, 'obtenerBiomasaPorProduccionDetalle']);
Route::put('/biomasa/{idBiomasa}', [BiomasaController::class, 'editarBiomasa']);
Route::delete('/biomasa/{id}', [BiomasaController::class, 'EliminarBiomasa']);



// alimento
Route::post('/alimento', [AlimentoController::class, 'registrarAlimento']);
Route::get('/alimento/produccion/{id}', [AlimentoController::class, 'obtenerAlimentoPorProduccion']);
Route::get('/alimento/{id}', [AlimentoController::class, 'obtenerAlimento']);
Route::put('/alimento/{id}', [AlimentoController::class, 'editarAlimento']);
Route::delete('/alimento/{id}', [AlimentoController::class, 'EliminarAlimento']);




//larva
Route::post('/larva', [LarvaController::class, 'registrarLarva']);
Route::get('/larva', [LarvaController::class, 'obtenerLarvas']);
Route::get('/larva/{id}', [LarvaController::class, 'ObtenerLarvaPorId']);
Route::put('/larva/{id}', [LarvaController::class, 'EditarLarva']);
Route::delete('/larva/{id}', [LarvaController::class, 'EliminarLarva']);

