<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Alphalist Registration</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Withholding Agent Information
					</span>
				</div>

				<form class="login100-form validate-form" action="process/alpharegisterprocess.php" method="POST">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">RDO Code</span>
						<input class="input100" value="" minlength="2" maxlength="30" placeholder="RDO Code" id="rdo" name="rdo" required="required" pattern="[^*()/><\][\\\x22,;|]+" autofocus>
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Tin</span>
						<input class="input100" value="" minlength="2" maxlength="30" placeholder="Tin No." id="tin" name="tin" required="required" pattern="[^*()/><\][\\\x22,;|]+" autofocus>
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<label class="label-input100">Withheld Category</label>
                                                 <select name="withcateg" id="withcateg" class="input100">
                                                    <option value="Private">Private</option>
                                                <option value="Government">Government</option>
                                                </select>
					</div>

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<label class="label-input100">Tax Payer Classification</label>
                                                 <select name="taxclass" id="taxclass" class="input100">
                                                    <option value="Non-Individual">Non-Individual</option>
                                                    <option value="Individual">Individual</option>
                                                </select>
					</div>

					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Signatory</span>
						<input class="input100" value="" minlength="2" maxlength="30" placeholder="Signatory" id="Signatory" name="Signatory" required="required" pattern="[^*()/><\][\\\x22,;|]+" autofocus>
						<span class="focus-input100"></span>
					</div>
					

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Save
						</button>
						<label>&nbsp</label>
						<button class="login100-form-btn" onclick="Back();">
							Back
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
<script  type="text/javascript">
	function Back(){
		// alert(1);
		var rdo = document.getElementById("rdo").value;
		alert(rdo);
		//alert(orgid);
		$.ajax({	
							type: 'GET',
							url: 'process/alpharegisterprocess.php',
							//data:'action=save&actmode=userform&userno='+UId.value+'&pass='+UPass.value+'&lname='+NM.value+'&darea='+DT.value,
							data:{rdo:rdo},
							beforeSend:function(){
									
							//$("#datatbl").html('<center><img src="img/loading.gif" width="300" height="300"></center>');
								
							},
							success: function(data){
							//$('#result').html(data);		
							//alert(orgid);			
							}
					}); 
	}
	 	
</script>
<script type="text/javascript" src="js/custom.js"></script>
</body>
</html>