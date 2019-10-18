<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Contacto;

$app->post('/contacto/registrar', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $data = $request->getBody()->getContents();

    if ($contacto->bindJSON($data)) {
        if ($contacto->registrar()) {
            $response = $response->withStatus(201);
        } else {
            $response = $response->withStatus(417);
        }
    }
    return $response;
});

$app->put('/contacto/actualizar/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args['id'] ?? 0;
    $data = $request->getBody()->getContents();

    if ($contacto->bindJSON($data)) {
        if ($contacto->actualizar()) {
            $response = $response->withStatus(201);
        } else {
            $response = $response->withStatus(417);
        }
    }
    return $response;
});

$app->delete('/contacto/eliminar/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args['id'] ?? 0;

    if ($contacto->eliminar()) {
        $response = $response->withStatus(200);
    } else {
        $response = $response->withStatus(204);
    }
    return $response;
});

$app->post('/contacto/agregar-meta/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args['id'] ?? 0;
    $data = \json_decode($request->getBody()->getContents(), true);
    if ($data != null) {
        if ($contacto->agregarMeta($data['llave'], $data['valor'])) {
            $response = $response->withStatus(200);
        } else {
            $response = $response->withStatus(204);
        }
    } else {
        $response = $response->withStatus(500);
    }
    return $response;
});

$app->put('/contacto/actualizar-meta/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args['id'] ?? 0;
    $data = \json_decode($request->getBody()->getContents(), true);
    if ($data != null) {
        if ($contacto->actualizarMeta($data['llave'], $data['valor'])) {
            $response = $response->withStatus(200);
        } else {
            $response = $response->withStatus(204);
        }
    } else {
        $response = $response->withStatus(500);
    }
    return $response;
});


$app->delete('/contacto/eliminar-meta/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args['id'] ?? 0;
    $data = \json_decode($request->getBody()->getContents(), true);
    if ($data != null) {
        if ($contacto->eliminarMeta($data['llave'])) {
            $response = $response->withStatus(200);
        } else {
            $response = $response->withStatus(204);
        }
    } else {
        $response = $response->withStatus(500);
    }
    return $response;
});

$app->get('/contacto/buscar', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $busqueda = $request->getQueryParams()["busqueda"] ?? "";
    $response = $response->withHeader('Content-type', 'application/json');
    $response->getBody()->write(\json_encode($contacto->buscar($busqueda)));
    return $response;
});

$app->get('/contacto/detalle/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args["id"];
    $content = $contacto->detalle();

    if (count($content) <= 0) {
        $response = $response->withStatus(204);
    }
    $response->getBody()->write(\json_encode($content[0]));

    $response = $response->withHeader('Content-type', 'application/json');
    return $response;
});

$app->get('/contacto/meta-data/{id}', function (Request $request, Response $response, $args) {
    $contacto = new Contacto();
    $contacto->idContacto = $args["id"];
    $content = $contacto->metaData();

    if (count($content) <= 0) {
        $response = $response->withStatus(204);
    }
    $response->getBody()->write(\json_encode($content));
    $response = $response->withHeader('Content-type', 'application/json');
    return $response;
});
