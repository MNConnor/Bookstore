<!DOCTYPE html>
<html>
    <head>
        <h1>Employee Login Page</h1>
    </head>

    <body>
    <?php
    session_start();

    // If we got names, use them.
    $fnamei = "Unknown";
    $lnamei = "Unknown";
    if (isset($_POST["user"])) { $uname = $_POST["user"]; }
    if (isset($_POST["pass"])) { $pw = $_POST["pass"]; }

    // Validate both names.
    $uname = test_input($uname);
    $pw = test_input($pw);


    if(($uname == '') && ($pw == ''))
    {
        $_SESSION["Login.Error"] = 'Please enter your credentials';
        header("location:login.php"); //redirect back to login
        exit();
    }
    elseif ($uname == '')
    {
        $_SESSION["Login.Error"] = 'Please enter a username';
        header("location:login.php"); //redirect back to login
        exit();
    }
    elseif ($pw == '')
    {
        $_SESSION["Login.Error"] = 'Please enter a password';
        header("location:login.php"); //redirect back to login
        exit();
    }

    // Create a connection to the database server.
    // Go get the User name and password for the MySQL access.
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

    // Create a string representing our query.
    // Then execute the query and get back our result set.
    $sql = "SELECT * FROM bookstore.login " .
        "WHERE UserID = '" . $uname . "' AND " .
        "Password = '" . $pw . "'";

    echo "Attempting query:<h4> " . $sql . "</h4>";

    mysqli_select_db($conn, "store");
    $role = '';
    $userID = '';
    if ($result = mysqli_query($conn, $sql))
    {
        while ($row = mysqli_fetch_assoc($result))
        {
            printf("<h3>%s is their role.</h3>",
                    $row['Role']);
            $role = $row['Role'];
            $userID = $row['userID'];
        }
        // Free result set
        mysqli_free_result($result);

        if($role == '')
        {
            $_SESSION["Login.Error"] = 'Incorrect Username or Password<BR>';
            header("location:login.php"); //redirect back to login
            exit();
        }
        elseif ($role != 'Admin')
        {
            $_SESSION["Login.Error"] = 'Sorry, this page is for Employees only<BR>';
            header("location:login.php"); //redirect back to login
            exit();
        }
        else
        {
            $_SESSION["user_role"] = 'Admin';
            $_SESSION["user_ID"] = $userID;
            header("location:EmployeePage.php"); //redirect to to Employee Page
        }
    }


    // validate input data.
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function getUser()
    {
        $myfile = fopen("DB_USER.txt", "r") or die("Unable to open user file!");
        $file_input = fread($myfile, filesize("DB_USER.txt"));
        // https://www.php.net/manual/en/function.explode.php
        $user_pw = explode(" ", $file_input);
        fclose($myfile);
        return $user_pw;
    }
    ?>

    </body>
</html>