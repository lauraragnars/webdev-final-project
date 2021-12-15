<?php
require_once(__DIR__.'/../globals.php');

// Validate
if( !isset($_POST['item_name'])){ http_response_code(400); echo 'item_name required'; exit(); }
if(strlen($_POST['item_name']) < _ITEM_MIN_LEN){ http_response_code(400); echo 'item_name min '._ITEM_MIN_LEN.' characters'; exit(); }
if(strlen($_POST['item_name']) > _ITEM_MAX_LEN){ http_response_code(400); echo 'item_name max '._ITEM_MAX_LEN.' characters'; exit(); }

// @TODO add validation for all inputs

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    $item_id = bin2hex(random_bytes(16));
    $q = $db->prepare('INSERT INTO items VALUES(:item_id, :item_name, :item_description, :item_price, :item_image)');
    $q->bindValue(':item_id', $item_id);
    $q->bindValue(':item_name', $_POST['item_name']);
    $q->bindValue(':item_description', $_POST['item_description']);
    $q->bindValue(':item_price', $_POST['item_price']);
    $q->bindValue(':item_image', $_POST['item_image']);
    $q->execute();
    
    // what will be returned in conn
    echo $item_id;
}catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance';
    exit();
  }
  