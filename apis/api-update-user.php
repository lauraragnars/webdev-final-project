<?php

require_once('../globals.php');
session_start();

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

// Connect to DB
try{
    $db = _db();
  
  }catch(Exception $ex){
    _res(500, ['info' => 'System under maintenence', 'error' => __LINE__]);
  }

  try{
    // Update data 
    $q = $db->prepare('UPDATE users SET user_name = :user_name, user_email = :user_email, user_last_name = :user_last_name WHERE user_email = :email');
    $q->bindValue(":user_name", $_POST['name']);
    $q->bindValue(":user_last_name", $_POST['last_name']);
    $q->bindValue(":user_email", $_POST['email']);
    $q->bindValue(":email", $_SESSION['user_email']);
    $q->execute();

    // SUCCESS
    header('Content-Type: application/json');
  
    $_SESSION['user_name'] = $_POST['name'];
    $_SESSION['user_last_name'] = $_POST['last_name'];
    $_SESSION['user_email'] = $_POST['email'];
  
    $response = ["info" => "user info updated"];
    echo json_encode($response);
    
  }catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance';
    exit();
  }