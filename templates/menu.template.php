<?php

$template = 
'<div id="header">
	
	<div id="firstRow">
		<a href="account.php"><p id="projectTitle">Uploadmanger</p></a>
		<div id="userRow">
			<p> Willkommen, '.$getHook( 'menu_username' ).' | <a href="logout.php"> Abmelden </a>
		</div>
	</div>
	<div id="secondRow">
		
		<ul class="nav">
		
			<li>
				<a href="accountEdit.php"> Mein Account </a>
			</li>
			<li>
				<a href="upload.php"> Uploads </a>
			</li>
		
		</ul>
		
	</div>
	
</div>';

?>