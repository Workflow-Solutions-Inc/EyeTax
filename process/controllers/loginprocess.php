<?php

session_start();
//include("dbconn.php");



	/*$username = $_POST["username"];
	$password = $_POST["password"];
	if(($username=="admin")&&($password=="admin")){
		header('location: ../../Main.php');
	}
	else if(($username=="jabogado")&&($password=="adminjabogado")){
		header('location: ../../Main.php');
	}
	else if(($username=="raymond")&&($password=="adminraymond")){
		header('location: ../../Main.php');
	}else{
		?>
			<script type="text/javascript">
			alert("That user doesn't exist!");
			window.location="../../login.php?invalid=1";
			</script>
		<?php 
	}*/
include("config/dbconn.php");
$checker = 0;
$query = "SELECT * FROM useraccounts where username = '".$_POST["username"]."' and password = '".$_POST["password"]."'";
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) 
{ 
	$checker = 1;
	$_SESSION['userid'] = $_POST["username"];
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


