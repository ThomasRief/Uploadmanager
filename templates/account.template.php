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
	
		'.$getHook( 'account_menu' ).'
	
		<div class="wrapper">
			
			'.$getHook('account_error').'
			
			<h2>'.$getHook( 'account_contentTitle' ).'</h2>
			'.$getHook( 'account_content' ).'
			
		</div>
	
	</body>
</html>';

?>