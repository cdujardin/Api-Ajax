<?php

// importation de classes
use Michelf\Markdown;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// chargement de l'autoloading
require_once __DIR__.'/../vendor/autoload.php';

// gestion des erreurs
ErrorHandler::register();
ExceptionHandler::register();

function getUser($app, $id) {
    $sql = "SELECT * FROM user WHERE id = ?";

    try {
        $user = $app['db']->fetchAssoc($sql, [(int) $id]);
    } catch (Exception $e) {
        $myException = new StdClass();
        $myException->error = true;
        $myException->message = $e->getMessage();
        $myException->code = $e->getCode();

        return $app->json($myException, 500);
    }

    if (!$user) {
        $myException = new StdClass();
        $myException->error = true;
        $myException->message = 'Vous devez fournir un id valide';
        $myException->code = 0;

        return $app->json($myException, 400);
    }

    return $app->json($user);
}

// création d'une nouvelle appli
$app = new Silex\Application();

// activation du mode déboggage
$app['debug'] = true;

// gestion des erreurs
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    $myException = new StdClass();
    $myException->error = true;
    $myException->message = $e->getMessage();
    $myException->code = $code;

    return $app->json($myException, 500);
});

// transformation automatique de données json dans le corps de la requête en tableau php
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});


// permettre les connexions ajax
$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

// connexion à la base de données
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'api_user',
        'user'      => 'root',
        'password'  => '',
        'charset'   => 'utf8',
    ),
));

// home du site
$app->get('/', function() {
    // @todo utiliser twig plutôt que du html en dur
    $htmlHead = '<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <title></title>
  </head>
  <body>
';

    $htmlTail = '  </body>
</html>
';

    // lecture du fichier README.md
    $text = file_get_contents('../README.md');

    // transformation du markdonw en html
    $html = Markdown::defaultTransform($text);

    return $htmlHead . $html . $htmlTail;
});

// liste des users
$app->get('/api/users/', function() use ($app) {
    $sql = "SELECT * FROM user";

    try {
        $users = $app['db']->fetchAll($sql);
    } catch(Exception $e) {
        $myException = new StdClass();
        $myException->error = true;
        $myException->message = $e->getMessage();
        $myException->code = $e->getCode();

        return $app->json($myException, 500);
    }

    return $app->json($users);
});

// détails d'un user
$app->get('/api/users/{id}', function($id) use ($app) {
    return getUser($app, $id);
});

// création d'un nouveau user
$app->post('/api/users/', function(Request $request) use ($app) {
    $firstname = $request->get('firstname');
    $lastname = $request->get('lastname');
    $email = $request->get('email');
    $birthday = $request->get('birthday');
    $github = $request->get('github');
    $sex = $request->get('sex');
    $pet = $request->get('pet');

    if ($pet == 'true') {
        $pet = true;
    } elseif ($pet == 'false') {
        $pet = false;
    }

    $myException = new StdClass();
    $myException->message = [];

    if ($pet != 'true' && $pet != 'false' && !empty($pet)) {
        $myException->error = true;
        $myException->message[] = 'Le champ optionnel animal de compagnie, peut prendre les valeurs : "true", "false" ou ""';
        $myException->code = 0;
    }

    if ($sex != 'M' && $sex != 'F') {
        $myException->error = true;
        $myException->message[] = 'Vous devez fournir un sexe valide (M ou F)';
        $myException->code = 0;
    }

    if (isset($myException->error)) {
        return $app->json($myException, 400);
    }

    try {
        $app['db']->insert('user', [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'birthday' => $birthday,
            'github' => $github,
            'sex' => $sex,
            'pet' => $pet,
        ]);

        $lastId = $app['db']->lastInsertId();
    } catch(Exception $e) {
        $myException->error = true;
        $myException->message = $e->getMessage();
        $myException->code = $e->getCode();

        return $app->json($myException, 500);
    }

    return getUser($app, $lastId);
});

// modification d'un user
$app->put('/api/users/{id}', function(Request $request, $id) use ($app) {
    $firstname = $request->get('firstname');
    $lastname = $request->get('lastname');
    $email = $request->get('email');
    $birthday = $request->get('birthday');
    $github = $request->get('github');
    $sex = $request->get('sex');
    $pet = $request->get('pet');

    if ($pet == 'true') {
        $pet = true;
    } elseif ($pet == 'false') {
        $pet = false;
    }

    $app['db']->update('user', [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'birthday' => $birthday,
        'github' => $github,
        'sex' => $sex,
        'pet' => $pet,
    ], [
        'id' => $id,
    ]);

    return getUser($app, $id);;
});

// suppression d'un user
$app->delete('/api/users/{id}', function($id) use ($app) {
    $resultat = $app['db']->delete('user', [
        'id' => (int) $id,
    ]);

    if ($resultat) {
        $return = 204;
    } else {
        $return = 500;
    }

    return new Response('', $return);
});

// démarrage de l'appli
$app->run();
?>
              </body>
</html>
