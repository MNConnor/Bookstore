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
                    <input type="text" id="booksearch" name="booksearch" placeholder="Book"/><br>
                    <input type="submit" name="bookSearchButton" style="background-color:green;color:white" value="Search"/>
                </p>
            </form>


        </div>

        <div class="column">
            <h2 style="text-align:center">Add Book</h2>
        </div>
    </div>
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

if(isset($_POST['bookSearchButton']))
{ //check if form was submitted
    $conn = makeDBconnection();
    $input = $_POST['booksearch']; //get input text
    $input = test_input($input); // Clean input data
    $sql = "SELECT * FROM BOOKSTORE.BOOKS WHERE Title LIKE \"%{$input}%\"";
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    global $FindBookResults;
    $FindBookResults = $result;
    ?>

                <table>
                <thead>
                    <tr>
                        <td colspan="3" style="text-align:center">Books</td>
                    </tr>
                    <tr>
                        <td>BookID</td>
                        <td>Title</td>
                        <td>AuthID</td>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = getBookResults();
                while ($row = mysqli_fetch_assoc($result))
                {
                    ?>
                    <tr>
                        <td><?php echo $row['BookID']?></td>
                        <td><?php echo $row['Title']?></td>
                        <td><?php echo $row['AuthID']?></td>
                    </tr>

                    <?php
                }
                mysqli_free_result($result);


                ?>

                </tbody>
            </table>
    <?php
}
?>

