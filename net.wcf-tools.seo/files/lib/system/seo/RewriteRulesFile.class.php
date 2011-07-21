<?php
require_once(WCF_DIR.'lib/system/io/File.class.php');

class RewriteRulesFile extends File {

	public function __construct($filename, $rewriteBase = null) {
		$existingContent = '';
		
		if (file_exists($filename)) {
			$existingContent = StringUtil::unifyNewlines(file_get_contents($filename));
			$existingContent = preg_replace("~\n?# WCF-SEO-START.*# WCF-SEO-END~s", '', $existingContent);
		}
		
		parent::__construct($filename);
		@chmod($filename, 0777);
		
		if (!empty($existingContent)) {
			$this->write($existingContent);
		}
		
		$this->write("\n# WCF-SEO-START\n");
		$this->write("<IfModule mod_rewrite.c>\n");
		$this->write("RewriteEngine On\n");
		
		if ($rewriteBase === null) {
			$rewriteBase = '/';
			$urlComponents = @parse_url(PAGE_URL);
			if (!empty($urlComponents['path'])) $rewriteBase = $urlComponents['path'];
		}
		
		$this->write("RewriteBase ".$rewriteBase."\n\n");
	}
	
	public function writeRules() {
		$sql = "SELECT 	*
				FROM wcf".WCF_N."_seo";
				
		$result = WCF::getDB()->sendQuery($sql);
		while($row = WCF::getDB()->fetchArray($result)) {
			if(file_exists(WCF_DIR.$row['classFile'])) {
				require_once(WCF_DIR.$row['classFile']);
				$class = new $row['className']();
				$class->writeRules($this);
			}		
		}
	}
	
	public function close() {
		$this->write("</IfModule>\n");
		$this->write("# WCF-SEO-END");
		
		fclose($this->resource);
	}
}
?>