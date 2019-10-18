<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Core\Authorization;

use App\Models\Cuenta;


$app->get('/cuenta/buscar', function (Request $request, Response $response, $args) {
    $salt = "Caballo salvaje";
    $hash = hash("sha256", "Victor $salt", false);
    $response->getBody()->write($hash);
    return $response;
});

$app->post('/cuenta/registrar', function (Request $request, Response $response, $args) {
    $cuenta = new Cuenta();
    $data = $request->getBody()->getContents();
    $cuenta->bindJSON($data);

    if ($cuenta->bindJSON($data)) {
        var_export($cuenta);
        // if ($cuenta->registrar()) {
        //     $response = $response->withStatus(201);
        // } else {
        //     $response = $response->withStatus(417);
        // }
    }
    return $response;
});

$app->post('/usuario/login', function (Request $request, Response $response, $args) {
    $data = \json_decode($request->getBody()->getContents(), true);
    $usuario = new Usuario();

    $usuario->usuario = $data["usuario"] ?? "";
    $usuario->password = $data["password"] ?? "";
    $content = $usuario->login();

    if (count($content) != 0) {
        $content = $content[0];
        $content['token'] = Authorization::GenerarToken([
            'id' => $content['idContacto'],
            'usuario' => $content['usuario']
        ]);
        $response = $response->withHeader("Content-Type", "application/json");
    } else {
        $response = $response->withStatus(401);
    }

    $response->getBody()->write(\json_encode($content));
    return $response;
});

$app->post("/usuario/verificar", function (Request $request, Response $response, $args) {
    // $data = $request->getBody()->getContents();
    // $data = \json_decode($data, true);
    $token = $request->getHeader("X-API-Token")[0];

    $status = Authorization::validarToken(['token' => $token]);

    if ($status) {
        $response->getBody()->write("OK");
    } else {
        $response = $response->withStatus(401);
    }
    // var_dump($request->getHeaders());

    return $response;
});
