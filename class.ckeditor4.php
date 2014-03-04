<?php

/**
 *	@module		ckeditor
 *	@version	see info.php of this module
 *	@authors	Dietrich Roland Pehlke, erpe
 *	@copyright	2012 - 2014 Dietrich Roland Pehlke, erpe
 *	@license	GNU General Public License
 *	@license_terms	see info.php of this module
 *
 */


class ckeditor
{
	private $guid = "244312D5-DB24-4FBA-99A4-D855EA45E77A";

	public $config = array(
		'width'	=> "100%",
		'height' => "250px",
		'content' => "",
		'id'	=> '',
		'name'	=> '(no name)',
		'language' => 'en',
		'contentsCss' => '',
		'customConfig' => ''
	);
		
	/**
	 *	@var	array	More-dimensional array for the 'look-up' paths for
	 *					editor.css, editor.style.js, templates.js and the config.js
	 *
	 */
	public $files = array(
		'contentsCss' => Array(
			'/editor.css',
			'/css/editor.css',
			'/editor/editor.css'
		),
		'stylesSet' => Array(
			'/editor.styles.js',
			'/js/editor.styles.js',
			'/editor/editor.styles.js'
		),
		'templates_files' => Array(
			'/editor.templates.js',
			'/js/editor.templates.js',
			'/editor/editor.templates.js'
		),
		'customConfig' => Array(
			'/wb_ckconfig.js',
			'/js/wb_ckconfig.js',
			'/editor/wb_ckconfig.js'
		)
	);

	/**
	 *	@var	string	An internal template-string for the generated textarea.
	 *
	 */
	public $textarea = "\n<textarea name='%s' id='%s' width='%s' height='%s' cols='8' rows='8'>%s</textarea>\n";
	
	/**
	 *	@var	string	Path to the basic script file of CkEditor.js
	 *
	 */
	public $ckeditor_file = "";
	
	
	private $script_loaded = false;
	
	/**
	 *	The constructor of the class
	 *
	 */
	public function __construct() {
		
	}
	
	/**
	 *	The destructor of the class.
	 *
	 */
	public function __destruct() {
	
	}
	
	/**
	 *	@param	string	Any HTML-Source, pass by reference
	 *
	 */
	public function reverse_htmlentities(&$html_source) {

		$html_source = str_replace(
			array_keys( $this->lookup_html ),
			array_values( $this->lookup_html ),
			$html_source
		);
    }

	public function toHTML() {
		$html  = $this->__build_textarea();
		$html  .= $this->__build_script();
		
		return $html;
	}
	
	private function __build_textarea() {
		return sprintf(
			$this->textarea,
			$this->config['name'],
			$this->config['id'],
			$this->config['width'],
			$this->config['height'],
			htmlspecialchars_decode( $this->config['content'] )
		);
	}
	
	private function __build_script() {
		$s = "";
		if (false == $this->script_loaded) {
			$s .= "\n<script type='text/javascript' src='".$this->ckeditor_file."'></script>\n";
			$this->script_loaded = true;
		}

		$s .= "
			<script>
		";
				
		foreach( $this->config as $key => $value ) {
			$s .= "CKEDITOR.config['".$key."'] = ".$this->jsEncode( $value ).";\n";
		}
		
		$s .= "CKEDITOR.replace( '". $this->config['id']. "', { customConfig: '". $this->config['customConfig']."' } );
		</script>
		";

		return $s;
	}
	
	/**
	 * This little function provides a basic JSON support.
	 *
	 * @param mixed $val
	 * @return string
	 */
	private function jsEncode($val)
	{
		if (is_null($val)) {
			return 'null';
		}
		if (is_bool($val)) {
			return $val ? 'true' : 'false';
		}
		if (is_int($val)) {
			return $val;
		}
		if (is_float($val)) {
			return str_replace(',', '.', $val);
		}
		if (is_array($val) || is_object($val)) {
			if (is_array($val) && (array_keys($val) === range(0,count($val)-1))) {
				return '[' . implode(',', array_map(array($this, 'jsEncode'), $val)) . ']';
			}
			$temp = array();
			foreach ($val as $k => $v){
				$temp[] = $this->jsEncode("{$k}") . ':' . $this->jsEncode($v);
			}
			return '{' . implode(',', $temp) . '}';
		}
		// String otherwise
		if (strpos($val, '@@') === 0)
			return substr($val, 2);
		if (strtoupper(substr($val, 0, 9)) == 'CKEDITOR.')
			return $val;

		return '"' . str_replace(array("\\", "/", "\n", "\t", "\r", "\x08", "\x0c", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'), $val) . '"';
	}
}
?>