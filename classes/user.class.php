<?php

class user extends connection {
	
	public $user_name;
	
	public $user_ID;
	
	public $user_permGroup;
	
	private $table_name;
	
	private $table_nameCol;
	
	private $table_IDCol;
	
	private $table_passCol;
	
	private $table_permCol;
	
	
	/**
	 * Constructor. Lädt einen Nutzer.
	 * @param string $userConfigFile das Verzeichnis zur Datei mit Nutzerdaten.
	 * @param string $useID die Nutzer-ID definiert in der Tabelle.
	 * @param string $dbuser der Datenbanknutzer
	 * @param string $dbpass das passende Passwort zum Datenbanknutzer
	 * @param string $dbname die Datenbank.
	 * 
	 * @return void, bei Fehlern wird eine Exception geworfen.
	 */
	public function __construct() {
		
		try {
			
			// test for components
			if( self::testForComponents() ) {
			
				// load connection
				parent::__construct( DB_USER, DB_PASS, DB_NAME );
				
				// define some methods
				$this->table_name = USERTABLE_NAME;
				$this->table_IDCol = USERTABLE_USERID;
				$this->table_nameCol = USERTABLE_USERNAME; 	
				$this->table_passCol = USERTABLE_USERPASS;	
				$this->table_permCol = USERTABLE_USERPERM;	
				
			} else {
			
				throw new Exception( 'Die nötigen Konstanten sind nicht definiert!' );
			}
		
		} catch( PDOException $e ) {
			
			throw new Exception( $e );
		}
	}
	
	
	/**
	 * Überprüft, ob die nötigen Komponenten definiert sind.
	 *  
	 * @return boolen TRUE, wenn ja, FALSE wenn nicht.
	 */
	public function testForComponents() {
		
		if( defined( 'USERTABLE_NAME' ) &&
			defined( 'USERTABLE_USERID' ) &&
			defined( 'USERTABLE_USERNAME' ) &&
			defined( 'USERTABLE_USERPASS' ) &&
			defined( 'USERTABLE_USERPERM' ) &&
			defined( 'DB_USER' ) &&
			defined( 'DB_PASS' ) &&
			defined( 'DB_NAME' ) ) {
				
			return TRUE;
		
		} else {
			
			return FALSE;
		}
	}


	/**
	 * Lädt eine Nutzer.
	 * @param string $userID die Nutzer ID.
	 * 
	 * @return boolen TRUE, wenn Nutzer geladen werden konnte, FALSE wenn nicht.
	 */
	public function loadUser( $userID ) {
		
		$result = parent::select( $this->table_name, '*', TRUE, $this->table_IDCol, $userID );
		if( $result != FALSE ) {
			if( count( $result ) == 1 ) {
									
				$this->user_ID 			= $userID; 
				$this->user_name 		= $result[0][$this->table_nameCol];
				$this->user_permGroup	= $result[0][$this->table_permCol];
				
				return TRUE;
				
			} else {
				
				return FALSE;
			}
		
		} else {
			
			return FALSE;
		}
	}
	
	
	/**
	* Überprüft, ob der geladen Nutzer die nötigen Rechte hat.
	* @param string $neededPermission das nötige Recht.
	* 
	* @return boolen TRUE, wenn der Nutzer die Rechte hat, FALSE wenn nicht.
	*/
	public function checkForPermission( $neededPermission ) {
		
		if( $this->user_permGroup == $neededPermission ) {
			
			return TRUE;
		
		} else {
			
			return FALSE;
		}
	}	
}

?>