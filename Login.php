<!DOCTYPE html>

<html>
<body>
<link rel="stylesheet" href="styles.css">
<h1 id="loginheader">Employee Login Page</h1>
<hr>
<div id="LoginBox">
    <p style="color: red"><b>
    <?php
    session_start();
    unset($_SESSION['user_role']);
    if(isset($_SESSION['Login.Error']))
    {
        echo $_SESSION['Login.Error'];
        unset($_SESSION['Login.Error']);
    }
    ?>
    </b></p>

    <form action="EmployeeLogin.php" method="POST">
        <p style="font-size:200%;">
            <input type="text" id="usernamebox" name="user" placeholder="Username"/><br>
            <input type="password" id="passwordbox" name="pass" placeholder="Password"/><br>
            <input type="submit" value="Submit"/>
        </p>
    </form>
    <hr><br>
    <form method="post">
        <input type="submit" name="getRandomUserButton" id="getRandomUserButton" value="Random User"/><br/>
    </form>
</div>

</body>

<?php
function getRandomUser()
{
    $db_login = getUser();

    $dbhost = "localhost:3306";
    $dbuser = $db_login[0];
    $dbpass = $db_login[1];
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
    if(! $conn )
    {
        echo "Error: Unable to connect to MySQL." . "<br>\n";
        echo "Debugging error: " . mysqli_connect_errno() . "<br>\n";
        echo "Debugging error: " . mysqli_connect_error() . "<br>\n";
        die("Could not connect: " . mysqli_error());
    }

    $sql = "SELECT UserID, Password from bookstore.login ORDER BY RAND() LIMIT 1";;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
?>
    <script type="text/javascript">
        var usernamebox = document.getElementById("usernamebox");
        var passwordbox = document.getElementById("passwordbox");
        usernamebox.value = "<?php echo $row['UserID'];?>";
        passwordbox.value = "<?php echo $row['Password'];?>";
    </script>
<?php

}


if(array_key_exists('getRandomUserButton',$_POST))
{
    getRandomUser();
}

function getUser()
{
    $myfile = fopen("DB_USER.txt", "r") or die("Unable to open user file!");
    $file_input = fread($myfile, filesize("DB_USER.txt"));
    // https://www.php.net/manual/en/function.explode.php
    $user_pw = explode(" ", $file_input);
    // echo "<p>From DB_USER.txt: User name = " . $user_pw[0] . ", Password  = " . $user_pw[1];
    fclose($myfile);
    return $user_pw;
}
?>
</html>