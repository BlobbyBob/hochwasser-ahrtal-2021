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

getPDO()->exec('SET CHARACTER SET UTF8');

$app = AppFactory::create();

// todo this CORS workaround is only for development. Remove in production
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin')
        ->withHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
});

$app->addErrorMiddleware(false, true, true);

// todo get/post on / returns index.html

$app->get('/api/towns', function (Request $request, Response $response) {
    $stmt = getPDO()->prepare('SELECT * FROM towns');
    $stmt->execute();

    $towns = [];
    /** @var Town $town */
    while (($town = $stmt->fetchObject(Town::class))) {
        $town->id = (int) $town->id;
        $town->name = (string) $town->name;
        $town->route = (string) $town->route;
        $town->x = (float) $town->x;
        $town->y = (float) $town->y;
        $town->label = (string) $town->label;
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
    $town->id = (int) $town->id;
    $town->name = (string) $town->name;
    $town->route = (string) $town->route;
    $town->x = (float) $town->x;
    $town->y = (float) $town->y;
    $town->label = (string) $town->label;

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
        $media->id = (int) $media->id;
        $media->town = (int) $media->town;
        $media->title = (string) $media->title;
        $media->timestamp = (string) $media->timestamp;
        $media->latitude = (float) $media->latitude;
        $media->longitude = (float) $media->longitude;
        $media->data = (string) $media->data;
        $medias[] = $media;
    }

    $response->getBody()->write(json_encode($medias));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
