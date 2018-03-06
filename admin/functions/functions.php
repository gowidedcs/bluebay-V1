<?php


$con=mysqli_connect("localhost","root","","bluebayadmin");

if ($con->connect_error) {
    die("Connection failed: ".$con->connect_error);
}

//getting categories

function getCats(){

	global $con;
		
	$get_cats = "select * FROM categories";
	
	$run_cats = mysqli_query($con, $get_cats);
	
	while($row_cats = mysqli_fetch_array($run_cats)){
		$cat_id = $row_cats['cat_id'];
		$cat_title = $row_cats['cat_title'];
		echo "<li><a href='#'>$cat_title //\r\n</a></li>";
	}
    $con->close();	
}

?>