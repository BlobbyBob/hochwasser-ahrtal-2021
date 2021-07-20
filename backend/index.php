<?php

use BlobbyBob\HochwasserAhrtal2021\Media;
use BlobbyBob\HochwasserAhrtal2021\Town;
use Slim\Factory\AppFactory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require 'vendor/autoload.php';
require __DIR__ . '/config.php';

$GLOBALS['pdo'] = new PDO(sprintf("mysql:dbname=%s;host=%s", DB_NAME, DB_HOST), DB_USER, DB_PASS);
function getPDO(): PDO
{
    return $GLOBALS['pdo'];
}

$app = AppFactory::create();

$app->addErrorMiddleware(false, true, true);

// todo get/post on / returns index.html

$app->get('/api/towns', function (Request $request, Response $response) {
    $stmt = getPDO()->prepare('SELECT * FROM towns');
    $stmt->execute();

    $towns = [];
    /** @var Town $town */
    while (($town = $stmt->fetchObject(Town::class))) {
        $towns[] = $town;
    }

    $response->getBody()->write(json_encode($towns));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/town/{id}', function (Request $request, Response $response, array $args) {
    $stmt = getPDO()->prepare('SELECT * FROM towns WHERE id = ?');
    $stmt->execute([$args['id']]);

    /** @var Town $town */
    $town = $stmt->fetchObject(Town::class);

    if (!$town) {
        return $response->withStatus(404, 'Not Found');
    }

    $response->getBody()->write(json_encode($town));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/media/{town}', function (Request $request, Response $response, array $args) {
    $stmt = getPDO()->prepare('SELECT * FROM towns WHERE id = ?');
    $stmt->execute([$args['town']]);
    /** @var Town $town */
    $town = $stmt->fetchObject(Town::class);
    if (!$town) {
        return $response->withStatus(404, 'Not Found');
    }

    $stmt = getPDO()->prepare('SELECT * FROM media WHERE town = ?');
    $stmt->execute([$town->id]);

    $medias = [];
    /** @var Media $media */
    while (($media = $stmt->fetchObject(Media::class))) {
        $medias[] = $media;
    }

    $response->getBody()->write(json_encode($medias));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
