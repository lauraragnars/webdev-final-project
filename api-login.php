<?php

require_once('globals.php');

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
    $q = $db->prepare('SELECT * FROM users WHERE user_email = :user_email AND user_password = :user_password');
    $q->bindValue(':user_email', $_POST['email']);
    $q->bindValue(':user_password', $_POST['password']);
    $q->execute();
    $row = $q->fetch();
    //var_export($row);
    if(!$row){ _res(400, ['info' => 'Wrong credentials', 'error' => __LINE__]);}

    // success
    session_start();
    $_SESSION['user_name'] = $row['user_name'];
    _res(200, ['info' => 'Successfully logged in']);

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}





