<html lang="en">
<head>
  <title>wishlist</title>
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
      <li><a href="index.php">Home</a></li>
      <li><a href="cart.php">Cart</a></li>
      <li class="active"><a href="wishlist.php">Wishlist</a></li>
      <li><a href="orders.php">Orders</a></li>
    </ul>
  </div>
</nav>
  <?php
	function total_items($wishlist){
		$items = 0;
		if(is_array($wishlist)){
			foreach($wishlist as $wishlist => $qty){
				$items += $qty;
			}
		}
		return $items;
	}
  ?>


</body>
</html>