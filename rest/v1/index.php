<?php
use \Firebase\JWT\JWT;

require_once '../../vendor/autoload.php';
require_once '../PersistenceManager.class.php';
require_once '../Config.class.php';

Flight::register('pm', 'PersistenceManager', [Config::DB]);
Flight::register('google', 'League\OAuth2\Client\Provider\Google', [Config::GOOGLE]);

Flight::set('flight.base_url', '/');

//$this->get('flight.base_url');

/*
Flight::before('start', function(&$params, &$output){
  Flight::halt(404, Flight::json(['error' => 'Email does not exist']));
  die;
  print_r($params);
  print_r($output);

    print_r(Flight::request());
});*/




Flight::route('POST /getdata', function(){
$userdata = Flight::request()->data->email;
    $records = Flight::pm()->getdata($userdata);
    Flight::json($records);
});
Flight::route('POST /getactivities', function(){
$userdata2 = Flight::request()->data->email;
    $records2 = Flight::pm()->getactivities($userdata2);
    Flight::json($records2);
});
Flight::route('POST /getactivitiesfull', function(){
$userdata3 = Flight::request()->data->email;
    $records3 = Flight::pm()->getactivitiesfull($userdata3);
    Flight::json($records3);
});
Flight::route('POST /getactivitiesall', function(){
  $userdata2 = Flight::request()->data->email;
      $records2 = Flight::pm()->getactivitiesall($userdata2);
      Flight::json($records2);
  });



  Flight::route('GET /getentrymonth/@registration', function ($registration) {
    $data = Flight::pm()->query("SELECT COUNT(datetime) as number_of_entries_month FROM activities WHERE datetime BETWEEN (DATE_SUB(NOW(), INTERVAL 1 month)) AND CURRENT_TIMESTAMP AND action='0' AND registration= :registration ", [':registration' => $registration]);
    Flight::json($data);
});
Flight::route('GET /getentryday/@registration', function ($registration) {
  $data = Flight::pm()->query("SELECT COUNT(datetime) as number_of_entries_day FROM activities WHERE datetime BETWEEN (DATE_SUB(NOW(), INTERVAL 1 day)) AND CURRENT_TIMESTAMP AND action='0' AND registration= :registration ", [':registration' => $registration]);
  Flight::json($data);
});
Flight::route('GET /getentryyear/@registration', function ($registration) {
  $data = Flight::pm()->query("SELECT COUNT(datetime) as number_of_entries_year FROM activities WHERE datetime BETWEEN (DATE_SUB(NOW(), INTERVAL 1 year)) AND CURRENT_TIMESTAMP AND action='0' AND registration= :registration ", [':registration' => $registration]);
  Flight::json($data);
});
Flight::route('GET /getentrytotal/@registration', function ($registration) {
  $data = Flight::pm()->query("SELECT COUNT(datetime) as number_of_entries_total FROM activities WHERE action='0' AND registration= :registration ", [':registration' => $registration]);
  Flight::json($data);
});

// function return all items from table items :)


Flight::route('POST /login', function(){
  $email = Flight::request()->data->email;
  $user = Flight::pm()->get_user_by_email($email);
  if ($user){
    $url = Flight::google()->getAuthorizationUrl();
    $redirect_uri = $url.'&login_hint='.$email;
    $user['redirect_uri'] = $redirect_uri;
    Flight::json($user);
  }else{
    Flight::halt(404, Flight::json(['error' => 'Email does not exist']));
  }
});
Flight::route('GET /redirect', function(){
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  try {
    $code = Flight::request()->query->code;
    $access_token = Flight::google()->getAccessToken('authorization_code', ['code' => $code]);
    $owner = Flight::google()->getResourceOwner($access_token);
    Flight::pm()->update_user_by_email($owner->getEmail(), str_replace('?sz=50', '?sz=250', $owner->getAvatar()), $owner->getId(), $owner->getName());
    $user = Flight::pm()->get_user_by_email($owner->getEmail());
    $token = ["user" => $user, "iat" => time(), "exp" => time() + 2592000 /*30 days*/];
    $jwt = JWT::encode($token, Config::JWT_SECRET);
    Flight::redirect('/redirect.html?t='.$jwt);
  } catch (Exception $e) {
    print_r($e);
  }
});
Flight::route('POST /decode', function(){
  try {
    $token = Flight::request()->data->token;
    $user = (array)JWT::decode($token, Config::JWT_SECRET, ['HS256'])->user;
    Flight::json($user);
  } catch (Exception $e) {
    Flight::halt(500, Flight::json(['error' => $e->getMessage()]));
  }
});
Flight::route('GET /user/@email', function($email){
  $email = Flight::request()->data->email;
  $user = Flight::pm()->get_user_by_email($email);
  if ($user){
    Flight::json($user);
  }else{
    Flight::halt(404, Flight::json(['error' => 'Email does not exist']));
  }
});

Flight::start();
?>
