<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require '../src/Rest/Model/ModelUsuario.php';


// $app->get('/usuario', function (Request $request, Response $response, array $args ) {
//     global $dbIfx;
//     $ModelUsuario = new ModelUsuario($dbIfx);
//     $resp = $ModelUsuario->getUsers();
//     if ($resp['Error']) {
//         $response = showResponse(400, $resp, $response);
//     } else {
//         $response = showResponse(200, $resp, $response);
//     }
//     return $response;
// });

$app->get('/usuario', function (Request $request, Response $response, array $args ) {
    global $db;
    $ModelUsuario = new ModelUsuario($db);
    $getUsuarios  = $ModelUsuario->getUsuarios();
    $response     = showResponse(200, $getUsuarios['data'], $response);
    return $response;
});

$app->post('/usuario', function (Request $request, Response $response, array $args ) {
    global $db;
    $params       = $request->getParsedBody();
    $Usuario      = new Usuario();
    $Usuario->setNombre($params['nombre']);
    $Usuario->setUsuario($params['usuario']);
    $Usuario->setContrasena(sha1(123456));
    $Usuario->setStatus($params['status']);
    $Usuario->setFechaAlta(date('Y-m-d H:i:s'));
    $ModelUsuario = new ModelUsuario($db);
    $saveUser     = $ModelUsuario->saveUser($Usuario);
    $response     = showResponse(200, $saveUser, $response);
    return $response;
});

$app->post('/usuario/edit', function (Request $request, Response $response, array $args ) {
    global $db;
    $params       = $request->getParsedBody();
    $Usuario      = new Usuario();
    $Usuario->setNombre($params['nombre']);
    $Usuario->setUsuario($params['usuario']);
    $Usuario->setStatus($params['status']);
    $Usuario->setIdUsuario($params['id_usuario']);
    $ModelUsuario = new ModelUsuario($db);
    $editUser     = $ModelUsuario->editUser($Usuario);
    $response     = showResponse(200, $editUser, $response);
    return $response;
});


$app->post('/login', function (Request $request, Response $response, array $args ) {
    global $db;
    $params 	  = $request->getParsedBody(); 
    $usuario 	  = $params['usuario'];
	$contrasena	  = sha1($params['contrasena']);
    $ModelUsuario = new ModelUsuario($db);
    $login  	  = $ModelUsuario->login($usuario,$contrasena);
    $response     = showResponse(200, $login, $response);
    return $response;
});


$app->post('/usuario/resetearPassword', function (Request $request, Response $response, array $args ) {
    global $db;
    $params       = $request->getParsedBody(); 
    $id_usuario   = $params['id_usuario'];
    $password     = sha1(123456);
    $ModelUsuario = new ModelUsuario($db);
    $resetPsw     = $ModelUsuario->resetPsw($id_usuario,$password);
    $response     = showResponse(200, $resetPsw, $response);
    return $response;
});


$app->post('/usuario/cambiarPassword', function (Request $request, Response $response, array $args ) {
    global $db;
    $params       = $request->getParsedBody(); 
    $id_usuario   = $params['id_usuario'];
    $password     = sha1($params['password']);
    $ModelUsuario = new ModelUsuario($db);
    $cambiarPsw   = $ModelUsuario->cambiarPsw($id_usuario,$password);
    $response     = showResponse(200, $cambiarPsw, $response);
    return $response;
});


?>
