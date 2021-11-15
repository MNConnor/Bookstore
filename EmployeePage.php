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

        <div style="float:right">
            <form align="right" name="logoutform" method="post" action="Login.php">
                <input name="submit" type="submit" value="Log Out">
            </form>
        </div>

        <h1 style="text-align:center">Employee Dashboard</h1>

        <hr><br>

        <link rel="stylesheet" href="styles.css">
    </head>

    <body>


    <form align="right" name="books" method="post" action="Books.php">
        <input name="submit" type="submit" value="Books">
    </form>

    <form align="right" name="Sales" method="post" action="Sales.php">
        <input name="submit" type="submit" value="Sales">
    </form>

    <form align="right" name="rentals" method="post" action="Rentals.php">
        <input name="submit" type="submit" value="Rentals">
    </form>

    <form align="right" name="Customers" method="post" action="Customers.php">
        <input name="submit" type="submit" value="Customers">
    </form>


    </body>
</html>

<?php
function getUser()
{
    $myfile = fopen("DB_USER.txt", "r") or die("Unable to open user file!");
    $file_input = fread($myfile, filesize("DB_USER.txt"));
    $user_pw = explode(" ", $file_input);
    fclose($myfile);
    return $user_pw;
}
?>