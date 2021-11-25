<?php

    session_start();

    if ($_SESSION["user_role"] != 'Customer')
    {
        $_SESSION["Login.Error"] = 'Please sign in<BR>';
        header("location:login.php");
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Orders</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Bookstore</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="CustomerPage.php">Home</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="wishlist.php">Wishlist</a></li>
      <li class="active"><a href="rentals.php">Rentals</a></li>
    </ul>
  </div>
</nav>

<form name="Rentals" method="post" action="rentals.php">
    <input name="cRental" type="submit" value="Your Rentals">
</form>



</body>
</html>

<?php
$FindRentalResults = null;

function setRentalResults($results)
{
    global $FindRentalResults;
    $FindRentalResults = $results;
}

function getRentalResults()
{
    global $FindRentalResults;
    return $FindRentalResults;
}

function makeDBconnection()
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
    return $conn;
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

if(isset($_POST['cRental']))
{
    $conn = makeDBconnection();
    $sql = "";
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    global $FindRentalResults;
    $FindRentalResults = $result;
}
?>