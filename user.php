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

    <div id="#items">
        
    </div>
    
<?php
require_once('components/footer.php');
?>
