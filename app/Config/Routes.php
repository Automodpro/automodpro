<?php
use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes
 */

// Auth routes (no filter)
$routes->get('/', 'Home::index');
$routes->get('/auth/register', 'Auth::register');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/doLogin', 'Auth::doLogin');
$routes->post('/auth/doRegister', 'Auth::doRegister');
$routes->post('/auth/google', 'Auth::googleLogin');
$routes->get('/auth/verify/(:any)', 'Auth::verifyEmail/$1');
$routes->get('/logout', 'Auth::logout');
$routes->post('/logout', 'Auth::logout');

// Protected routes
    $routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'DashboardPorRol::index');

    $routes->get('/dashboard/(:any)', 'DashboardPorRol::index');


    // Vehiculos
    $routes->get('/vehiculos', 'Vehiculos::index');
    $routes->get('/vehiculos/crear', 'Vehiculos::crear');
    $routes->post('/vehiculos/guardar', 'Vehiculos::guardar');
    $routes->get('/vehiculos/editar/(:num)', 'Vehiculos::editar/$1');
    $routes->post('/vehiculos/actualizar/(:num)', 'Vehiculos::actualizar/$1');
    $routes->get('/vehiculos/eliminar/(:num)', 'Vehiculos::eliminar/$1');
    $routes->get('/vehiculos/tipos', 'Vehiculos::tipos');

    // Servicios
    $routes->get('/servicios', 'Servicios::index');
    $routes->get('/servicios/crear', 'Servicios::crear');
    $routes->post('/servicios/guardar', 'Servicios::guardar');
    $routes->get('/servicios/editar/(:num)', 'Servicios::editar/$1');
    $routes->post('/servicios/actualizar/(:num)', 'Servicios::actualizar/$1');
    $routes->get('/servicios/eliminar/(:num)', 'Servicios::eliminar/$1');
    $routes->get('/servicios/costos', 'Servicios::costos');
    $routes->get('/servicios/precios', 'Servicios::precios');

    // Pedidos
    $routes->get('/pedidos', 'Pedidos::index');
    $routes->get('/pedidos/crear', 'Pedidos::crear');
    $routes->post('/pedidos/guardar', 'Pedidos::guardar');
    $routes->get('/pedidos/ver/(:num)', 'Pedidos::ver/$1');
    $routes->get('/pedidos/eliminar/(:num)', 'Pedidos::eliminar/$1');

    // Editar / actualizar (admin)
    $routes->get('/pedidos/editar/(:num)', 'Pedidos::editar/$1');
    $routes->post('/pedidos/actualizar/(:num)', 'Pedidos::actualizar/$1');

    // Pagos
    $routes->get('/pagos', 'Pagos::index');
    $routes->get('/pagos/crear', 'Pagos::crear');
    $routes->post('/pagos/guardar', 'Pagos::guardar');
    $routes->get('/pagos/eliminar/(:num)', 'Pagos::eliminar/$1');

// Usuarios
    $routes->get('/usuarios', 'Usuarios::index');


    $routes->get('/usuarios/crear', 'Usuarios::crear');
    $routes->post('/usuarios/guardar', 'Usuarios::guardar');
    $routes->get('/usuarios/editar/(:num)', 'Usuarios::editar/$1');
    $routes->post('/usuarios/actualizar/(:num)', 'Usuarios::actualizar/$1');
    $routes->get('/usuarios/eliminar/(:num)', 'Usuarios::eliminar/$1');

    // Configuracion
    $routes->get('/configuracion', 'Configuracion::index');
    $routes->post('/configuracion/verify', 'Configuracion::verify');
    $routes->post('/configuracion/update', 'Configuracion::update');
    $routes->get('/configuracion/lock', 'Configuracion::lock');

    // Factores de precio
    $routes->get('/factores-precio', 'FactoresPrecio::index');
    $routes->get('/factores-precio/crear', 'FactoresPrecio::crear');
    $routes->post('/factores-precio/guardar', 'FactoresPrecio::guardar');
    $routes->get('/factores-precio/editar/(:num)', 'FactoresPrecio::editar/$1');
    $routes->post('/factores-precio/actualizar/(:num)', 'FactoresPrecio::actualizar/$1');
    $routes->get('/factores-precio/eliminar/(:num)', 'FactoresPrecio::eliminar/$1');
    $routes->get('/factores-precio/precios-json', 'FactoresPrecio::preciosJson');

    // Personalizador
    $routes->get('/personalizador', 'Personalizador::index');
    $routes->post('/personalizador/guardar', 'Personalizador::guardar');

    // Módulo de Reportes PDF
    $routes->group('reportes', function($routes) {
        $routes->get('usuariosGeneral', 'Reportes::usuariosGeneral');
        $routes->get('vehiculosGeneral', 'Reportes::vehiculosGeneral');
        $routes->get('vehiculoDetalle/(:num)', 'Reportes::vehiculoDetalle/$1');
        $routes->get('pedidosGeneral', 'Reportes::pedidosGeneral');
        $routes->get('pedidoDetalle/(:num)', 'Reportes::pedidoDetalle/$1');
        $routes->get('serviciosGeneral', 'Reportes::serviciosGeneral');
        $routes->get('servicioDetalle/(:num)', 'Reportes::servicioDetalle/$1');

        // NUEVO: PDFs de Pagos, Factores y Dashboard
        $routes->get('pagosGeneral', 'Reportes::pagosGeneral');
        $routes->get('pagoDetalle/(:num)', 'Reportes::pagoDetalle/$1');
        $routes->get('factoresPrecioGeneral', 'Reportes::factoresPrecioGeneral');
        $routes->get('factoresTipo', 'Reportes::factoresTipo');
        $routes->get('factoresMarca', 'Reportes::factoresMarca');
        $routes->get('factoresAntiguedad', 'Reportes::factoresAntiguedad');
        $routes->get('usuarioDetalle/(:num)', 'Reportes::usuarioDetalle/$1');
        $routes->get('dashboardGeneral', 'Reportes::dashboardGeneral');
        $routes->get('miReporte', 'Reportes::miReporte');
    });


    });


// ====== API ROUTES ======
// Social Auth (público)
$routes->group('api/v1/auth', function ($routes) {
    $routes->post('google', 'Api\SocialAuthApi::google');
    $routes->post('facebook', 'Api\SocialAuthApi::facebook');
    $routes->post('github', 'Api\SocialAuthApi::github');
});

// CRUD Usuarios (protegido con JWT)
$routes->group('api/v1', ['filter' => 'jwt'], function ($routes) {
    $routes->get('usuarios', 'Api\UsuarioApi::index');
    $routes->get('usuarios/(:num)', 'Api\UsuarioApi::show/$1');
    $routes->post('usuarios', 'Api\UsuarioApi::create');
    $routes->put('usuarios/(:num)', 'Api\UsuarioApi::update/$1');
    $routes->delete('usuarios/(:num)', 'Api\UsuarioApi::delete/$1');
});
