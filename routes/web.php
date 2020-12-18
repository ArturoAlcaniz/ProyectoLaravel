<?php


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

Route::view('/', 'inicio');

Route::view('/home', 'home');

Route::view('/inicio', 'inicio');

Route::view('/home/perfil', 'perfil');

Route::view('/home/tienda', 'tienda');

Route::view('/home/negocios', 'negocios');

Route::view('/home/trabajo', 'trabajo');

Route::view('/home/ranking', 'ranking');

Route::view('/home/chat', 'chat');

Route::view('/home/logout', 'logout');

Route::post('/login','App\Http\Controllers\ControlInicio@login')->name('login');

Route::post('/registro','App\Http\Controllers\ControlInicio@registro')->name('registro');

Route::post('/cambiodatos', 'App\Http\Controllers\validacionPerfil@cambiardatos')->name('cambiardatos');

Route::post('/crearnegocio', 'App\Http\Controllers\validacionNegocios@crearNegocio')->name('crearNegocios');

Route::post('/trabajar', 'App\Http\Controllers\validacionTrabajo@trabajar')->name('trabajar');

Route::post('/enviarmensaje', 'App\Http\Controllers\validacionChat@enviarMensaje')->name('enviarMensaje');

Route::get('/obtenerMensajesChat', 'App\Http\Controllers\ControlChat@obtenerMensajes')->name('ObtenerMensajesChat');

Route::get('/actualizarTrabajosDatos', 'App\Http\Controllers\gestorTrabajo@actualizarExpTrabajo')->name('actualizarExpTrabajos');

Route::get('/actualizarTrabajosGanancias', 'App\Http\Controllers\gestorTrabajo@actualizarGananciaTrabajo')->name('actualizarGananciaTrabajos');

Route::get('/actualizarTrabajosCoste', 'App\Http\Controllers\gestorTrabajo@actualizarCosteTrabajo')->name('actualizarCosteTrabajo');

Route::get('/cookiesAccepted', function (){
    session()->put('tokenCookie', '1');
});

Route::get('/mantenimientoYA', function(){
Artisan::call('down', ['--allow' => '85.251.217.7', '--allow' => '127.0.0.1']);
});

Route::get('/mantenimientoNUNCA', function(){
Artisan::call('up');
});
