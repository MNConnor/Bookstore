<!DOCTYPE html>
<html>
	<head>
		<title>book page</title>
	</head>
<body>
<?php
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
	
	//if user selects book- query book information and post to new page
	
	
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
	
	//create query for each information on ? book
	
?>
      <p class="lead" style="margin: 25px 0"><a href="books.php">Books</a> > <?php echo $row['book_title']; ?></p>
      <div class="row">
        <div class="col-md-3 text-center">
          <img class="img-responsive img-thumbnail" src="./bootstrap/img/<?php echo $row['book_image']; ?>">
        </div>
        <div class="col-md-6">
          <h4>Book Description</h4>
          <p><?php echo $row['book_descr']; ?></p>
          <h4>Book Details</h4>
          <table class="table">
          	<?php foreach($row as $key => $value){
              if($key == "book_descr" || $key == "book_image" || $key == "publisherid" || $key == "book_title"){
                continue;
              }
              switch($key){
                case "book_isbn":
                  $key = "ISBN";
                  break;
                case "book_title":
                  $key = "Title";
                  break;
                case "book_author":
                  $key = "Author";
                  break;
                case "book_price":
                  $key = "Price";
                  break;
              }
            ?>
            <tr>
              <td><?php echo $key; ?></td>
              <td><?php echo $value; ?></td>
            </tr>
            <?php 
              } 
              if(isset($conn)) {mysqli_close($conn); }
            ?>
          </table>
          <form method="post" action="cart.php">
            <input type="hidden" name="bookisbn" value="<?php echo $book_isbn;?>">
            <input type="submit" value="Purchase / Add to cart" name="cart" class="btn btn-primary">
          </form>
       	</div>
      </div>