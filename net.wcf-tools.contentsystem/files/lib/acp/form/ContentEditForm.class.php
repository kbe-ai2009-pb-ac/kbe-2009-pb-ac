<?php
require_once(WCF_DIR.'lib/acp/form/ContentAddForm.class.php');

class ContentEditForm extends ContentAddForm {
	public $activeMenuItem = 'wcf.acp.menu.link.content.contentsystem.view';
	public $neededPermissions = 'admin.content.contentsystem.canEdit';
	
	public $contentID;
	public static $boardStructure;
	
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['contentID'])) $this->contentID = intval($_REQUEST['contentID']);
		$this->contentEditor = new ContentEditor($this->contentID);
		
	}
	
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			
			$this->contentType					= $this->contentEditor->contentType;
			$this->title	 					= $this->contentEditor->title;
			$this->content 						= $this->contentEditor->content;
			$this->url 						= $this->contentEditor->url;
			$this->invisible					= $this->contentEditor->invisible;
			$this->parentID						= $this->contentEditor->parentID;
			
			$this->day 						= date('d', $this->contentEditor->releaseDate);
			$this->month						= date('m', $this->contentEditor->releaseDate);
			$this->year						= date('Y', $this->contentEditor->releaseDate);
			$this->hour 						= date('H', $this->contentEditor->releaseDate);
			$this->minute						= date('i', $this->contentEditor->releaseDate);
			
			// get position
			$sql = "SELECT	
					position
				FROM	
					wcf".WCF_N."_content_structure
				WHERE	
					contentID = ".$this->contentID;
			$row = WCF::getDB()->getFirstRow($sql);
			if (isset($row['position'])) $this->position = $row['position'];
		}
	}
	
	
	public function save() {
		ACPForm::save();
			
		$releaseDate = $this->getReleaseDate();
		$username = WCF::getUser()->username;
			
		$this->contentEditor->update($this->parentID, $this->contentType, $this->title, $this->content, $this->url, 1, $this->invisible, $releaseDate, $username, TIME_NOW, $this->position, $this->additionalFields);
		$this->contentEditor->removePositions();
		$this->contentEditor->addPosition($this->parentID, ($this->position ? $this->position : null));
		
		//cache
		$this->resetCache();
		
		//success
		WCF::getTPL()->assign('success', true);
	}
	
	
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'contentID' => $this->contentID,
			'action' => 'edit'
		));

	}
	
	protected function readContentOptions() {
		$this->contentOptions = Content::getContentSelect(true, true, array($this->contentID));
	}

}
