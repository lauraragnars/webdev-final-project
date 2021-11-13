<?php

require_once('globals.php');

// Validate name
if( ! isset( $_POST['name'] ) ){ _res(400, ['info' => 'Name required']); };
if( strlen( $_POST['name'] ) < _NAME_MIN_LEN ){ _res(400, ['info' => 'Name min '._NAME_MIN_LEN.' characters']); };
if( strlen( $_POST['name'] ) > _NAME_MAX_LEN ){ _res(400, ['info' => 'Name max '._NAME_MAX_LEN.' characters']); };

// Validate last_name
if( ! isset( $_POST['last_name'] ) ){ _res(400, ['info' => 'Last name required']); };
if( strlen( $_POST['last_name'] ) < _NAME_MIN_LEN ){ _res(400, ['info' => 'Last name min '._NAME_MIN_LEN.' characters']); };
if( strlen( $_POST['last_name'] ) > _NAME_MAX_LEN ){ _res(400, ['info' => 'Last name max '._NAME_MAX_LEN.' characters']); };

// Validate email
if( ! isset( $_POST['email'] ) ){ _res(400, ['info' => 'Email required']); };
if( ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) ){ _res(400, ['info' => 'Email is invalid']); };

// validate the password
if( ! isset($_POST['password'])){ _res(400, ['info' => 'password required']); };
if( strlen($_POST['password']) < _PASSWORD_MIN_LEN ){ _res(400, ['info' => 'password must be at least '._PASSWORD_MIN_LEN.' characters']); };
if( strlen($_POST['password']) > _PASSWORD_MAX_LEN ){ _res(400, ['info' => 'password cannot be more than '._PASSWORD_MAX_LEN.' characters']); };


// Connect to DB
try{
  $db = _db();

}catch(Exception $ex){
  _res(500, ['info' => 'System under maintenence', 'error' => __LINE__]);
}

try{

  $q2 = $db->prepare('SELECT * FROM users WHERE user_email = :email');
  $q2->bindValue(":email", $_POST['email']);
  $q2->execute();
  $row = $q2 -> fetch();
  
  if ($row){
    _res(400, ['info' => 'Email already exits']);
  }

  // Insert data in the DB
  $q = $db->prepare('INSERT INTO users 
  VALUES(:user_id, :user_name, :user_email, :user_last_name, :user_password)');
  $q->bindValue(":user_id", null); // The db will give this automati.
  $q->bindValue(":user_name", $_POST['name']);
  $q->bindValue(":user_email", $_POST['email']);
  $q->bindValue(":user_last_name", $_POST['last_name']);
  $q->bindValue(":user_password", $_POST['password']);
  $q->execute();
  $user_id = $db->lastinsertid();
  // SUCCESS
  header('Content-Type: application/json');

  session_start();
  $_SESSION['user_name'] = $_POST['name'];
    
  $response = ["info" => "user created", "user_id" => $user_id];
  echo json_encode($response);
}catch(Exception $ex){
  http_response_code(500);
  echo 'System under maintainance';
  exit();
}
