<?php

require_once(__DIR__.'/../globals.php');

// validate email
if( ! isset($_POST['email'])){ _res(400, ['info' => 'Email required']); };
if( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL ) ){ _res(400, ['info' => 'Email is invalid']); }

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
    if(!$row){ _res(400, ['info' => 'No account with this email', 'error' => __LINE__]);}

    $forgot_password_key = $row['forgot_password_key'];
    $user_id = $row['user_id'];

    $_message = "<a href='http:localhost:8888/final-project/webdev-final-project/reset-password.php?key=$forgot_password_key&id=$user_id'>To create a new password click here</a>";
    $_to_email = $_POST['email'];
  
    require_once(__DIR__.'/../private/send-email.php');

    _res(200, ['info' => 'Email sent!']);

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}
