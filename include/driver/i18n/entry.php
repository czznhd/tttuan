<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename entry.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-07-28 07:47:50 $
 *******************************************************************/ 
 



if ( !class_exists( 'Translation_Entry' ) ):

class Translation_Entry {

	
	var $is_plural = false;

	var $context = null;
	var $singular = null;
	var $plural = null;
	var $translations = array();
	var $translator_comments = '';
	var $extracted_comments = '';
	var $references = array();
	var $flags = array();

	
	function Translation_Entry($args=array()) {
				if (!isset($args['singular'])) {
			return;
		}
				foreach ($args as $varname => $value) {
			$this->$varname = $value;
		}
		if (isset($args['plural'])) $this->is_plural = true;
		if (!is_array($this->translations)) $this->translations = array();
		if (!is_array($this->references)) $this->references = array();
		if (!is_array($this->flags)) $this->flags = array();
	}

	
	function key() {
		if (is_null($this->singular)) return false;
				return is_null($this->context)? $this->singular : $this->context.chr(4).$this->singular;
	}
}
endif;