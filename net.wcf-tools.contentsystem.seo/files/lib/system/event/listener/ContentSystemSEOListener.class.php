<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

class ContentSystemSEOListener implements EventListener {
	protected $buffer = null;
	public $text = '';
	public $cachedContentLinks = array();

	
	//listener
	public function execute($eventObj, $className, $eventName) {
		if(MODULE_SEO){
			ob_start(array($this, 'formatOutput'));
		}
	}

	//output
	public function formatOutput($output, $status) {
		if ($status & PHP_OUTPUT_HANDLER_START) {
			$this->buffer = $this->rewrite($output);
		}

		if ($status & PHP_OUTPUT_HANDLER_END) {
			return $this->buffer;
		}
	}
	
	
	//rewrite links
	public function rewrite($output) {
		$this->text = $output;
		
		if (preg_match_all('~(?<=href=")index\.php\?page=Content&amp;contentID=(\d+)([^"]+)?(?=")~', $this->text, $matches)) {
			$this->cacheContentLinks($matches[1]);
		}
		
		if(count($this->cachedContentLinks) > 0){
			$this->text = preg_replace_callback('~(?<=href=")index\.php\?page=Content&amp;contentID=(\d+)([^"]+)?(?=")~', array($this, 'replaceContentLinksCallback'), $this->text);
		}
		
		return $this->text;
	}
	
	
	public function cacheContentLinks($contentIDArray) {
		$sql = "SELECT contentID, title
			FROM	wcf".WCF_N."_content
			WHERE	contentID IN (".implode(',', $contentIDArray).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			try{
				$title = SEOUtil::formatString($row['title']);
				$title = StringUtil::encodeHTML($title);
				$this->cachedContentLinks[$row['contentID']] = array('title' => $title);
			} catch (Exception $e) {}
		}
	}
	
	public function replaceContentLinksCallback($match) {
		if (!isset($this->cachedContentLinks[$match[1]])) {
			return $match[0];
		}
		
		return $this->parseContentURLs($match[1], (isset($match[2]) ? $match[2] : ''));
	}
	
	
	public function parseContentURLs($contentID, $queryString) {
		if (!isset($this->cachedContentLinks[$contentID])) return false;
		$content = $this->cachedContentLinks[$contentID];
		
		$string = "{CONTENT_ID}-{CONTENT_TITLE}/";
		
		$string = str_replace('{CONTENT_ID}', $contentID, $string);
		$string = str_replace('{CONTENT_TITLE}', $content['title'], $string);
		
		$string = SEOUtil::appendQueryString($string, $queryString);
		
		return $string;
	}
	
	
}
?>