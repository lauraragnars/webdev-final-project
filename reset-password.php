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
if( $_GET['key'] != $row['forgot_password_key']){
    echo "hmm... suspicious (keys dont match)";
    exit();
}
?>

<div class="modal">
   <h1>Reset password</h1>
    <form data-id=<?= $_GET['id'] ?> onsubmit="return false">
            <label for="password">New password</label>
            <input type="password" name="password" id="password" placeholder="Password">
            <label for="password2">Confirm password</label>
            <input type="password" name="password2" id="password2" placeholder="Confirm password">
            <h3 class="error-message"></h3>
            <button onclick="resetPassword()">Reset password</button>
        </form>
</div>

<script>
     async function resetPassword(){
        const form = event.target.form;
        const userId = form.dataset.id

        const formData = new FormData(form)
        formData.append('user_id', userId);

        let conn = await fetch("./apis/api-reset-password.php", {
            method: "POST",
            body: formData
        }) 
        let res = await conn.json();

        if (!conn.ok){
            document.querySelector(".error-message").textContent = res.info
        } else if (conn.ok){
            document.querySelector(".error-message").textContent = res.info
        }
        console.log(res)
        }
</script>
