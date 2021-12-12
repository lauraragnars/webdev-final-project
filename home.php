<?php
    session_start();
    if( ! isset($_SESSION['user_name'])){
        header('Location: index');
        exit();
    };
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

        <?php

        $data = json_decode(file_get_contents("shop.txt"));

        foreach($data as $item){
            echo "<div>{$item->id}</div>";
            echo "<div>{$item->title}</div>";
            echo "<img src='https://coderspage.com/2021-F-Web-Dev-Images/{$item->image}' />";
        }
        ?>
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