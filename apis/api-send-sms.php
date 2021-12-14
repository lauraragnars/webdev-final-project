<?php

    if( !isset($_POST['to_phone'])){ http_response_code(400); echo 'to_phone required'; exit(); }
    if( !isset($_POST['message'])){ http_response_code(400); echo 'message required'; exit(); }

    $api_key = "286de1fe-c456-4edd-b303-c2c3d1ee25dc";

    $fields = [
        'to_phone' => $_POST['to_phone'],
        'message' => $_POST['message'],
        'api_key' => $api_key
    ];
    $postdata = http_build_query($fields);
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, 'https://fatsms.com/send-sms');
    curl_setopt($ch,CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    echo $result;
?>
