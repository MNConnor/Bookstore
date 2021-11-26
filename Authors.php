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

    <h1 style="text-align:center">Authors Manager</h1>

    <hr><br>

    <link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="row">
    <div class="column" style="text-align: center">
        <h2 style="text-align:center">Find Author</h2>
        <form action="" method="POST" style="align-content: center">
            <p style="font-size:200%;">
                <input type="text" id="authorsearch" name="authorsearch" placeholder="Enter Author Name"/><br>
                <input type="submit" name="authorSearchButton" style="background-color:green;color:white" value="Search"/>
            </p>
        </form>
        <?php
        if(isset($_POST['authorSearchButton']))
        {
            getAuthorsTable();
        }
        if(array_key_exists('editAuthor_ID',$_POST))
        {
            editAuthorInfo($_POST['editAuthor_ID']);
        }
        ?>
    </div>

    <div class="column">
        <h2 style="text-align:center">Add Author</h2>
        <form method="POST" action="" id="addauthorform"></form>
        <table>
            <tr>
                <td><input type="text" name="AuthIDValue" form="addauthorform" placeholder="Author ID" required></td>
                <td><input type="text" name="FirstNameValue" form="addauthorform" placeholder="First Name" required></td>
                <td><input type="text" name="LastNameValue" form="addauthorform" placeholder="Last Name" required></td>
            </tr>

            <tr>
                <td><input type="date" name="BirthdayValue" form="addauthorform" required/></td>
                <td><input type="text" name="CountryValue" form="addauthorform" placeholder="Country" required></td>
                <td><input type="text" name="WritingValue" form="addauthorform" placeholder="Writing Hours" required></td>
            </tr>

            <tr>
                <td colspan="3"><input type="submit" name="addauthorSubmitButton" form="addauthorform" value="Add"></td>
            </tr>

        </table>
        <?php
        if(isset($_POST['addauthorSubmitButton']))
        {

            $conn = makeDBconnection();
            $sql = "INSERT INTO BOOKSTORE.AUTHOR (AuthID, First_Name, Last_Name, Birthday, Country_of_Residence, Hrs_Writing_per_Day) VALUES (\"{$_POST['AuthIDValue']}\", \"{$_POST['FirstNameValue']}\", \"{$_POST['LastNameValue']}\", \"{$_POST['BirthdayValue']}\", \"{$_POST['CountryValue']}\", \"{$_POST['WritingValue']}\")";
            mysqli_query($conn, $sql) or die(mysqli_error($conn));
            echo("<p>{$_POST['FirstNameValue']} {$_POST['LastNameValue']}  has been added</p>");
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

function getAuthorsTable()
{ //check if form was submitted
    $conn = makeDBconnection();
    $input = $_POST['authorsearch']; //get input text
    $input = test_input($input); // Clean input data
    $sql = "SELECT First_Name, Last_Name, AuthID FROM BOOKSTORE.AUTHOR WHERE First_Name LIKE \"%{$input}%\" OR Last_Name LIKE \"%{$input}%\"";
    mysqli_query($conn, $sql);
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    ?>

    <table>
        <thead>
        <tr>
            <td colspan="2" style="text-align:center">Authors</td>
        </tr>
        <tr>
            <td>Edit</td>
            <td>Author</td>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result))
        {
            ?>
            <tr>
                <td>
                    <!--                            EDIT BUTTON-->
                    <form method="post">
                        <input type="submit" name="editAuthor" id="editBook" value="Edit"/>
                        <input type="hidden" name="editAuthor_ID" value="<?php echo $row['AuthID']; ?>"/>
                    </form>
                </td>
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

function editAuthorInfo($AuthID)
{
    $conn = makeDBconnection();
    $sql = "SELECT * FROM BOOKSTORE.AUTHOR WHERE Author.AuthID = \"{$AuthID}\"";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($result);
    ?>
    <form method="POST" action="" id="my_form"></form>
    <table>
        <td colspan="3" style="text-align:center">Author Details</td>
        </tr>
        <tr>
            <td>Author ID</td>
            <td>First Name</td>
            <td>Last Name</td>
        </tr>
        <tr>
            <td>
                <input type="text" name="AuthIDValue" form="my_form" value="<?php echo $row['AuthID'];?>" />
            </td>
            <td>
                <input type="text" name="FirstNamevalue" form="my_form" value="<?php echo $row['First_Name'];?>" />
            </td>
            <td>
                <input type="text" name="LastNameValue" form="my_form" value="<?php echo $row['Last_Name'];?>" />
            </td>
        </tr>
        <tr>
            <td>Birthday</td>
            <td>Country</td>
            <td>Writing Hours</td>
        </tr>
        <tr>
            <td>
                <?php
                $valid_date = date( 'Y-m-d', strtotime($row['Birthday']));
                ?>
                <input type="date" name="BirthdayValue" form="my_form", value="<?php echo $valid_date;?>"/>
            </td>
            <td>
                <input type="text" name="CountryValue" form="my_form" value="<?php echo $row['Country_of_Residence'];?>" />
            </td>
            <td>
                <input type="text" name="WritingValue" form="my_form" value="<?php echo $row['Hrs_Writing_per_Day'];?>" />
            </td>
        </tr>
    </table>
    <input type="submit" name="editAuthorSubmitButton" form="my_form" value="Submit"/>
    <div id='map' style='width: 400px; height: 300px;'></div>
    <?php
}

if(isset($_POST['editAuthorSubmitButton']))
{
    $conn = makeDBconnection();
    $sql = "UPDATE BOOKSTORE.AUTHOR SET AuthID=\"{$_POST['AuthIDValue']}\", First_Name=\"{$_POST['FirstNamevalue']}\", Last_Name=\"{$_POST['LastNameValue']}\", Birthday=\"{$_POST['BirthdayValue']}\", Country_of_Residence=\"{$_POST['CountryValue']}\", Hrs_Writing_per_Day=\"{$_POST['WritingValue']}\" WHERE AUTHOR.AuthID = \"{$_POST['AuthIDValue']}\"";
    mysqli_query($conn, $sql) or die(mysqli_error($conn));
    echo("<p>{$_POST['FirstNamevalue']} {$_POST['LastNameValue']}  has been updated</p>");
}
?>

