<?php
    session_start();
    if( ! isset($_SESSION['user_name'])){
        header('Location: index');
        exit();
    };

    $_title = 'User page';
    require_once('components/header.php'); 
?>
    <nav>
        <a href="logout">Logout</a>
    </nav>
    <h1>
        <?php
            echo $_SESSION['user_name'];
        ?>
    </h1>

    </div>
        <form onsubmit="return false">
            <label for="name">First name</label>
            <input value=<?= $_SESSION['user_name'] ?> name="name" type="text" placeholder="First name">
            <label for="last_name">Last name</label>
            <input value=<?= $_SESSION['user_last_name'] ?> name="last_name" type="text" placeholder="Last name">
            <label for="email">Email</label>
            <input value=<?= $_SESSION['user_email'] ?> type="text" name="email" id="email" placeholder="Email">
            <label for="password">Password</label>
            <input value=<?= $_SESSION['user_password'] ?> type="password" name="password" id="password" placeholder="Password">
            <h3 class="message"></h3>
            <button onclick="updateInfo()">Update</button>
        </form>
    </div>

    <div id="#items">
        
    </div>

    <script>
    async function updateInfo(){
    const form = event.target.form;
    console.log(form)
       let conn = await fetch("./apis/api-update-user.php", {
           method : "POST",
           body: new FormData(form)
       })
       let res = await conn.json()
       if (!conn.ok){
           document.querySelector(".message").textContent = res.info
       } else if (conn.ok){
            document.querySelector(".message").textContent = "User info updated!"
       }
       console.log(res)
    }
</script>
    
<?php
require_once('components/footer.php');
?>
