<?php

// set user's permission
if( isset( $attributes['userPerm'] ) ) {
	
	$userPerm = $attributes['userPerm'];

} else {
	
	$userPerm = 'user';
}

// create Linkarray
$links = '';
$subLinks = '';				// sublinks needed!!!
if( isset( $attributes['links'] ) ) {
	
	foreach( $attributes['links'] as $name => $attr ) {
		
		// test for needed attributes
		if( isset( $attr['href'] ) && isset( $attr['perm'] ) ) {
			
			$href = $attr['href'];
			$perm = $attr['perm'];
			
			// create tab
			if( $perm == $userPerm || $userPerm == 'admin' ) {
				
				$links .= 
				'<li>
					<a href="'.$href.'">'.$name.'</a>
				</li>';
			}
		}
	}
}

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
		
			'.$links.'
		
		</ul>
		
	</div>
	
	<div id="sideBar">
		
		<ul class="nav">
			
			'.$subLinks.'
			
		</ul>
		
	</div>
	
</div>';

?>