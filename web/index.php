<?php
use Michelf\Markdown;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../vendor/autoload.php';

//fonction de récupération d'un utilisateur
function getUser($app, $id){
  $sql = "SELECT * FROM user WHERE id = ?";

  try {
    $users = $app['db']->fetchAssoc($sql, [(int) $id]);
  } catch (Exception $e) {
    $myException = new StdClass();
    $myException->error = true;
    $myException->message = $e->getMessage();
    $myException->code = $e->getCode();

    return $app->json($myException, 500);
  }

  if(!$users){
    $myException = new StdClass();
    $myException->error = true;
    $myException->message = 'Vous devez fournir un id valide';
    $myException->code = 0;

    return $app->json($myException, 400);
  }

  return $app->json($users);
}

$app = new Silex\Application();


//activer le mode debug
$app['debug']=true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
      'driver'    => 'pdo_mysql',
      'host'      => 'localhost',
      'dbname'    => 'api_user',
      'user'      => 'test',
      'password'  => 'test',
      'charset'   => 'utf8',
    ),
));

$app->get('/', function(){
  // @todo utiliser twig plutôt que du html
  $htmlHead= '
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title></title>
      <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    ';
  $htmlTail='</body>
  </html>';

  $text = file_get_contents('../README.md');
  $html = Markdown::defaultTransform($text);
  return $htmlHead.$html.$htmlTail;
});

// liste des users
$app->get('/api/users/', function() use($app){
  $sql = "SELECT * FROM user";
  try {
    $users = $app['db']->fetchAll($sql);
  } catch (Exception $e) {
    $myException = new StdClass();
    $myException->error = true;
    $myException->message = $e -> getMessage();
    $myException->code = $e ->getCode();

    return $app->json($myException, 500);
  }


  return $app->json($users);
});

// détail d'un user
$app->get('/api/users/{id}', function($id) use($app){
  return getUser($app, $id);
});

// ajouter un user
$app->post('/api/users/', function(Request $request) use ($app){
  $firstname = $request->get('firstname');
  $lastname = $request->get('lastname');
  $email = $request->get('email');
  $birthday = $request->get('birthday');
  $github = $request->get('github');
  $sex = $request->get('sex');
  $pet = $request->get('pet');

  if($pet == 'true'){
    $pet = true;
  }
  elseif($pet == 'false'){
    $pet = false;
  }

  $myException = new StdClass();
  $myException->message = [];

  if ($pet != 'true' && $pet != 'false' && !empty($pet)){
    $myException->error = true;
    $myException->message[] = 'Ce champ optionnel prend les valeur : "true", "false" ou "" ';
    $myException-> code = 0;
  }

  if($sex != 'M' && $sex != 'F'){
    $myException->error = true;
    $myException->message[] = 'Vous devez fournir un sexe valide (M ou F)';
    $myException-> code = 0;
  }

  if(isset($myException->error)){
    return $app->json($myException, 400);
  }

  try {
    $app['db']->insert('user', [
      'firstname'=>$firstname,
      'lastname'=>$lastname,
      'email'=>$email,
      'birthday'=>$birthday,
      'github'=>$github,
      'sex'=>$sex,
      'pet'=>$pet,
    ]);
  } catch (Exception $e) {
    $myException = new StdClass();
    $myException->error = true;
    $myException->message = $e->getMessage();
    $myException-> code = $e->getCode();

    return $app->json($myException, 500);
  }

  $lastId = $app['db']->lastInsertId();

  return getUser($app, $lastId);

});

//update
$app->put('/api/users/{id}', function(Request $request,$id) use ($app){
  $firstname = $request->get('firstname');
  $lastname = $request->get('lastname');
  $email = $request->get('email');
  $birthday = $request->get('birthday');
  $github = $request->get('github');
  $sex = $request->get('sex');
  $pet = $request->get('pet');

  $myException = new StdClass();
  $myException->message = [];

  if ($pet != 'true' && $pet != 'false' && !empty($pet)){
    $myException->error = true;
    $myException->message[] = 'Ce champ optionnel prend les valeur : "true", "false" ou "" ';
    $myException-> code = 0;
  }

  if($sex != 'M' && $sex != 'F'){
    $myException->error = true;
    $myException->message[] = 'Vous devez fournir un sexe valide (M ou F)';
    $myException-> code = 0;
  }

  if(isset($myException->error)){
    return $app->json($myException, 400);
  }


  try {
    $app['db']->update('user', array(
      'firstname' => $firstname,
      'lastname' => $lastname,
      'email' => $email,
      'birthday' => $birthday,
      'github' => $github,
      'sex' => $sex,
      'pet' => $pet,
    ),array('id' => $id));
  } catch (Exception $e) {
    $myException = new StdClass();
    $myException->error = true;
    $myException->message = $e->getMessage();
    $myException-> code = $e->getCode();

    return $app->json($myException, 500);
  }

  return getUser($app, $id);
});

$app->delete('/api/users/{id}', function($id) use ($app){
  $resultat = $app['db']->delete('user', [
    'id'=> (int) $id,
  ]);

  if ($resultat) {
    $return = 204;
  }else {
    $return = 500;
  }

  return new Response('', $return);
});

$app->run();
