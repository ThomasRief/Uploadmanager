<?php

class html {
	
	public $doctype;
	
	public $encoding;
	
	public $title;
	
	private $hooks;
	
	private $template;
	
	private $style;
	
	
	public function __construct() {
		
		$this->hooks = array();
		$this->style = array();
		
		$this->title = 'untitled';
		$this->encoding = 'UTF-8';
		$this->doctype = '<!DOCTYPE html>';
		
	}
	
	
	public function addStylelink( $href ) {
		
		array_push( $this->style, $href );
	}
	
	
	public function getStylelinks() {
		
		$links = '';
		foreach( $this->style as $href ) {
			
			$links .= '<link rel="stylesheet" type="text/css" href="'.$href.'" />';
		}
		
		return $links;
	}
	
	
	public function setTemplate( $url ) {
		
		$this->template = $url;
	}
	
	
	public function setHook( $name, $value ) {
		
		$this->hooks[$name] = $value;
	}
	
	
	private function getHook( $hook ) {
		
		if( isset( $this->hooks[$hook] ) ) {
			
			return $this->hooks[$hook];
		
		} else {
			
			return '';
		}
	}
	
	
	public function setHookAsTemplate( $hookName, $templateURL ) {
		
		$getHook = function( $hook ) {
			
			return self::getHook( $hook );
		};
		
		require_once( $templateURL );
		self::setHook( $hookName, $template );
	}
	
	
	public function createFile() {
		
		$getHook = function( $hook ) {
			
			return self::getHook( $hook );
		};
		
		self::setHook( 'template_doctype', $this->doctype );
		self::setHook( 'template_encoding', $this->encoding );
		self::setHook( 'template_title', $this->title );
		self::setHook( 'template_style', self::getStylelinks() );
		
		require_once( $this->template );
		if( isset( $template ) ) {
			
			 echo( $template );
		}
	}
}	
	
?>