<?php

use App\Http\Controllers\CategoriasController;
use Illuminate\Foundation\Application;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DescuentosController;
use App\Http\Controllers\CarritosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\DetalleVentasController;
use App\Http\Controllers\CuponesController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\EnviosController;
use App\Http\Controllers\DetalleCarritoController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductosDetallesController;
use App\Http\Controllers\EncargadoVentas;
use App\Http\Controllers\UsuarioexternoController;
use App\Http\Controllers\Edit2Controller;
use App\Http\Controllers\Edit3Controller;


Route::get('/', [DashboardController::class,'index'])->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'dashboard'])->name('dashboard');
    Route::resource('/Productos',ProductosController::class);
    Route::resource('/Descuentos',DescuentosController::class);
    Route::resource('/Carrito',CarritosController::class);
    Route::resource('/Ventas',VentasController::class);
    Route::resource('/Administrador',AdministradorController::class);
    Route::resource('/Cupones', CuponesController::class);
    Route::resource('/Compras', ComprasController::class);
    Route::resource('/Envios', EnviosController::class);
    Route::resource('/DetalleCompra', DetalleVentasController::class);
    Route::resource('/Ventaslocal', EncargadoVentas::class);
});

Route::get('auth/google', function () {
    return Socialite::driver('google')->redirect();
});
 
Route::get('auth/google/callback', function () {

    $user_google = Socialite::driver('google')->user();

    $user = User::updateOrCreate([
        'google_id' => $user_google->id,
    ], [
        'name' => $user_google->name,
        'email' => $user_google->email,
        'google_id' => $user_google->id,
    ]);
    Auth::login($user);

    return redirect('/dashboard');
});

//rutas personalizadas
Route::get('/comprar/{producto}', [ProductosController::class, 'comprar'])->name('productos.comprar');

Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);

Route::post('/ventas', [VentasController::class, 'store'])->name('ventas.store');

Route::post('/detalle-ventas', [DetalleVentasController::class, 'store']);

Route::post('/actualizar-stockuser', [ProductosDetallesController::class, 'actualizarStock']);

Route::post('/carrito/agregar', [CarritosController::class, 'agregarAlCarrito'])->name('carrito.agregar');

Route::post('/detalle-carrito/store', [DetalleCarritoController::class, 'store'])->name('detalleCarrito.store');

Route::get('/carritos/comprar/{producto}', [CarritosController::class, 'comprar'])->name('carritos.comprar');

//Route::get('/carritos/comprar/{producto}', [CarritosController::class, 'comprar'])->name('carritos.comprar');
Route::match(['get', 'post'], '/carritos/comprar/{producto}', [CarritosController::class, 'comprar'])->name('carritos.comprar');

Route::delete('/carritos/{detalleCarritoId}', [CarritosController::class, 'destroy'])->name('carritos.destroy');



Route::post('/carritos/comprar/{detalleCarritoId}', [CarritosController::class, 'comprarCarrito'])->name('carritos.comprar_carrito');

Route::get('/usuarios/buscar/{documento_identidad}', [EncargadoVentas::class, 'buscarUsuarioPorDocumento']);

Route::post('/venta-anonima', [EncargadoVentas::class, 'ventaAnonima']);

Route::post('/ventas-anonimo-detalle', [EncargadoVentas::class, 'ventaAnonimaDetalle']);

Route::post('/usuariosexternos', [UsuarioexternoController::class, 'store']);

Route::post('/actualizar-stock', [EncargadoVentas::class, 'actualizarStock']);

Route::post('/guardar-venta', [EncargadoVentas::class, 'guardarVenta']);

Route::post('/guardar-detalle-venta', [EncargadoVentas::class, 'guardarDetalleVenta']);

Route::post('/guardar-venta-stock', [EncargadoVentas::class, 'guardar_venta_stock']);

Route::post('/agregar-detalle-producto', [ProductosController::class, 'agregar_detalle_producto'])->name('agregar_detalle_producto');

Route::post('/insertar_color', [ProductosController::class, 'insertar_color'])->name('insertar_color');

Route::post('/productos-detalles/update/{id}', [ProductosController::class, 'actualizar_stock_producto'])->name('productosdetallesstock');

Route::get('/datos-generales', [AdministradorController::class, 'obtenerDatosGenerales']);

Route::get('/admin/edit2', [AdministradorController::class, 'edit2'])->name('admin.edit2');

Route::get('/admin/edit3', [AdministradorController::class, 'edit3'])->name('admin.edit3');

Route::get('/datosedit2', [AdministradorController::class, 'obtenerDatosedit2']);

//admin rutas edit2

Route::post('/admin/colores', [Edit2Controller::class, 'addColor']);
Route::put('/admin/colores/{id}', [Edit2Controller::class, 'editColor']);
Route::delete('/admin/colores/{id}', [Edit2Controller::class, 'deleteColor']);

Route::post('/admin/tallas', [Edit2Controller::class, 'addtallas']);
Route::put('/admin/tallas/{id}', [Edit2Controller::class, 'edittallas']);
Route::delete('/admin/tallas/{id}', [Edit2Controller::class, 'deletetallas']);

Route::post('/admin/envios', [Edit2Controller::class, 'addEnvios']);
Route::put('/admin/envios/{id}', [Edit2Controller::class, 'editEnvios']);
Route::delete('/admin/envios/{id}', [Edit2Controller::class, 'deleteEnvios']);

Route::post('/admin/sucursales', [Edit2Controller::class, 'addSucursales']);
Route::put('/admin/sucursales/{id}', [Edit2Controller::class, 'editSucursales']);
Route::delete('/admin/sucursales/{id}', [Edit2Controller::class, 'deleteSucursales']);

//cupones 

Route::post('/cupones', [Edit3Controller::class, 'store']);
Route::put('/cupones/{id}', [Edit3Controller::class, 'update']);
Route::delete('/cupones/{id}', [Edit3Controller::class, 'destroy']);

Route::post('/proveedores', [Edit3Controller::class, 'storeProveedor']);
Route::put('/proveedores/{id}', [Edit3Controller::class, 'updateProveedor']);
Route::delete('/proveedores/{id}', [Edit3Controller::class, 'destroyProveedor']);

Route::post('/descuentos', [Edit3Controller::class, 'storeDescuento']);
Route::put('/descuentos/{id}', [Edit3Controller::class, 'updateDescuento']);
Route::delete('/descuentos/{id}', [Edit3Controller::class, 'destroyDescuento']);