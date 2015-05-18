<?php
$template = 
$getHook( 'template_doctype' ).
'<html>

	'.$getHook( 'template_head' ).'
	
	<body>
	
		<div class="wrapper">
		
			<div class="noticesBox">'.$getHook( 'login_notices' ).'</div>
			<div class="loginBox">
				
				<h2 class="contentTitle">Login:</h2>
				<form action="login.php" method="POST">
				
					<input type="text" name="name" placeholder="Nutzername" />
					<input type="password" name="password" placeholder="Passwort" />
					
					<input type="submit" name="submit" value="Anmelden" />
					
					<label for="keepLoggedIn">
						<input type="checkbox" name="keepLoggedIn" value="true" />
						Angemeldet bleiben
					</label>
					
					<div class="clear"></div>
					
					<a class="question" href="">Hast du dein Passwort vergessen?</a>
				
				</form>
				
			</div>
		</div>
	
	</body>
</html>';

?>