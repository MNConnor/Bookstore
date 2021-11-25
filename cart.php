<html lang="en">
<head>
  <title>Cart</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Bookstore</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="CustomerPage.php">Home</a></li>
      <li class="active"><a href="cart.php">Cart</a></li>
      <li><a href="wishlist.php">Wishlist</a></li>
        <li><a href="login.php">Logout</a></li>
    </ul>
  </div>
</nav>

<form name="Cart" method="post" action="cart.php">
    <input name="cCart" type="submit" value="Your Cart">
</form>
<link rel="stylesheet" href="styles.css">
</body>
</html>
<?php
$FindCartResults = null;

function setCartResults($results)
{
    global $FindCartResults;
    $FindCartResults = $results;
}

function getCartResults()
{
    global $FindCartResults;
    return $FindCartResults;
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

if(isset($_POST['cCart']))
{
    $conn = makeDBconnection();
    $sql = "SELECT distinct B.Title, A.First_Name, A.Last_Name, E.Price" .
        "FROM bookstore.books AS B " .
        "JOIN bookstore.author AS A ON A.AuthID = B.AuthID ".
        "JOIN bookstore.edition as E ON B.BookID = E.BookID".
        "JOIN bookstore.cart as C ON B.BookID = C.BookID".
        "WHERE C.UserID = 'Customer1'" ;
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    //global $FindRentalResults;
    //$FindRentalResults = $result;
    ?>
    <table>
        <thead>
        <tr>
            <td colspan="4" style="text-align: center">Cart</td>
        </tr>
        <tr>
            <td>Remove</td>
            <td>Title</td>
            <td>Author</td>
            <td>Price</td>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_accos($result))
        {
            ?>
            <tr>
                <td>
                    <!--Remove from cart button -->
                    <form method="post">
                        <input type="submit" name="remove" id="remove" value="Delete"/>
                    </form>
                </td>
                <td><?php echo $row['Title'] ?></td>
                <td><?php echo $row['First_Name'] . " " . $row['Last_Name']?></td>
                <td><?php echo $row['Price'] ?></td>
            </tr>
            <?php
        }
        mysqli_free_result($result);
        ?>
        </tbody>
    </table>
    <?php
}
if(isset($_POST['remove']))
{
    echo("<p>{$_POST['cCart']} has been updated</p>");
    $conn = makeDBconnection();
    $sql = "DELETE FROM bookstore.cart WHERE UserID= 'Customer1' AND BookID= \"{$_POST['BookIDValue']}\")";
    mysqli_query($conn, $sql);
}
?>