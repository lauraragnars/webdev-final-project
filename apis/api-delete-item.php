<?php
require_once(__DIR__.'/../globals.php');

try{
    $db = _db();

}catch(Exception $ex){
    _res(500, ['info'=>'System under maintenence', 'error'=> __LINE__]);
}

try{
    $q = $db->prepare('DELETE FROM items WHERE item_id = :item_id');
    $q->bindValue(':item_id', $_POST['item_id']);
    $q->execute();
    echo "Item deleted";
}catch(Exception $ex){
    http_response_code(500);
    echo 'System under maintainance'.__LINE__;;
    exit();
  }
  