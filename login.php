<?php
    session_start([
        'cookie_lifetime' => 30,
    ]);
    //session_destroy();

    $_SESSION['loggdin'] = $_SESSION['loggdin'] ?? false;
    $error = false;

    if(!isset($_SESSION['loggdin'])){
        $_SESSION['loggdin'] = false;
    }

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
   
    $fp = fopen("./data/user.txt","r");
    $count = 0;
    if($username && $password){
        while($data = fgetcsv($fp)){

            if($data[0] == $username && $data[1] == sha1($password)){
                $_SESSION['loggdin'] = true;
                $_SESSION['user'] = $username; 
                $_SESSION['role'] = $data[2];
                header("location:/index.php");
            }
        }
        if(!$_SESSION['loggdin']){
            $_SESSION['loggdin'] = false;
            $error = true;
            
        }
    }

    if(isset($_GET['logout'])){
        $_SESSION['loggdin'] = false;
        $_SESSION['user'] = false; 
        session_destroy();
        header("location:/index.php");        
    }


?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <?php include_once 'inc/templates/nav.php'; ?>
    <div class="container">
        <h2>Simple Auth Example</h2>
        <?php
            if(true ==  $_SESSION['loggdin']){
                echo "<p>Hello Admin, Walcome</p>";
            }else{
                echo "<p>Hello Stranger, Login Below</p>";
            }
        ?>

        <?php
            if(true == $error){
                echo "<blockquote>Username and Password didn't match</blockquote>";
            }
            if(false == $_SESSION['loggdin']):
        ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
            </div>
            <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
            </div>
            <button type="submit" name='submit' class="btn btn-default">Submit</button>
        </form>

        <?php else: ?>
        <form method="POST">
            <input type="hidden" name="logout" value="1">
            <button type="submit" name='submit' class="btn btn-default">Log Out</button>
        </form>
        <?php endif; ?>
    </div>
</body>

</html>