<?php
require_once(WCF_DIR.'lib/page/MultipleLinkPage.class.php');
require_once(WCF_DIR.'lib/data/content/Content.class.php');

class ContentPage extends MultipleLinkPage {
	public $templateName = 'contentDetail';
	public $itemsPerPage = 1;
	
	public $contentID = 0;
	public $content; 
	public $pageContent;
	
	public function __construct($contentID = 0) {
		if($contentID != 0) {
			$this->contentID = $contentID;
		}
		
		require_once(WCF_DIR.'lib/page/util/menu/CustomMenu.class.php');
		CustomMenu::setActiveContentID($this->contentID);
		
		parent::__construct();
	}
	
	public function readParameters() {
		parent::readParameters();
		
		if(isset($_GET['contentID']) && intval($_GET['contentID'] != 0)) 	$this->contentID = 	intval($_GET['contentID']);
		
	}
	
	public function readData() {
		$this->content = new Content($this->contentID);
		if($this->content->invisible || $this->content->releaseDate > TIME_NOW)
			throw new IllegalLinkException();
		
		parent::readData();
		
		$this->pageContent = $this->content->getPage($this->pageNo);
	}
	
	public function countItems() {
		return $this->content->countPages();
	}
	
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'content' => $this->pageContent,
			'contentObj' => $this->content
		));
	
	}

}
?>