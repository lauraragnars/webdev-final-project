<?php

require_once(__DIR__.'/globals.php');

if( !isset($_GET['key'])){
    echo "hmm... suspicious";
    exit();
}

if( strlen($_GET['key']) != 32 ){
    echo "hmm... suspicious (key is not 32)";
    exit();
}

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

$q = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
  $q->bindValue(":user_id", $_GET['id']);
  $q->execute();
  $row = $q->fetch();

// update the user info if the keys match
if( $_GET['key'] != $row['verification_key']){
    echo "hmm... suspicious (keys dont match)";
    exit();
}

$q2 = $db->prepare('UPDATE users SET verified = :verified WHERE user_id = :user_id');
  $q2->bindValue(":verified", 1);
  $q2->bindValue(":user_id", $_GET['id']);
  $q2->execute();

echo "Your email has been verified!";

?>