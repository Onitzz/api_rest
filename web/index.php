<?php

require_once __DIR__.'/../vendor/autoload.php';

$user = [
  'lorem',
  'ipsum',
  'foo',
  'bar',
  'baz'
];

$app = new Silex\Application();

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/', function(){
  return <<<EOT
  <!DOCTYPE html>
  <html lang="en">
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

$app->get('/api/users/{id}', function($id) use($app){
  return "Id de l'utilisateur : ".$app->escape($id);
});

$app->get('api/users', function() use($user){
  return json_encode($user);
});
$app->run();
