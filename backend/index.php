<?php

use BlobbyBob\HochwasserAhrtal2021\Complaint;
use BlobbyBob\HochwasserAhrtal2021\Correction;
use BlobbyBob\HochwasserAhrtal2021\Contact;
use BlobbyBob\HochwasserAhrtal2021\Media;
use BlobbyBob\HochwasserAhrtal2021\PersonalMedia;
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

getPDO()->exec('SET CHARACTER SET UTF8MB4');

$app = AppFactory::create();

$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);

    $allowOrigin = 'hochwasser-ahrtal-2021.de';
    $origin = $request->getHeader('Origin');
    if (count($origin) == 1) {
        if (array_search(strpos($origin[0], 'hochwasser-ahrtal-2021.de'), [7, 8, 11, 12])) {
            $allowOrigin = $origin[0];
        }
    }

    return $response
        ->withHeader('Access-Control-Allow-Origin', $allowOrigin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
        ->withHeader('Vary', 'Origin');
});

$app->addErrorMiddleware(false, true, true);

$app->get('/api/towns', function (Request $request, Response $response) {
    $stmt = getPDO()->prepare('SELECT * FROM towns');
    $stmt->execute();

    $towns = [];
    /** @var Town $town */
    while (($town = $stmt->fetchObject(Town::class))) {
        $town->setTypes();
        $towns[] = $town;
    }

    $response->getBody()->write(json_encode($towns));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/town/{name}', function (Request $request, Response $response, array $args) {
    $stmt = getPDO()->prepare('SELECT * FROM towns WHERE route = ?');
    $stmt->execute([$args['name']]);

    /** @var Town $town */
    $town = $stmt->fetchObject(Town::class);

    if (!$town) {
        return $response->withStatus(404, 'Not Found');
    }
    $town->setTypes();

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

    $stmt = getPDO()->prepare('SELECT * FROM media WHERE town = ? AND disabled = 0');
    $stmt->execute([$town->id]);

    $medias = [];
    /** @var Media $media */
    while (($media = $stmt->fetchObject(Media::class))) {
        $media->setTypes();
        $medias[] = $media;
    }

    $response->getBody()->write(json_encode($medias));
    return $response->withHeader('Content-Type', 'application/json');
});

function postHelper(Request $request, Response $response, $class, $query, $getParams, $gdprOptional = false)
{
    $contentType = $request->getHeaderLine('Content-Type');
    $response = $response->withHeader('Accept', 'application/json');

    if (strpos($contentType, 'application/json') === false) {
        return $response->withStatus(406, 'Not Acceptable');
    }

    $data = json_decode($request->getBody()->getContents(), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $response->withStatus(400, 'Bad Request');
    }

    if (!isset($data['gdpr']) && !$gdprOptional) {
        return $response->withStatus(409, 'Conflict');
    }

    $object = new $class();
    $reflect = new ReflectionClass($object);
    $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
    foreach ($props as $prop) {
        if (isset($data[$prop->getName()])) $object->{$prop->getName()} = $data[$prop->getName()];
    }
    $object->setTypes();
    if (!$object->validateUserInput()) {
        return $response->withStatus(401, 'Bad Request');
    }

    $stmt = getPDO()->prepare($query);
    try {
        if (!$stmt->execute($getParams($object))) {
            return $response->withStatus(404, 'Not Found');
        }
    } catch (PDOException $e) {
        return $response->withStatus(404, 'Not Found');
    }

    return $response->withStatus(201, 'Accepted');
}

$app->post('/api/contact', function (Request $request, Response $response) {

    return postHelper($request, $response, Contact::class,
        'INSERT INTO contact (name, email, request, latitude, longitude, copyright) VALUES (?, ?, ?, ?, ?, ?)',
        function (Contact $contact) {
            return [$contact->name, $contact->email, $contact->request, $contact->latitude, $contact->longitude, $contact->copyright];
        });
});

$app->post('/api/personal', function (Request $request, Response $response) {

    return postHelper($request, $response, PersonalMedia::class,
        'INSERT INTO personalMedia (name, email, request) VALUES (?, ?, ?)',
        function (PersonalMedia $personalMedia) {
            return [$personalMedia->name, $personalMedia->email, $personalMedia->request];
        });
});

$app->post('/api/complaint', function (Request $request, Response $response) {

    return postHelper($request, $response, Complaint::class,
        'INSERT INTO complaints (name, email, request, media) VALUES (?, ?, ?, ?)',
        function (Complaint $complaint) {
            return [$complaint->name, $complaint->email, $complaint->request, $complaint->media];
        });
});

$app->post('/api/correction', function (Request $request, Response $response) {

    return postHelper($request, $response, Correction::class,
        'INSERT INTO corrections (media, title, latitude, longitude) VALUES (?, ?, ?, ?)',
        function (Correction $correction) {
            return [$correction->media, $correction->name, $correction->latitude, $correction->longitude];
        }, 
        true);
});


$app->any('[/{path:.*}]', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents(__DIR__ . '/index.html'));
    return $response;
});
$app->run();
