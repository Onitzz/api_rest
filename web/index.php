<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../vendor/autoload.php';

$users = [
  ['id' => 0, 'name' => 'lorem'],
  ['id' => 1, 'name' => 'ipsum'],
  ['id' => 2, 'name' => 'foo']
];

$app = new Silex\Application();


//activer le mode debug
$app['debug']=true;

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/', function(){
  return <<<EOT
  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
  </head>
  <body>
    <pre>
    GET /api/users/
    renvoit la liste des utilisateurs

    GET /api/users/{id}
    renvoit le détail d'un utilisateur

    POST /api/users/
    ajoute un utilisateur

    PUT /api/users/{id}
    ajoute ou modifie un utilisateur

    DELETE /api/users/{id}
    Supprime un utilisateur'

    </pre>
  </body>
  </html>
EOT;
});

$app->get('api/users', function() use($users){
  return json_encode($users);
});


$app->get('/api/users/{id}', function($id) use($users){
  return json_encode($users[$id]);
});

$app->post('/api/users/', function(Request $request) use ($users){
  $name = $request->get('name');

  $nextIndex = count($users);

  $users[]=[
    'id'=> $nextIndex,
    'name'=> $name
  ];

  $lastId = count($users) - 1;

  return $lastId;
});

$app->delete('/api/users/{id}', function($id) use ($users){
  unset($user[$id]);

  return new Response('', 204);
});

$app->run();
