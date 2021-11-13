<?php
$_title = 'Login';
require_once('components/header.php');
?>

    <div class="modal">
    <h1>Welcome to Zillow</h1>
    <div class="login-options">
        <a class="selected">Sign in</a>
        <a class="unselected" href="signup">New account</a>
    </div>
        <form onsubmit="return false">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" placeholder="Email">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Password">
            <h3 class="error-message"></h3>
            <button onclick="login()">Login</button>
        </form>
    </div>

    <script>
        async function login(){
           const form = event.target.form;
           let conn = await fetch("api-login", {
               method: "POST",
               body: new FormData(form)
           }) 
           let res = await conn.json();
           console.log(res)

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