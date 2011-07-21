<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

class Content extends DatabaseObject {
	protected $parentContent;
	protected static $content = null;
	protected static $contentStructure = null;
	protected static $contentSelect = null;

	public function __construct($contentID, $row = null, $cacheObject = null) {
		if ($contentID !== null) $cacheObject = self::getContent($contentID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}
	
	//try to get content from cache
	public function getContent($contentID) {
		if (self::$content === null) {
			self::$content = WCF::getCache()->get('content', 'content');
		}
		
		if (!isset(self::$content[$contentID])) {
			throw new IllegalLinkException();
		}
		
		return self::$content[$contentID];
	}
	
	public function getURL() {
		if($this->contentType == 1)
			return $this->url;
		else
			return "index.php?page=Content&contentID=".$this->contentID.SID_ARG_2ND_NOT_ENCODED;
	}

	//get parent pages
	public function getParentContent() {
		if ($this->parentContent === null) {
			$this->parentContent = array();
			$content = WCF::getCache()->get('content', 'content');
			
			$parentContent = $this;
			while ($parentContent->parentID != 0) {
				$parentContent = $content[$parentContent->parentID];
				array_unshift($this->parentContent, $parentContent);
			}
		}
		
		return $this->parentContent;
	}
	
	//get content from current pageNo
	public function getPage($pageNo) {
		$splitContent = explode("<!-- pagebreak -->", $this->content);		
		return $splitContent[$pageNo-1];
	}
	
	//count number of pages / pagebreak per content
	public function countPages() {
		$splitContent = explode("<!-- pagebreak -->", $this->content);		
		return count($splitContent);
	}
	
	//reset cache
	public static function resetCache() {
		WCF::getCache()->clearResource('content');
		
		self::$content = self::$contentStructure = self::$contentSelect = null;
	}
	
	//get a select field for forms
	public static function getContentSelect($hideLinks = false, $showInvisibleContent = false, $ignore = array()) {
		self::$contentSelect = array();
		
		if (self::$contentStructure === null) self::$contentStructure = WCF::getCache()->get('content', 'contentStructure');
		if (self::$content === null) self::$content = WCF::getCache()->get('content', 'content');
		
		self::makeContentSelect(0, 0, $hideLinks, $showInvisibleContent, $ignore);
		
		return self::$contentSelect;
	}
	
	/**
	 * Generates the content select list.
	 * 
	 */
	protected static function makeContentSelect($parentID = 0, $depth = 0, $hideLinks = false, $showInvisibleContent = false, $ignore = array()) {
		if (!isset(self::$contentStructure[$parentID])) return;
		
		foreach (self::$contentStructure[$parentID] as $contentID) {
			if (!empty($ignore) && in_array($contentID, $ignore)) continue;
			
			$content = self::$content[$contentID];
			if (!$showInvisibleContent && $content->invisible) continue;
			
			if ($hideLinks && $content->boardType == 1) continue; 
			
			// we must encode html here because the htmloptions plugin doesn't do it
			$title = WCF::getLanguage()->get(StringUtil::encodeHTML($content->title));
			if ($depth > 0) $title = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth). ' ' . $title;
			
			self::$contentSelect[$contentID] = $title;
			self::makeContentSelect($contentID, $depth + 1, $hideLinks, $showInvisibleContent, $ignore);
		}
	}
}
?>