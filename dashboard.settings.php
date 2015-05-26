<?php

/// load frame
	require_once( 'php/frame.php' );
	
/// process document
	try {
		
		/// some document values
			$html->title = 'Einstellungen |uploadmanager';				// head title tag
			$docTemplate = 'templates/dashboard.template.php';				// main template
			
			$contentTitle = 'Einstellungen:';							// displayed title
			
			// stylelinks
			$html->addStylelink( 'http://fonts.googleapis.com/css?family=Open+Sans' );
			$html->addStylelink( 'styles/main.css' );
			$html->addStylelink( 'styles/noticeBoxes.css' );
			$html->addStylelink( 'styles/dashboard.css' );
			$html->addStylelink( 'styles/dashboardHeader.css' );
			
		/// generate content
			// menu
			$menu = 
			array(
				'userPerm' => $user->user_permGroup,
				'selectedPage' => 'settings',
				'links' => $menuArray );
			
			// main content
			$content =
			'<div class="notice">
				<p>
					Leider ist dieser Bereich noch nicht ausgebaut.
				</p>
			</div>';
			
			// error handling
			if( !isset( $error ) ) {
				
				$error = '';
			}
			
		/// set hooks
			// string hooks
				// menu
				$html->setHook( 'menu_username', $user->user_name );
				
				// content
				$html->setHook( 'account_contentTitle', $contentTitle );
				$html->setHook( 'account_content', $content );
		
				// error
				$html->setHook( 'account_error', $error );	
				
			// template hooks
				// main
				$html->setTemplate( $docTemplate );
				
				// menu
				$html->setHookAsTemplate( 'account_menu', 'templates/menu.template.php', $menu );
				$html->setHookAsTemplate( 'template_head', 'templates/head.template.php' );
				
		/// close file
			$html->createFile();
			
			
				
	} catch( ErrorException $e ) {
		
		header( 'Location: error.php' );
	}
?>