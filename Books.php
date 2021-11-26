<?php
session_start();
// Verify whoever is on this page is an employee
if($_SESSION["user_role"] != 'Admin')
{
    $_SESSION["Login.Error"] = 'Please sign in<BR>';
    header("location:login.php"); //redirect back to login
}
?>

<!DOCTYPE html>
<html>
<head>
<!--    LOGOUT BUTTON-->
    <div style="float:right">
        <form align="right" name="logoutform" method="post" action="Login.php">
            <input name="submit" type="submit" value="Log Out">
        </form>
    </div>

<!--    BACK TO MENU BUTTON-->
    <div style="float:left">
        <form align="left" name="mainmenu" method="post" action="EmployeePage.php">
            <input name="submit" type="submit" value="Menu">
        </form>
    </div>

    <h1 style="text-align:center">Books Manager</h1>

    <hr><br>

    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="row">
        <div class="column" style="text-align: center">
            <h2 style="text-align:center">Find Book</h2>
            <form action="" method="POST" style="align-content: center">
                <p style="font-size:200%;">
                    <input type="text" id="booksearch" name="booksearch" placeholder="Enter Book Title or Author Name"/><br>
                    <input type="submit" name="bookSearchButton" style="background-color:green;color:white" value="Search"/>
                </p>
            </form>
            <?php
            if(isset($_POST['bookSearchButton']))
            {
                getBooksTable();
            }
            if(array_key_exists('editBook_ID',$_POST))
            {
                editBookInfo($_POST['editBook_ID']);
            }
                ?>
        </div>

        <div class="column">
            <h2 style="text-align:center">Add Book</h2>
            <form method="POST" action="" id="addbookform"></form>
            <table>
                <tr>
                    <td><input type="text" name="BookIDValue" form="addbookform" placeholder="Book ID" required></td>
                    <td><input type="text" name="TitleValue" form="addbookform" placeholder="Title" required></td>

                    <td style>
                        <select name="AuthIDValue" id="AuthIDValue" form="addbookform" required>
                            <option value="" disabled selected>Select Author</option>
                            <?php
                            $conn = makeDBconnection();
                            $sql = "SELECT AuthID, First_Name, Last_Name FROM BOOKSTORE.AUTHOR";
                            mysqli_query($conn, $sql);
                            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                ?>
                                <option name="AuthIDValue" value=<?php echo $row['AuthID'];?>> <?php echo $row['First_Name'] . " " . $row['Last_Name'];?> </option>
                                <?php
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="FormatValue" form="addbookform" placeholder="Format" required></td>
                    <td><input type="text" name="PriceValue" form="addbookform" placeholder="Price" required></td>
                    <td><input type="submit" name="addBooksSubmitButton" form="addbookform" value="Add" required></td>
                </tr>

            </table>
            <?php
            if(isset($_POST['addBooksSubmitButton']))
            {

                $conn = makeDBconnection();
                $sql = "INSERT INTO BOOKSTORE.BOOKS (BookID, Title, AuthID) VALUES (\"{$_POST['BookIDValue']}\", \"{$_POST['TitleValue']}\", \"{$_POST['AuthIDValue']}\"); ";
                mysqli_query($conn, $sql) or die("<p>Error adding {$_POST['TitleValue']}");
                $sql = "INSERT INTO BOOKSTORE.EDITION (BookID, Format, Price) VALUES (\"{$_POST['BookIDValue']}\", \"{$_POST['FormatValue']}\", \"{$_POST['PriceValue']}\"); ";
                mysqli_query($conn, $sql) or die("<p>Error adding {$_POST['TitleValue']}");
                echo("<p>{$_POST['TitleValue']} has been added</p>");
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
function makeDBconnection()
{
    // Create a connection to the database server.
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

// validate input data.
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getBooksTable()
{ //check if form was submitted
    $conn = makeDBconnection();
    $input = $_POST['booksearch']; //get input text
    $input = test_input($input); // Clean input data
    $sql = "SELECT BOOKS.BookID, Books.Title, Author.First_Name, Author.Last_Name FROM BOOKSTORE.BOOKS JOIN BOOKSTORE.AUTHOR ON BOOKS.AuthID = Author.AuthID WHERE Books.Title LIKE \"%{$input}%\" OR Author.First_Name LIKE \"%{$input}%\" OR Author.Last_Name LIKE \"%{$input}%\"";
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
//    global $FindBookResults;
//    $FindBookResults = $result;
    ?>

                <table>
                <thead>
                    <tr>
                        <td colspan="3" style="text-align:center">Books</td>
                    </tr>
                    <tr>
                        <td>Edit</td>
                        <td>Title</td>
                        <td>Author</td>
                    </tr>
                </thead>
                <tbody>
                <?php
//                $result = getBookResults();
                while ($row = mysqli_fetch_assoc($result))
                {
                    ?>
                    <tr>
                        <td>
<!--                            EDIT BUTTON-->
                            <form method="post">
                                <input type="submit" name="editBook" id="editBook" value="Edit"/>
                                <input type="hidden" name="editBook_ID" value="<?php echo $row['BookID']; ?>"/>
                            </form>
                        </td>
                        <td><?php echo $row['Title']?></td>
                        <td><?php echo $row['First_Name'] . " " . $row['Last_Name']?></td>
                    </tr>

                    <?php
                }
                mysqli_free_result($result);


                ?>

                </tbody>
            </table>
    <?php
}

function editBookInfo($BookID)
{
    $conn = makeDBconnection();
    $sql = "SELECT * FROM BOOKSTORE.BOOKS JOIN BOOKSTORE.AUTHOR ON BOOKS.AuthID = Author.AuthID JOIN BOOKSTORE.EDITION ON Books.BookID = edition.BookID WHERE Books.BookID = \"{$BookID}\";";
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);
    ?>
    <form method="POST" action="" id="my_form"></form>
    <table>
        <td colspan="3" style="text-align:center">Book Details</td>
        </tr>
        <tr>
            <td>Book ID</td>
            <td>Title</td>
            <td>Author</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="BookIDValue" form="my_form" value="<?php echo $row['BookID'];?>" />
            </td>
            <td>
                <input type="text" name="TitleValue" form="my_form" value="<?php echo $row['Title'];?>" />
            </td>
            <td>
                <select name="AuthIDValue" id="AuthIDValue" form="my_form">
                    <option selected name="AuthIDValue" value=<?php echo $row['AuthID'];?>> <?php echo $row['First_Name'] . " " . $row['Last_Name'];?> </option>
                    <?php
                    mysqli_free_result($result);
                    $sql = "SELECT AuthID, First_Name, Last_Name FROM BOOKSTORE.AUTHOR";
                    mysqli_query($conn, $sql);
                    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                    while ($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <option name="AuthIDValue" value=<?php echo $row['AuthID'];?>> <?php echo $row['First_Name'] . " " . $row['Last_Name'];?> </option>
                        <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center">Pricing</td>
        </tr>
        <?php
        $sql = "SELECT * FROM BOOKSTORE.BOOKS JOIN BOOKSTORE.AUTHOR ON BOOKS.AuthID = Author.AuthID JOIN BOOKSTORE.EDITION ON Books.BookID = edition.BookID WHERE Books.BookID = \"{$BookID}\";";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            while ($row = mysqli_fetch_assoc($result))
            {
            ?>
            <tr>
                <td colspan="2"><?php echo $row['Format'];?></td>
                <td>
                    <input type="text" name="<?php echo explode(" ", $row['Format'])[0];?>" form="my_form" value="<?php echo $row['Price'];?>" />
                </td>
            </tr>
        <?php
            }

        ?>
    </table>
    <input type="submit" name="editBooksSubmitButton" form="my_form" value="Submit"/>
    <div id='map' style='width: 400px; height: 300px;'></div>
    <?php
}

if(isset($_POST['editBooksSubmitButton']))
{
    echo("<p>{$_POST['TitleValue']} has been updated</p>");
    $conn = makeDBconnection();
    $sql = "UPDATE BOOKSTORE.BOOKS SET BookID=\"{$_POST['BookIDValue']}\", Title=\"{$_POST['TitleValue']}\", AuthID=\"{$_POST['AuthIDValue']}\" WHERE Books.BookID = \"{$_POST['BookIDValue']}\"";
    mysqli_query($conn, $sql);

    $sql = "SELECT * FROM BOOKSTORE.BOOKS JOIN BOOKSTORE.AUTHOR ON BOOKS.AuthID = Author.AuthID JOIN BOOKSTORE.EDITION ON Books.BookID = edition.BookID WHERE Books.BookID = \"{$_POST['BookIDValue']}\";";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($result))
    {
        $sql = "UPDATE BOOKSTORE.EDITION SET PRICE = \"{$_POST[explode(" ", $row['Format'])[0]]}\" WHERE BOOKID = \"{$_POST['BookIDValue']}\" AND FORMAT = \"{$row['Format']}\"";
        mysqli_query($conn, $sql);
    }
}
    ?>

