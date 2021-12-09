<?php
    session_start();
    $_title = 'Home';
    require_once('components/header.php');
?>

<nav>
    <a href="logout">Logout</a>
    <a href="profile">View profile</a>
</nav>

<div class="container">
    <h1>
        Welcome, <?php echo $_SESSION['user_name']; ?>
    </h1>
</div>

<form onsubmit="return false">
    <input type="text" id="name" name="item_name" data-validate="str" data-min="2" data-max="20">
    <button onclick="uploadItem()">Upload item</button>
</form>

<div id="items"></div>

<script>
        async function uploadItem(){
            const form = event.target.form;
            const itemName = document.querySelector("#name", form).value
            const conn = await fetch("apis/api-upload-item", {
                method: "POST",
                body: new FormData(form)
            })
            const res = await conn.text()
            if(conn.ok){
                document.querySelector("#items").insertAdjacentHTML("afterbegin", 
                `<div class="item">
                    <div class="id" data-value="${res}" >${res}</div>
                    <div>${itemName}</div>
                    <div onclick="deleteItem()">🗑️</div>
                </div>`)
            }
        }

        async function deleteItem(){
            const item = event.target.parentNode
            const id = document.querySelector(".id", item).getAttribute('data-value')
            let formData = new FormData();
            formData.append('item_id', id);

            const conn = await fetch("apis/api-delete-item", {
                method: "POST",
                body: formData
            })
            const res = await conn.text()
            item.remove();
        }
    </script>
<?php
require_once('components/footer.php');
?>