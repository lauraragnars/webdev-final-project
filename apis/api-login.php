<?php

require_once(__DIR__.'/../globals.php');

// validate
if( ! isset($_POST['email'])){ _res(400, ['info' => 'email required']); };
if( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL ) ){ _res(400, ['info' => 'email is invalid']); }

// validate the password
if( ! isset($_POST['password'])){ _res(400, ['info' => 'password required']); };
if( strlen($_POST['password']) < _PASSWORD_MIN_LEN ){ _res(400, ['info' => 'password must be at least '._PASSWORD_MIN_LEN.' characters']); };
if( strlen($_POST['password']) > _PASSWORD_MAX_LEN ){ _res(400, ['info' => 'password cannot be more than '._PASSWORD_MAX_LEN.' characters']); };

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    // get data
    $q = $db->prepare('SELECT * FROM users WHERE user_email = :user_email');
    $q->bindValue(':user_email', $_POST['email']);
    $q->execute();
    $row = $q->fetch();
    if(!$row){ _res(400, ['info' => 'Wrong email', 'error' => __LINE__]);}

    $password = $_POST['password'];
    $hashed_password = $row['user_password'];

    if (!password_verify($password, $hashed_password)){
        _res(400, ['info' => 'Wrong password', 'error' => __LINE__]);
    }

    // success
    session_start();
    $_SESSION['user_name'] = $row['user_name'];
    $_SESSION['user_last_name'] = $row['user_last_name'];
    $_SESSION['user_phone_number'] = $row['user_phone_number'];
    $_SESSION['user_email'] = $_POST['email'];
    _res(200, ['info' => 'Successfully logged in']);

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}
