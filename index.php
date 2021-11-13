<!DOCTYPE html>
<html>
	<head>
		<title>Homepage</title>
	</head>
<body>
<?php
	/*
	//basis session info for each page
	session_start();
	$role= $_SESSION['Role'];
	$uname= $_SESSION['UserID'];
	if ($role != 'Customer') 
	{
		if ($role == '')
			echo 'I am sorry but you need to make an account to access this page.<BR>';
		else
			echo "You are known as $Role / $UserID, so you do not have rights to this page.<BR>";
		return;
	}
		//connect to database for books
	$dbhost = "localhost:3306";
	$dbuser = $_SESSION['USER_SELECT_BL'];
	$dbpass = $_SESSION['PW_SELECT_BL'];
	$name_root = $_SESSION['USER_ROOT']; //db root user
	$pw_root = $_SESSION['PW_ROOT']; //db root password
		
	$conn = new mysqli($dbhost, $dbuser, $dbpass, "bookstore");
	if(! $conn) //error handling if not able to connect
	{
		echo "Error: Unable to connect to Mysql." . "<BR>\n";
		echo "Debugging errno: " . mysqli_connect_errno() . "<BR>\n";
		echo "Debugging error: " . mysqli_connect_error() . "<BR>\n";
		die("Count not connect: " . mysqli_error());
	}
	*/
?>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
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
      <li class="active"><a href="#">Home</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li><a href="wishlist.php">Wishlist</a></li>
      <li><a href="orders.php">Orders</a></li>
    </ul>
  </div>
</nav>
	
<div class= "container">
	<h3> Popular Titles </h3>
	<?php
		/*
		// connecting to database for most popular books
		session_start();
		
		$role= $_SESSION['Role'];
		$username = $_SESSION['UserID'];
		if (($role != 'customer')) {
			if($role == '')
				echo 'I am sorry but you do not have access to this page.<BR>';
				return;
		}
		
		//connect to db
		$dbhost = "localhost:3306";
		$dbuser = $_SESSION['USER_SELECT_Bookstore'];
		$dbpass = $_SESSION['PW_SELECT_Bookstore'];
		$db_root = $_SESSION['USER_ROOT'];
		$db_pw = $_SESSION['PW_ROOT'];
		
		//connection + error handling if not able to connect to db
		$conn = new mysqli($dbhost, $dbuser, $dbpass);
		if (! $conn) {
			echo "Error: Unable to conenct to mysql." . "<BR>\n";
			echo "Debugging errno: " . mysqli_connect_errno() . "<BR>\n";
			echo "Debugging error: " . mysqli_connect_error() . "<BR>\n";
			die ("Could not connect: " . mysqli_error());
		}
		
		//query string, select most popular books
		$sql = "SELECT distinct B.Title, A.First_Name, A.Last_Name " .
			   "FROM bookstore.books AS B " .
			   "JOIN bookstore.author AS A ON A.AuthID = B.AuthID " .
			   "JOIN bookstore.checkouts as C ON B.BookID = C.BookID " .
			   "WHERE C.Number_of_Checkouts > 50";
		mysqli_select_db($conn, "bookstore");
		//echo $uname; 
		if ($result = mysqli_query($conn, $sql))
		{			
			
			echo "<body style= background:rgb(255,182,193)>";
			echo "<table border = \"1\"; style= 'position:relative; background:#FFFFFF; max-width: 360px; margin:0 auto 100px; padding: 45px; box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);'> >";
			if($row = mysqli_fetch_row($result))
			{	
				printf( "<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>" ,
					$row[0], $row[1], $row[2], $row[3], $row[4]);
			}
			//mysqli_free_result($result);
		}
		*/
	?>
</div>
	<div class ="row">
		<?php foreach ($row as $book){ ?>
			<div class="colm">
				<a href="book.php?bookisbn= <?php echo $book['bookisbn']; ?> ">
				</a>
			</div>
		<?php } ?>	
	</div>
</body>
</html>

	