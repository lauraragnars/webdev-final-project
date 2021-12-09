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
    <form onsubmit="return false">
        <label for="name">Item name</label>
        <input type="text" id="name" name="item_name">

        <label for="desc">Item description</label>
        <input type="text" id="desc" name="item_description">

        <label for="price">Item price</label>
        <input type="number" id="price" name="item_price">
        <button onclick="uploadItem()">Upload item</button>
    </form>
    <div id="items"></div>
</div>

<script>
        async function uploadItem(){
            const form = event.target.form;
            const itemName = document.querySelector("#name").value
            const itemDesc = document.querySelector("#desc").value
            const itemPrice = document.querySelector("#price").value

            const conn = await fetch("apis/api-upload-item", {
                method: "POST",
                body: new FormData(form)
            })
            const res = await conn.text()
            
            if(conn.ok){
                document.querySelector("#items").insertAdjacentHTML("afterbegin", 
                `<div class="item" data-id="${res}">
                    <div>${itemName}</div>
                    <div>${itemDesc}</div>
                    <div>${itemPrice}</div>
                    <div onclick="deleteItem()">🗑️</div>
                </div>`)
            }
        }

        async function deleteItem(){
            const item = event.target.parentNode
            const id = item.getAttribute('data-id')
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