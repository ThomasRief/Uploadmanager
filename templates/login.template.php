<?php
$template = 
$getHook( 'template_doctype' ).
'<html>
	<head>
		
		<title>'.$getHook( 'template_title' ).'</title>
		<meta charset="'.$getHook( 'template_encoding' ).'" />
		
		'.$getHook( 'template_style' ).'
		
	</head>
	<body>
	
		<div class="wrapper">
		
			<div class="noticesBox">'.$getHook( 'login_notices' ).'</div>
			<div class="loginBox">
				
				<h2 class="contentTitle">Bitte melden Sie sich an</h2>
				<form action="login.php" method="POST">
				
					<input type="text" name="name" placeholder="Nutzername" />
					<input type="password" name="password" placeholder="Passwort" />
					
					<input type="submit" name="submit" value="Anmelden" />
					
					<div class="clear"></div>
					
				</form>
				
			</div>
		</div>
	
	</body>
</html>';

?>