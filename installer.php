<?php

/// start session
	session_start();	

/// set some var
	$mainTitle = '';
	$subTitle = '';
	$error = '';
	$content = '';

if( isset( $_SESSION['userID'] ) ) {
	
	/// load user
		require_once( 'classes/user.class.php' );
		$user = new user();
		$user->loadUser( $_SESSION['userID'] );
		
} else {
	
	$user = FALSE;
}

/// content generieren
	if( isset( $_GET['do'] ) ) {
		
		switch( $_GET['do'] ) {
			
			default:
				$mainTitle = 'Unbekannte Aufgabe!';
				$subTitle = 'Aufgabe unbekannt:';
				$error = 
				'<div class="error">
					<p>
						Es scheint irgendwo ein Fehler aufgetreten zu sein. Sonst können wir uns das ganze nicht erklären.
						<br><br>
						Sie versuchen gerade etwas zu tun, was wir gar nicht können. Gehen Sie einfach eine Seite zurück, um den 
						Fehler zu beheben.
					</p>
				</div>';
			break;
				
			case 'firstCheck':
				$mainTitle = 'Fehlerüberprüfung';
				$subTitle = 'Ein kleiner Systemcheck:';
				$content = 
				'<h3>Folgende Probleme wurden gefunden:</h3>';
				
				if( !file_exists( 'config/db_data.config.php' ) ) {
					
					$content .= 
					'<p>
						Datenbankverbindung nicht konfiguriert (Fehlende Konfigurationsdatei)!<br>
						<a href="'.$_SERVER['PHP_SELF'].'?do=configurDB">beheben</a>
					</p>
					<p>
						... weitere Überprüfungen nicht möglich.
					</p>';
				
				} else {
					
					require_once( 'classes/connection.class.php' );
					require_once( 'config/db_data.config.php' );
					
					$conn = new connection( DB_USER, DB_PASS );
					
					$sql = 'USE '.DB_NAME;
					if( $conn->executeSQL( $sql ) == FALSE ) {
						
						$content .= 
						'<p>
							Datenbank "'.DB_NAME.'" konnte nicht gefunden werden und muss erstellt werden! <br>
							<a href="'.$_SERVER['PHP_SELF'].'?do=createDB">beheben</a>
						</p>';
					
					} else {
			
						$content .= 
						'<p>
							Es wurden keine Probleme gefunden!
						</p>
						<a href="index.php">Zur Hauptseite</a>';
					}
				} 
			break;
			
			case 'configurDB':
				$mainTitle = 'Datenbankverbindung erstellen:';
				$content = 
				'<h3>Datenbankverbindung erstellen:</h3>
				<p>
					Bitte geben Sie Ihre Datenbankinformationen ein:
				</p>
				<form action="'.$_SERVER['PHP_SELF'].'?do=configurDB" method="POST">
					<input type="text" name="name" value="root" placeholder="Nutzername" />
					<input type="password" name="password" placeholder="Passwort" />
					
					<input type="submit" name="submit" value="Verbinden" />
				</form>';
				
				if( isset( $_POST['submit'] ) ) {
					
					require_once( 'classes/connection.class.php' );
					try {
						
						$name = $_POST['name'];
						$pass = $_POST['password'];
						$conn = new connection( $name, $pass );
					
						$confile = 
						'<?php '.PHP_EOL.
						'	define( "DB_USER", "'.$name.'" );'.PHP_EOL.
						'	define( "DB_PASS", "'.$pass.'" );'.PHP_EOL.
						'	define( "DB_NAME", "uploadmanager" );'.PHP_EOL.
						'?>';
					
						$file = fopen( 'config/db_data.config.php', 'a' );
						fwrite( $file, $confile );
						
						header( 'Location: '.$_SERVER['PHP_SELF'].'?do=firstCheck' );
					
					} catch( Exception $e ) {
						
						$error = 
						'<div class="error">
							<p>
								Die Datenbankverbindung konnte nicht erstellt werden!
							</p>
						</div>';
					}
				}
			break;
			
			case 'createDB':
				$mainTitle = 'Datenbank erstellen:';
				$content =
				'<h3>Datenbank und Tabellen anlegen</h3>
				<p>
					Im folgenden Schritt werden die nötigen Datenbanken und die dazu entsprechenden Tabellen erstellt:
				</p>
				<div class="dir">
					<div class="folder">
						<p>Datenbank: uploadmanager</p>
						<div class="folder">
							<p>Tabelle: users</p>
							<div class="file"><p>Datenwert: user</p></div>
							<div class="file"><p>Datenwert: admin</p></div>
						</div>
						<div class="file"><p>Tabelle: downloadlog</p></div>
					</div>
				</div>
				<p>
					Klicken Sie auf "Weiter" um die Datenbank zu installieren.
				</p>
				<a href="'.$_SERVER['PHP_SELF'].'?do=createDB&checked">Weiter</a>';
				
				if( isset( $_GET['checked'] ) ) {
		
					require_once( 'classes/connection.class.php' );
					require_once( 'config/db_data.config.php' );
					$conn = new connection( DB_USER, DB_PASS );
		
					$sqlArray = array( 
									0 => 'CREATE DATABASE IF NOT EXISTS uploadmanager;',
									1 => 'CREATE TABLE IF NOT EXISTS users 
										  (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
										   name VARCHAR(16),
										   password VARCHAR(64),
										   permissions VARCHAR(16)
										  );',
									2 => 'CREATE TABLE IF NOT EXISTS downloadlog
										  (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
										   uploadUser VARCHAR(16),
										   downloadLink VARCHAR(32),
										   date TIMESTAMP
										  );',
									3 => 'INSERT INTO users 
										  (name, password, permissions ) 
										  VALUES ("user", "'.hash( 'sha256', 'user' ).'", "user");',
									4 => 'INSERT INTO users 
										  (name, password, permissions ) 
										  VALUES ("admin", "'.hash( 'sha256', 'admin' ).'", "admin");' );
						
					$errorOccurred = FALSE;			  
					foreach( $sqlArray as $sql ) {
						
						$conn->connectToDatabase( DB_NAME );
						
						if( $conn->executeSQL( $sql ) == FALSE ) {
							
							$errorOccurred = TRUE;
							$error = 
							'<div class="error">
								<p>
									Es konnten nicht alle Datenwerte installiert werden!
								</p>
							</div>';
						} 
					}
					
					if( !$errorOccurred ) {
						
						header( 'Location: '.$_SERVER['PHP_SELF'].'?do=firstCheck' );
					}
				}
			break;
			
		}
	
	} else {
		
		$mainTitle = 'Willkommen!';
		$subTitle = 'Datenbank konfigurieren:';
		$content =
		'<h3>Probleme mit der Datenbank?</h3>
		<p>
			Mithilfe des folgenden Programmes können Sie alle Datenbankfehler beheben. Drücken Sie einfach auf "Weiter"!
		</p>
		<a href="'.$_SERVER['PHP_SELF'].'?do=firstCheck">Weiter</a>';
	}

?>

<!DOCTYPE html>
	<html>
		<head>
			<title> Datenbankkonfiguration </title>
			<meta charset="UTF-8" />
			
			<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans" />
			<link rel="stylesheet" type="text/css" href="styles/installer.css" />
			
		</head>
		<body>
			<div class="wrapper">
				
				<header>
					
					<h1><?php echo( $mainTitle ) ?></h1>
					<h2><?php echo( $subTitle ) ?></h2>
					
				</header>
				<div class="content">
					
					<?php echo( $error ) ?>
					
					<?php echo( $content ) ?>
					
				</div>
				
			</div>
		</body>
	</html>