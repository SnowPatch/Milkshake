<?php 

namespace Milkshake\Core; 

class Controller {
	
	private $path;
	private $data;
	private $content;
	
	private function compile() {
		
		$this->content = '';
		
		/* Convert data array into local variables */
		if ($this->data) {
			extract($this->data, EXTR_SKIP);
		}
		
		$this->content = preg_replace([
			
			/* Break raw PHP */
			'/<\?php/',
			'/<\?/',
			
			/* Echo raw */
			'/{{/',
			'/}}/', 
			
			/* Echo escaped */
			'/@echo \$(\w+)/i',
			'/@echo \((.+)\)/i',
			'/@echo\((.+)\)/i', // no whitespace
			
			/* PHP code blocks */
			'/\[\[/',
			'/\]\]/',
			
			/* End statement */
			'/@end/i', 
			
			/* IF/ELSE */
			'/@if \((.+)\)/i',
			'/@if\((.+)\)/i', // no whitespace
			'/@elseif \((.+)\)/i',
			'/@elseif\((.+)\)/i', // no whitespace
			'/@else/i',
			
			/* Isset */
			'/@isset \$(\w+)/i',
			'/@isset \((.+)\)/i',
			'/@isset\((.+)\)/i', // no whitespace
			
			/* Empty */
			'/@empty \$(\w+)/i',
			'/@empty \((.+)\)/i',
			'/@empty\((.+)\)/i', // no whitespace
			
			/* For loop */
			'/@for \((.+)\)/i',
			'/@for\((.+)\)/i', // no whitespace
			
			/* Foreach loop */
			'/@foreach \((.+)\)/i',
			'/@foreach\((.+)\)/i', // no whitespace
			
			/* While loop */
			'/@while \((.+)\)/i',
			'/@while\((.+)\)/i', // no whitespace
			
			/* Date */
			'/@date \((.+)\)/i',
			'/@date\((.+)\)/i', // no whitespace
			
			/* FILE VERSION */
			'/@fileversion \((.+)\)/i',
			'/@fileversion\((.+)\)/i', // no whitespace
				
		], [
				
			/* Break raw PHP */
			'',
			'',
			
			/* Echo raw */
			'<?php echo ',
			'; ?>',
			
			/* Echo escaped */
			'<?php echo htmlspecialchars(\$$1); ?>',
			'<?php echo htmlspecialchars($1); ?>',
			'<?php echo htmlspecialchars($1); ?>',
			
			/* PHP code blocks */
			'<?php ',
			' ?>',
			
			/* End statement */
			'<?php } ?>',
			
			/* IF/ELSE */
			'<?php if ($1) { ?>',
			'<?php if ($1) { ?>',
			'<?php elseif ($1) { ?>',
			'<?php elseif ($1) { ?>',
			'<?php } else { ?>',
			
			/* Isset */
			'<?php if (isset(\$$1)) {',
			'<?php if (isset($1)) {',
			'<?php if (isset($1)) {',
			
			/* Empty */
			'<?php if (empty(\$$1)) {',
			'<?php if (empty($1)) {',
			'<?php if (empty($1)) {',
			
			/* For loop */
			'<?php for ($1) { ?>',
			'<?php for ($1) { ?>',
			
			/* Foreach loop */
			'<?php foreach ($1) { ?>',
			'<?php foreach ($1) { ?>',
			
			/* While loop */
			'<?php while ($1) { ?>',
			'<?php while ($1) { ?>',
			
			/* Date */
			'<?php echo date($1); ?>',
			'<?php echo date($1); ?>',
			
			/* FILE VERSION */
			'$1?v=<?php print filemtime(\'$1\'); ?>',
			'$1?v=<?php print filemtime(\'$1\'); ?>',
				
		], file_get_contents($this->path));
		
		/* Execute PHP with buffer */
		ob_start();
		eval('?>' . $this->content);
		$this->content = ob_get_clean();
		
		/* Return result */
		return $this->content;
		
	}
	
	public function render($path, $data = false) {
		
		$file = str_replace('/', '.', $path);
		$file = 'view/' . $path . '.html';
		
		if (!is_file($file)) {
			die('Something went wrong (Template not found)');
		}
		if (!is_readable($file)) {
			die('Something went wrong (Template not readable)');
		}
		
		$this->path = $file;
		$this->data = $data;
		
		$result = $this->compile();

		return $result;
		
	}
	
	public function model($name) {
		
		$model = '\Milkshake\Model\\'.$name;
		return new $model;
		
	}

}

?>