<?php
  session_start();
  if( !isset( $_SESSION['userID'] ) ) {
	
	  header( 'Location: index.php?pleaseLogin' );
  }
  
  // important stuff!!!
  require_once( 'classes/connection.class.php' );
  require_once( 'classes/user.class.php' );
  require_once( 'classes/html.class.php' );

  require_once( 'config/db_data.config.php' );
  require_once( 'config/user_table.config.php' );
  
  // some document information
  $title 				= 'hochgeladende Dateien';
  $templateLink 		= 'templates/account.template.php';
  
   // really important to catch Exceptions
   try {
        // set classes
	    $conn = new connection( DB_USER, DB_PASS, 'uploadmanager' );
	    $html = new html();
	    $user = new user();
	    
		// load user
        if( !$user->loadUser( $_SESSION['userID'] ) ) {
	         header( 'Location: error.php' );
        }
        // some variables
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
		
		// database 

		$sth = $conn->connection->prepare("SELECT * FROM downloadlog WHERE uploadUser = :user_id");
        $sth->bindParam(':user_id', $user->user_ID, PDO::PARAM_INT); 
		$sth->execute();

        // Fetch all of the rows in $result 
        $result = $sth->fetchAll();		
		
		// set some document information
	    $html->setTemplate( $templateLink );
	    $html->title = $title;
	
	    // bind styles
	    $html->addStylelink( 'http://fonts.googleapis.com/css?family=Open+Sans' );
	    $html->addStylelink( 'styles/main.css' );
	    $html->addStylelink( 'styles/upload.css' );
	
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
	
	    // set templates
	    $html->setHookAsTemplate( 'account_menu', 'templates/menu.template.php' );
	
	    // creat file
	    $html->createFile();
		
	} catch( PDOException $e ) {
			
		saveError( $e );
		header( 'Location: '.$_SERVER['PHP_SELF'].'?do=2' );
			
	}
?> 
  
  
