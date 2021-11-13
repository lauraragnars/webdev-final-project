<?php
$_title = 'Signup';
require_once('components/header.php');
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
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Password">
        <h3 class="error-message"></h3>
        <button onclick="signUp()">Signup</button>
    </form>
</div>

<script>
    async function signUp(){
    const form = event.target.form;
    console.log(form)
       let conn = await fetch("api-signup.php", {
           method : "POST",
           body: new FormData(form)
       })
       let res = await conn.json()
       if (!conn.ok){
           document.querySelector(".error-message").textContent = res.info
       } else if (conn.ok){
        location.href = "user"
       }
       console.log(res)
    }
</script>

<?php
require_once('components/footer.php');
?>
