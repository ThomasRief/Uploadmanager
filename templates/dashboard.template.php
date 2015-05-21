<?php

$template = 
$getHook( 'template_doctype' ).
'<html>
	
	'.$getHook( 'template_head' ).'
	
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