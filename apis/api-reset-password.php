<?php

require_once(__DIR__.'/../globals.php');

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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  
    $forgot_password_key = bin2hex(random_bytes(16));


    // Update data 
    $q = $db->prepare('UPDATE users SET user_password = :user_password, forgot_password_key = :forgot_password_key WHERE user_id = :user_id');
    $q->bindValue(":user_id", $_POST['user_id']);
    $q->bindValue(":forgot_password_key", $forgot_password_key);
    $q->bindValue(":user_password", $password);
    $q->execute();

    // SUCCESS
    header('Content-Type: application/json');
  
    _res(200, ['info' => 'Successfully changed password']);
    
  }catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
  }