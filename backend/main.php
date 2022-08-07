<?php

use BlobbyBob\HochwasserAhrtal2021\Endpoint;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Runtime;

use BlobbyBob\HochwasserAhrtal2021\Complaint;
use BlobbyBob\HochwasserAhrtal2021\Correction;
use BlobbyBob\HochwasserAhrtal2021\Contact;
use BlobbyBob\HochwasserAhrtal2021\Media;
use BlobbyBob\HochwasserAhrtal2021\PersonalMedia;
use BlobbyBob\HochwasserAhrtal2021\Town;

require 'vendor/autoload.php';
require __DIR__ . '/config.php';

Runtime::enableCoroutine();


// Handler
$getTowns = function (Request $request, Response $response, PDO $pdo, array $args) {
    $stmt = $pdo->prepare('SELECT * FROM towns');
    $stmt->execute();

    $towns = [];
    /** @var Town $town */
    while (($town = $stmt->fetchObject(Town::class))) {
        $town->setTypes();
        $towns[] = $town;
    }

    return $towns;
};

$getTown = function (Request $request, Response $response, PDO $pdo, array $args) {
    $stmt = $pdo->prepare('SELECT * FROM towns WHERE route = ?');
    $stmt->execute([$args['name']]);

    /** @var Town $town */
    $town = $stmt->fetchObject(Town::class);


    if (!$town) {
        $response->setStatusCode(404, "Not Found");
        return false;
    }
    $town->setTypes();

    return $town;
};

$getAllMedia = function (Request $request, Response $response, PDO $pdo, array $args) {
    $stmt = $pdo->prepare('SELECT * FROM media WHERE disabled = 0');
    $stmt->execute();

    $medias = [];
    /** @var Media $media */
    while (($media = $stmt->fetchObject(Media::class))) {
        $media->setTypes();
        $medias[] = $media;
    }

    return $medias;
};

$getTownMedia = function (Request $request, Response $response, PDO $pdo, array $args) {
    $stmt = $pdo->prepare('SELECT * FROM towns WHERE id = ?');
    $stmt->execute([$args['town']]);
    /** @var Town $town */
    $town = $stmt->fetchObject(Town::class);
    if (!$town) {
        $response->setStatusCode(404, "Not Found");
        return false;
    }

    $stmt = $pdo->prepare('SELECT * FROM media WHERE town = ? AND disabled = 0');
    $stmt->execute([$town->id]);

    $medias = [];
    /** @var Media $media */
    while (($media = $stmt->fetchObject(Media::class))) {
        $media->setTypes();
        $medias[] = $media;
    }

    return $medias;
};

/**
 * @throws ReflectionException
 */
function postHelper(Request $request, Response $response, PDO $pdo, string $class, string $query, callable $getParams, $gdprOptional = false): array|bool
{
    $contentType = $request->header["content-type"];
    $response = $response->header('Accept', 'application/json');

    if (!str_contains($contentType, 'application/json')) {
        $response->setStatusCode(406, 'Not Acceptable');
        return false;
    }

    $data = json_decode($request->getContent(), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response->setStatusCode(400, 'Bad Request');
        return false;
    }

    if (!isset($data['gdpr']) && !$gdprOptional) {
        $response->setStatusCode(409, 'Conflict');
        return false;
    }

    $object = new $class();
    $reflect = new ReflectionClass($object);
    $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
    foreach ($props as $prop) {
        if (isset($data[$prop->getName()])) $object->{$prop->getName()} = $data[$prop->getName()];
    }
    $object->setTypes();
    if (!$object->validateUserInput()) {
        $response->setStatusCode(400, 'Bad Request');
        return false;
    }

    $stmt = $pdo->prepare($query);
    if (!$stmt->execute($getParams($object))) {
        $response->setStatusCode(404, "Not Found");
        return false;
    }

    return true;
}

$postContact = function (Request $request, Response $response, PDO $pdo, array $args) {
    return postHelper($request, $response, $pdo, Contact::class,
        'INSERT INTO contact (name, email, request, latitude, longitude, copyright) VALUES (?, ?, ?, ?, ?, ?)',
        function (Contact $contact) {
            return [$contact->name, $contact->email, $contact->request, $contact->latitude, $contact->longitude, $contact->copyright];
        });
};

$postPersonal = function (Request $request, Response $response, PDO $pdo, array $args) {
    return postHelper($request, $response, $pdo, PersonalMedia::class,
        'INSERT INTO personalMedia (name, email, request) VALUES (?, ?, ?)',
        function (PersonalMedia $personalMedia) {
            return [$personalMedia->name, $personalMedia->email, $personalMedia->request];
        });
};

$postComplaint = function (Request $request, Response $response, PDO $pdo, array $args) {
    return postHelper($request, $response, $pdo, Complaint::class,
        'INSERT INTO complaints (name, email, request, media) VALUES (?, ?, ?, ?)',
        function (Complaint $complaint) {
            return [$complaint->name, $complaint->email, $complaint->request, $complaint->media];
        });
};

$postCorrection = function (Request $request, Response $response, PDO $pdo, array $args) {
    return postHelper($request, $response, $pdo, Correction::class,
        'INSERT INTO corrections (media, title, latitude, longitude) VALUES (?, ?, ?, ?)',
        function (Correction $correction) {
            return [$correction->media, $correction->name, $correction->latitude, $correction->longitude];
        }, true);
};


$server = new Server("0.0.0.0", 8080);

$server->on("start", function (Server $server) {
    echo "Server listening on port 8080\n";
});

$server->on("request", function (Request $request, Response $response) use (
    $getTowns,
    $getTown,
    $getAllMedia,
    $getTownMedia,
    $postContact,
    $postPersonal,
    $postComplaint,
    $postCorrection
) {
    try {
        // todo check accept headers

        // Database
        $pdo = new PDO(sprintf("mysql:dbname=%s;host=%s", DB_NAME, DB_HOST), DB_USER, DB_PASS);
        $pdo->exec('SET CHARACTER SET UTF8MB4');

        // CORS
        $allowOrigin = 'hochwasser-ahrtal-2021.de';
        $origin = $request->header['origin'] ?? "";
        if (array_search(strpos($origin, 'hochwasser-ahrtal-2021.de'), [7, 8, 11, 12])) {
            $allowOrigin = $origin;
        }

        $response->header('Access-Control-Allow-Origin', $allowOrigin);
        $response->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->header('Vary', 'Origin');

        // Request Data
        $method = $request->getMethod();
        $path = $request->server["REQUEST_URI"];
        $responseObject = [];

        // Default status codes
        if (in_array($method, ["GET", "HEAD"])) {
            $response->setStatusCode(200, "OK");
        } else if ($method == "POST") {
            $response->setStatusCode(201, "Created");
        }

        // API endpoints
        /** @var Endpoint[] $endpoints */
        $endpoints = [
            new Endpoint("GET", "/towns", $getTowns),
            new Endpoint("GET", "/town/:name", $getTown),
            new Endpoint("GET", "/media", $getAllMedia),
            new Endpoint("GET", "/media/:town", $getTownMedia),
            new Endpoint("POST", "/contact", $postContact),
            new Endpoint("POST", "/personal", $postPersonal),
            new Endpoint("POST", "/complaint", $postComplaint),
            new Endpoint("POST", "/correction", $postCorrection),
        ];

        $foundEndpoint = false;
        foreach ($endpoints as $endpoint) {
            if ($endpoint->matches($method, $path)) {
                $result = $endpoint->execute($request, $response, $pdo);
                if (!is_bool($result)) {
                    $responseObject = $result;
                }
                $foundEndpoint = true;
            }
        }
        if (!$foundEndpoint) {
            $response->setStatusCode(404, "Not Found");
        }

        $response->header("Content-Type", "application/json");
        if ($method != "HEAD") {
            $response->write(json_encode($responseObject));
        }
        $response->end("");

    } catch (Exception $e) {
        $response->setStatusCode(503, "Service Unavailable");
        $response->end("");
        printf("%s:%s %s\n%s\n", $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString());
    }

});

$server->start();
