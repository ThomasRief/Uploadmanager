<?php

///Start session and test if user is logged in
	session_start();
	if( !isset( $_SESSION['userID'] ) ) {
		
		header( 'Location: index.php?pleaseLogin' );
	}

///import needed classes and configuration
	require_once( 'classes/connection.class.php' );
	require_once( 'classes/user.class.php' );
	require_once( 'classes/html.class.php' );

	require_once( 'config/db_data.config.php' );
	require_once( 'config/user_table.config.php' );
	require_once( 'config/menu.config.php' );

///define some meta values about this document
	$title 				= 'Upload Übersicht';
	$templateLink 		= 'templates/dashboard.template.php';

///start page processing
	try {
		
        // set classes
	    $conn = new connection( DB_USER, DB_PASS, 'uploadmanager' );
	    $html = new html();
	    $user = new user();
	    
		// load user
        if( !$user->loadUser( $_SESSION['userID'] ) ) {
	         header( 'Location: error.php' );
        }
        
        // try to save uploaded file
        if ($_FILES) 							// only when something in $_FILES
		{   
		    // create downloadlink
			$time_now = date('d-m-Y__H-i-s');
			$downloadlink = "uploads/" . $user->user_name . "__" . $time_now . "__" . $_FILES['userfile']['name'];
		
			// save file
			move_uploaded_file($_FILES['userfile']['tmp_name'], $downloadlink);
  
			// database insert
			$stmt = $conn->connection->prepare("INSERT INTO downloadlog (uploadUser, downloadLink, date) VALUES(:id , :downloadlink , :time_now )");
			$stmt->bindValue(':id', $user->user_ID);
			$stmt->bindValue(':downloadlink', $downloadlink);
			$stmt->bindValue(':time_now', $time_now);
			$conn->executeStatement( $stmt );
		}
		
		// get all uploads of user out of the database
		$sth = $conn->connection->prepare("SELECT * FROM downloadlog WHERE uploadUser = :user_id");
        $sth->bindParam(':user_id', $user->user_ID, PDO::PARAM_INT); 
		$sth->execute();

        $result = $sth->fetchAll();		
		
		// set some document information
	    $html->setTemplate( $templateLink );
	    $html->title = $title;
	
	    // bind styles
	   	$html->addStylelink( 'http://fonts.googleapis.com/css?family=Open+Sans' );
		$html->addStylelink( 'styles/main.css' );
		$html->addStylelink( 'styles/noticeBoxes.css' );
		$html->addStylelink( 'styles/dashboard.css' );
		$html->addStylelink( 'styles/dashboardHeader.css' );
		
	    // define content
	    // table head           
        $content = '<table> <tr><th>Download Link</th> </tr>'; 
		
		// table rows
		foreach($result as $row){
           $content .= '<tr><td>' . $row['downloadLink'] . '</td></tr>';
		}
	    $content .= '</table>';
	    
		// set hook
	    $html->setHook( 'menu_username', $user->user_name );
	    $html->setHook( 'account_contentTitle', 'Bereits hochgeladene Dokumente' );
	    $html->setHook( 'account_content', $content );
	
		// menu
		$menu = 
		array(
			'userPerm' => $user->user_permGroup,
			'selectedPage' => 'overview',
			'links' => $menuArray );
			
	    // set templates
		$html->setHookAsTemplate( 'account_menu', 'templates/menu.template.php', $menu );
		$html->setHookAsTemplate( 'template_head', 'templates/head.template.php' );
	
	    // creat file
	    $html->createFile();
		
	} catch( PDOException $e ) {
			
		saveError( $e );
		header( 'Location: '.$_SERVER['PHP_SELF'].'?do=2' );
			
	}
?> 
  
  
