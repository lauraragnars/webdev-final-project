<?php
    session_start();

    if( ! isset($_SESSION['user_name'])){
        header('Location: index');
        exit();
    };

    $_title = 'Home';

    require_once(__DIR__.'/private/tsv-parser.php');
    require_once(__DIR__.'/components/header.php');
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

        <label for="image">Item image name</label>
        <input type="text" id="image" name="item_image">
        <button onclick="uploadItem()">Upload item</button>
    </form>
    <div id="items">
        <?php

            $data = json_decode(file_get_contents("shop.txt"));

            foreach($data as $item){

                $title = isset($item->title) ? $item->title : $item->title_en;
                $price = isset($item->price) ? $item->price : $item->price_dk;
                // $desc = isset($item->desc) ? $item->desc : $item->desc_en;
                // $desc = $desc ? $item->description : $desc;

                echo "<div class='item' data-id='{$item->id}'>
                        <div class='item-image'>
                            <img src='https://coderspage.com/2021-F-Web-Dev-Images/{$item->image}' />
                        </div>
                        <div class='item-text'>
                            <div>{$title}</div>
                            <div class='text-price'>{$price} $</div>
                        </div>
                    </div>";
            }
            ?>
    </div>
</div>

<script>
    getItems()
        async function getItems(){

            const conn = await fetch("apis/api-items", {
                method: "POST"
            })
            const res = await conn.json()
            console.log(res, "items res")
            if(conn.ok){
                res.forEach((item) =>(
                    document.querySelector("#items").insertAdjacentHTML("afterbegin", 
                `<div class="item" data-id="${item.item_id}">
                    <div class='item-image'>
                        <img src='https://coderspage.com/2021-F-Web-Dev-Images/${item.item_image}' />
                    </div>
                    <div class='item-text'>
                        <div>${item.item_name}</div>
                        <div>${item.item_description}</div>
                        <div>${item.item_price}</div>
                        <div onclick="deleteItem()">🗑️</div>
                    </div>
                </div>`)
                ))
                
            }
        }

        async function updateItem(){
            // post to update api
            // get values from update form
            // conn ok ? call get Items function again - cleara items fyrst - setja personal items í sér div 
        }

        async function uploadItem(){
            const form = event.target.form;
            const itemName = document.querySelector("#name").value
            const itemDesc = document.querySelector("#desc").value
            const itemPrice = document.querySelector("#price").value
            const itemImage = document.querySelector("#image").value

            const conn = await fetch("apis/api-upload-item", {
                method: "POST",
                body: new FormData(form)
            })
            const res = await conn.text()
            
            if(conn.ok){
                document.querySelector("#items").insertAdjacentHTML("afterbegin", 
                `<div class="item" data-id="${res}">
                    <div class='item-image'>
                        <img src='https://coderspage.com/2021-F-Web-Dev-Images/${itemImage}' />
                    </div>
                    <div class='item-text'>
                        <div>${itemName}</div>
                        <div>${itemDesc}</div>
                        <div>${itemPrice}</div>
                        <div onclick="deleteItem()">🗑️</div>
                    </div>
                </div>`)
            }
        }

        async function deleteItem(){
            const item = event.target.parentNode.parentNode
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
require_once(__DIR__.'/components/footer.php');
?>