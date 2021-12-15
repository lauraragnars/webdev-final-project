<?php

require_once(__DIR__.'/../globals.php');

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

// Validate phonenumber 
if( ! isset( $_POST['phone_number'] ) ){ _res(400, ['info' => 'Phone number required']); };
if( ! is_numeric( $_POST['phone_number'] ) ){ _res(400, ['info' => 'Phone number can only contain numbers']); };
if( strlen( $_POST['phone_number'] ) < _PHONE_MIN_LEN ){ _res(400, ['info' => 'Phone number min '._PHONE_MIN_LEN.' characters']); };
if( strlen( $_POST['phone_number'] ) > _PHONE_MAX_LEN ){ _res(400, ['info' => 'Phone number max '._PHONE_MAX_LEN.' characters']); };

// validate the password
if( ! isset($_POST['password'])){ _res(400, ['info' => 'password required']); };
if( strlen($_POST['password']) < _PASSWORD_MIN_LEN ){ _res(400, ['info' => 'password must be at least '._PASSWORD_MIN_LEN.' characters']); };
if( strlen($_POST['password']) > _PASSWORD_MAX_LEN ){ _res(400, ['info' => 'password cannot be more than '._PASSWORD_MAX_LEN.' characters']); };

// check that passwords match 
if( ! isset($_POST['password2'])){ _res(400, ['info' => 'Both password fields required']); };
if( strlen($_POST['password2']) < _PASSWORD_MIN_LEN ){ _res(400, ['info' => 'password must be at least '._PASSWORD_MIN_LEN.' characters']); };
if( strlen($_POST['password2']) > _PASSWORD_MAX_LEN ){ _res(400, ['info' => 'password cannot be more than '._PASSWORD_MAX_LEN.' characters']); };
if($_POST['password2'] !== $_POST['password']){ _res(400, ['info' => 'Passwords do not match']); };


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

  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  

  // verify
  $verification_key = bin2hex(random_bytes(16));
  $forgot_password_key = bin2hex(random_bytes(16));


  // Insert data in the DB
  $q = $db->prepare('INSERT INTO users 
  VALUES(:user_id, :user_name, :user_email, :user_last_name, :user_phone_number, :user_password, :verification_key, :verified, :forgot_password_key)');
  $q->bindValue(":user_id", null); // The db will give this automatically. 
  $q->bindValue(":user_name", $_POST['name']);
  $q->bindValue(":user_email", $_POST['email']);
  $q->bindValue(":user_last_name", $_POST['last_name']);
  $q->bindValue(":user_phone_number", $_POST['phone_number']);
  $q->bindValue(":user_password", $password);
  $q->bindValue(":verification_key", $verification_key);
  $q->bindValue(":forgot_password_key", $forgot_password_key);
  $q->bindValue(":verified", 0);

  $q->execute();

  $user_id = $db->lastinsertid();


  // SUCCESS
  header('Content-Type: application/json');

  session_start();
  $_SESSION['user_id'] = $user_id;
  $_SESSION['user_name'] = $_POST['name'];
  $_SESSION['user_last_name'] = $_POST['last_name'];
  $_SESSION['user_email'] = $_POST['email'];
  $_SESSION['user_phone_number'] = $_POST['phone_number'];

  $response = ["info" => "user created", "user_id" => $user_id];
  echo json_encode($response);

  $_message = "Thank you for signing up <a href='http:localhost:8888/final-project/webdev-final-project/validate-user.php?key=$verification_key&id=$user_id'>Click here to verify your account</a>";
  $_to_email = $_POST['email'];

  $_sms_message = "User created on Zillow";
  $_to_phone = $_POST['phone_number'];

  require_once(__DIR__.'/../private/send-email.php');
  require_once(__DIR__.'/../private/send-sms.php');

}catch(Exception $ex){
  http_response_code(500);
  echo 'System under maintainance';
  exit();
}
