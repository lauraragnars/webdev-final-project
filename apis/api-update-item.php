<?php
require_once(__DIR__.'/../globals.php');

// Validate
if( !isset($_POST['item_name'])){ http_response_code(400); echo 'item_name required'; exit(); }
if(strlen($_POST['item_name']) < _ITEM_MIN_LEN){ http_response_code(400); echo 'item_name min '._ITEM_MIN_LEN.' characters'; exit(); }
if(strlen($_POST['item_name']) > _ITEM_MAX_LEN){ http_response_code(400); echo 'item_name max '._ITEM_MAX_LEN.' characters'; exit(); }

if( ! isset( $_POST['item_description'] ) ){ _res(400, ['info' => 'Item desc required']); };

if( ! isset( $_POST['item_price'] ) ){ _res(400, ['info' => 'Item price required']); };

if( ! isset( $_POST['item_image'] ) ){ _res(400, ['info' => 'Item image required']); };

if( ! isset( $_POST['item_id'] ) ){ _res(400, ['info' => 'Item id required']); };

// @TODO add validation for all inputs

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    $q = $db->prepare('UPDATE items SET item_name = :item_name, item_description = :item_description, item_price = :item_price, item_image = :item_image WHERE item_id = :item_id');
    $q->bindValue(':item_id', $_POST['item_id']);
    $q->bindValue(':item_name', $_POST['item_name']);
    $q->bindValue(':item_description', $_POST['item_description']);
    $q->bindValue(':item_price', $_POST['item_price']);
    $q->bindValue(':item_image', $_POST['item_image']);
    $q->execute();

    header('Content-Type: application/json');
    
    $item_id = $_POST['item_id'];
    $response = ["info" => "Item updated with id: $item_id"];
    echo json_encode($response);
}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
  }
  