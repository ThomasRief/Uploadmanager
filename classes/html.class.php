<?php

class html {
	
	/**
	 * der Doctype des Dokuments (Standard: HTML5) 
	 * @type string
	 */
	public $doctype;
	
	/**
	 * die Codierung des Dokuments (Standard: UTF-8) 
	 * @type string
	 */
	public $encoding;
	
	/**
	 * der Titel (title-Tag im head) des Dokuments (Standard: untitled) 
	 * @type string
	 */
	public $title;
	
	/**
	 * der Hook-Array, der alle Hooks zwischenspeichert.
	 * @type array
	 */
	private $hooks;
	
	/**
	 * ähnlich wie $hooks speichert dies alle Templates, die als Hooks gespeichert wurden.
	 * @type array
	 */
	private $templateHooks;
	
	/**
	 * das Haupttemplate, gespeichert als Link
	 * @type string
	 */
	private $template;
	
	/**
	 * ein Array aus allen Links (href-Attribut) zu den CSS-Dateien
	 * @type array
	 */
	private $style;
	
	
	/**
	 * Constructor. Definiert einige Standards.
	 * 
	 * @return void
	 */
	public function __construct() {
		
		$this->hooks = array();
		$this->templateHooks = array();
		$this->style = array();
		
		$this->title = 'untitled';
		$this->encoding = 'UTF-8';
		$this->doctype = '<!DOCTYPE html>';
		
	}
	
	
	/**
	 * Fügt einen Stylelink zum Dokument hinzu.
	 * @param string $href das Verzeichnis zum CSS-Dokument
	 * 
	 * @return void
	 */
	public function addStylelink( $href ) {
		
		array_push( $this->style, $href );
	}
	
	
	/**
	 * lädt alle hinterlegten Stylelinks und gibt einen HTML-String ab.
	 * 
	 * @return string den generierten HTML-String
	 */
	public function getStylelinks() {
		
		$links = '';
		foreach( $this->style as $href ) {
			
			$links .= '<link rel="stylesheet" type="text/css" href="'.$href.'" />';
		}
		
		return $links;
	}
	
	
	/**
	 * hinterlegt das Haupttemplate
	 * @param string $url das Verzeichnis zur Datei
	 * 
	 * @return void
	 */
	public function setTemplate( $url ) {
		
		$this->template = $url;
	}
	
	
	/**
	 * definiert einen Hook, der vom Template geladen werden kann
	 * @param string $name der Hook-Name
	 * @param string $value der Wert des Hooks
	 * 
	 * @return void
	 */
	public function setHook( $name, $value ) {
		
		$this->hooks[$name] = $value;
	}
	
	
	/**
	 * holt den Wert des gegebenen Hooks
	 * @param string $hook der Hook-Name
	 * 
	 * @return string den gespeicherten Wert des Hooks
	 */
	private function getHook( $hook ) {
		
		if( isset( $this->hooks[$hook] ) ) {
			
			return $this->hooks[$hook];
		
		} else {
			
			return '';
		}
	}
	
	
	/**
	 * parst eine Templatedatei und speichert den String als Hook
	 * @param string $hookName der Hook-Name
	 * @param string $templateURL das Verzeichnis der Datei
	 * @param array $attributes (optional) Attribute, die das Template brauchen könnte.
	 * 
	 * @return void
	 */
	public function setHookAsTemplate( $hookName, $templateURL, $attributes = '' ) {
		
		if( empty( $attributes ) ) {
			
			$attributes = array();
		}
		
		$this->templateHooks[$hookName] = array( 'href' => $templateURL, 'attributes' => $attributes );
	}
	
	
	/**
	 * Schließt das Dokument ab und bindet alle Hooks in die Haupttemplatedatei ein. Das HTML wird ausgegeben!
	 * 
	 * @return void
	 */
	public function createFile() {
		
		// definiere eine wirklich wichtige Funktionsvariable
		$getHook = function( $hook ) {
			
			return self::getHook( $hook );
		};
		
		// definiere einige Standardhooks
		self::setHook( 'template_doctype', $this->doctype );
		self::setHook( 'template_encoding', $this->encoding );
		self::setHook( 'template_title', $this->title );
		self::setHook( 'template_style', self::getStylelinks() );
		
		// lade alle Templatehooks
		foreach( $this->templateHooks as $tempHook => $settings ) {
			
			$href = $settings['href'];
			$attributes = $settings['attributes'];
			
			require_once( $href );
			if( isset( $template ) ) {
				
				self::setHook( $tempHook, $template );
			}
		}
		
		// lade das Haupttemplate und gebe es aus
		require_once( $this->template );
		if( isset( $template ) ) {
			
			 echo( $template );
		}
	}
}	
	
?>