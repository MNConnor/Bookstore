
<?php

session_start();
// Verify whoever is on this page is a Customer
if($_SESSION["user_role"] != 'Customer')
{
    $_SESSION["Login.Error"] = 'Please sign in<BR>';
    header("location:login.php"); //redirect back to login
}

	
?>
<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
</head>
<body>
<head>
  <title>Bookstore</title>
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
      <li class="active"><a href="#">Home</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="wishlist.php">Wishlist</a></li>
      <li><a href="login.php">Logout</a></li>
    </ul>
	
  </div>
</nav>

<div class="row">
    <form action="" method="POST" style="align-content: center; padding: 50px">
        <label for="bookSearch"></label><input type="text" id="bookSearch" name="bookSearch" placeholder="Enter Book Title">
            <input type="submit" name="bookSearchButton" style="background-color:green;color:white" value="Search"/>
    </form>
    <?php
    if(isset($_POST['bookSearchButton']))
    {
        getSearchBook();
    }
    ?>
	<form action= " " method= "POST" style="align-content: center">
		<input type="submit" name= "cbestButton" value="Best Sellers">
	</form>
    <form action= " " method= "POST" style="align-content: center">
        <input type="submit" name="cAllbooksbutton" value="All Books">
	</form>
</div>
<link rel="stylesheet" href="styles.css">
</body>
</html>

<?php

$FindBookResults = null;

function setBookResults($result)
{
    global $FindBookResults;
    $FindBookResults = $result;
}

function getBookResults()
{
    global $FindBookResults;
    return $FindBookResults;
}

//create connection to db server
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
    $user_pw = explode(" ", $file_input);
    fclose($myfile);
    return $user_pw;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getSearchBook()
{
    $conn = makeDBconnection();
    $input = $_POST['bookSearch'];
    $input = test_input($input);
    $sqlSearch = "SELECT BOOKS.BookID, Books.Title, Author.First_Name, Author.Last_Name, Edition.Price FROM BOOKSTORE.BOOKS JOIN BOOKSTORE.AUTHOR ON BOOKS.AuthID = Author.AuthID JOIN BOOKSTORE.EDITION ON BOOKS.BookID = Edition.BookID WHERE Books.Title LIKE \"%{$input}%\" OR Author.First_Name LIKE \"%{$input}%\" OR Author.Last_Name LIKE \"%{$input}%\"";
    mysqli_query($conn, $sqlSearch);
    $result = mysqli_query($conn, $sqlSearch) or die(mysqli_error($conn))
    ?>
        <table>
            <thead>
                <tr>
                    <td colspan="4" style="text-align: center">Searched Books</td>
                </tr>
                <tr>
                    <td>Add</td>
                    <td>Title</td>
                    <td>Author</td>
                    <td>Price</td>
                </tr>
            </thead>
            <tbody>
            <?php

            while ($row = mysqli_fetch_assoc($result))
            {
                ?>
                <tr>
                    <td>
                        <!--Add to cart button -->
                        <form method="post">
                            <input type="submit" name="cCart" id="cCart" value="Add to Cart"/>
                            <input type="hidden" name="BookIDValue" id="BookIDValue" value="<?php echo $row['BookID']?>"/>
                        </form>
                        <!--Add to wishlist button -->
                        <form method="post">
                            <input type="submit" name="cWish" id="cWish" value="Add to Wishlist"/>
                            <input type="hidden" name="wBookIDValue" id="wBookIDValue" value="<?php echo $row['BookID']?>"/>
                        </form>
                    </td>
                    <td><?php echo $row['Title']?></td>
                    <td><?php echo $row['First_Name'] . " " . $row['Last_Name']?></td>
                    <td><?php echo $row['Price']?></td>
                </tr>
                <?php
            }
            mysqli_free_result($result);
            ?>
            </tbody>
        </table>
    <?php

}




if(isset($_POST['cAllbooksbutton']))
{
    $conn = makeDBconnection();
    $sql = "SELECT BOOKS.BookID, Books.Title, Author.First_Name, Author.Last_Name, edition.Price  FROM BOOKSTORE.BOOKS JOIN BOOKSTORE.AUTHOR ON BOOKS.AuthID = Author.AuthID JOIN BOOKSTORE.EDITION ON BOOKS.BookID = EDITION.BookID ";
    $sqlbooks = "SELECT B.Title, A.First_Name, A.Last_Name, E.Price" .
            "FROM bookstore.books AS B " .
            "JOIN bookstore.author AS A ON A.AuthID = B.AuthID " .
            "JOIN bookstore.edition as E ON B.BookID = E.BookID ";
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    ?>
        <table>
            <thead>
                <tr>
                    <td colspan="4" style="text-align: center">All Books</td>
                </tr>
                <tr>
                    <td>Add</td>
                    <td>Title</td>
                    <td>Author</td>
                    <td>Price</td>
                </tr>
            </thead>
            <tbody>
            <?php
            while($row = mysqli_fetch_assoc($result))
            {
                ?>
                <tr>
                    <td>
                        <!--Add to cart button -->
                        <form method="post">
                            <input type="submit" name="cCart" id="cCart" value="Add to Cart"/>
                            <input type="hidden" name="BookIDValue" id="BookIDValue" value="<?php echo $row['BookID']?>"/>
                        </form>
                        <!--Add to wishlist button -->
                        <form method="post">
                            <input type="submit" name="cWish" id="cWish" value="Add to Wishlist"/>
                            <input type="hidden" name="wBookIDValue" id="wBookIDValue" value="<?php echo $row['BookID']?>"/>
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

if(isset($_POST['cbestButton']))
{
    $conn = makeDBconnection();
    $sqlbest = "SELECT distinct B.BookID, B.Title, A.First_Name, A.Last_Name " .
        "FROM bookstore.books AS B " .
        "JOIN bookstore.author AS A ON A.AuthID = B.AuthID " .
        "JOIN bookstore.checkouts as C ON B.BookID = C.BookID " .
        "WHERE C.Number_of_Checkouts > 50";
    mysqli_query($conn, $sqlbest);
    $result = mysqli_query($conn, $sqlbest) or die(mysqli_error($conn));
    ?>
        <table>
            <thead>
                <tr>
                    <td colspan="3" style="text-align: center">Top Sellers</td>
                </tr>
                <tr>
                    <td>Add</td>
                    <td>Title</td>
                    <td>Author</td>
                </tr>
            </thead>
            <tbody>
            <?php
            while($row = mysqli_fetch_array($result))
            {
                ?>
                <tr>
                    <td>
                        <!--Add to cart button -->
                        <form method="post">
                            <input type="submit" name="cCart" id="cCart" value="Add to Cart"/>
                            <input type="hidden" name="BookIDValue" id="BookIDValue" value="<?php echo $row['BookID']?>"/>

                        </form>
                        <!--Add to wishlist button -->
                        <form method="post">
                            <input type="submit" name="cWish" id="cWish" value="Add to Wishlist"/>
                            <input type="hidden" name="wBookIDValue" id="wBookIDValue" value="<?php echo $row['BookID']?>"/>
                        </form>
                    </td>
                    <td><?php echo $row['Title'] ?></td>
                    <td><?php echo $row['First_Name'] . " ". $row['Last_Name'] ?></td>
                </tr>
                <?php
            }
            mysqli_free_result($result);
            ?>

            </tbody>
        </table>
    <?php
}
if(array_key_exists('BookIDValue',$_POST))
{
    //echo $_POST['BookIDValue'];
    echo("<p>{$_POST['cCart']} has been updated</p>");
    $conn = makeDBconnection();
    $sql = "INSERT INTO bookstore.cart VALUES ('Customer1', \"{$_POST['BookIDValue']}\"); ";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}
if(array_key_exists('wBookIDValue',$_POST))
{
    echo ("<p>{$_POST['cWish']} has been updated</p>");
    $conn = makeDBconnection();
    $sql = "INSERT INTO bookstore.wishlist VALUES ('Customer1', \"{$_POST['wBookIDValue']}\");";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
}
?>

	