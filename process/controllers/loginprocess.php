<?php

session_start();
include("config/dbconn.php");
$checker = 0;
$query = "SELECT * FROM useraccounts where username = '".$_POST["username"]."' and password = '".$_POST["password"]."'";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) 
{ 
	$checker = 1;
	$_SESSION['userid'] = $row["username"];
	header('location: ../../Main.php');
}

if($checker == 0){
	?>
			<script type="text/javascript">
			alert("That user doesn't exist!");
			window.location="../../login.php?invalid=1";
			</script>
	<?php 
}





?>


