<?php
$_title = 'Signup';
require_once(__DIR__.'/components/header.php');
?>


<div class="modal">
<h1>Welcome to Zillow</h1>
    <div class="login-options">
        <a class="unselected" href="index" >Sign in</a>
        <a class="selected">New account</a>
    </div>

    <form id="form_signup" onsubmit="return false">
        <label for="name">First name</label>
        <input name="name" type="text" placeholder="First name">
        <label for="last_name">Last name</label>
        <input name="last_name" type="text" placeholder="Last name">
        <label for="email">Email</label>
        <input name="email" type="email" placeholder="Email">
        <label for="phonenumber">Phone number</label>
        <input name="phone_number" type="text" maxlength="8" placeholder="Phone number">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password">
        <label for="password">Confirm password</label>
        <input type="password" name="password2" id="password2" placeholder="Confirm password">
        <h3 class="error-message"></h3>
        <button onclick="signUp()">Signup</button>
    </form>
</div>

<script>
    async function signUp(){
    const form = event.target.form;

       let conn = await fetch("./apis/api-signup.php", {
           method : "POST",
           body: new FormData(form)
       })
       
       if (!conn.ok){
        let res = await conn.json()
        document.querySelector(".error-message").textContent = res.info
       } else if (conn.ok){
        location.href = "home"
       }
    }
</script>

<?php
require_once(__DIR__.'/components/footer.php');
?>
