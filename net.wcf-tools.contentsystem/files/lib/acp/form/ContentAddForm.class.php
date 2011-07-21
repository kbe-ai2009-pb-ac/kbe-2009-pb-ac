<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/page/util/InlineCalendar.class.php');
require_once(WCF_DIR.'lib/data/content/ContentEditor.class.php');

class ContentAddForm extends ACPForm {
	public $activeMenuItem = 'wcf.acp.menu.link.content.contentsystem.add';
	public $neededPermissions = 'admin.content.contentsystem.canAdd';
	public $templateName = 'contentForm';
	
	public $contentEditor;
	
	public $send;
	public $contentType = 0;
	public $position;
	public $parentID = 0;
	public $parentOptions = array();
	
	public $title;
	public $content;
	public $url;
	public $active;
	public $releaseDate;
	public $invisible = 0;
	
	public $day;
	public $month;
	public $year;
	public $hour;
	public $minute;
	
	public $additionalFields = array();
	
	public function readFormParameters() {
		parent::readFormParameters();
		
		//Checkbox reset
		$this->invisible = 0;
		
		if(isset($_POST['contentType']))			$this->contentType			= intval($_POST['contentType']);
		
		if(isset($_POST['title']))				$this->title 				= StringUtil::trim($_POST['title']);
		if(isset($_POST['content']))				$this->content 				= StringUtil::trim($_POST['content']);
		if(isset($_POST['url']))				$this->url 				= StringUtil::trim($_POST['url']);
		if(isset($_POST['invisible']))				$this->invisible 			= intval($_POST['invisible']);
		if(isset($_POST['parentID']))				$this->parentID 			= intval($_POST['parentID']);
		if(!empty($_POST['position']))				$this->position 			= intval($_POST['position']);
		if(isset($_POST['send']))				$this->send 				= (boolean) $_POST['send'];
		if(isset($_POST['day']))				$this->day 				= intval($_POST['day']);
		if(isset($_POST['month']))				$this->month 				= intval($_POST['month']);
		if(isset($_POST['year']))				$this->year 				= intval($_POST['year']);
		if(isset($_POST['hour']))				$this->hour 				= intval($_POST['hour']);
		if(isset($_POST['minute']))				$this->minute 				= intval($_POST['minute']);
	}
	
	public function validate() {
		parent::validate();
		
		if(empty($this->title)) {
			throw new UserInputException('title','empty');	
		}
		
		if ($this->contentType < 0 || $this->contentType > 1) {
			throw new UserInputException('contentType', 'invalid');
		}
		
		if ($this->contentType == 1 && empty($this->url)) {
			throw new UserInputException('url');
		}
		
		$this->validateParentID();
		
	}
	
	protected function validateParentID() {
		if ($this->parentID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			try {
				Content::getContent($this->parentID);
			}
			catch (IllegalLinkException $e) {
				throw new UserInputException('parentID', 'invalid');
			}
		}
	}
	
	public function readData() {
		$this->setDateToNow();
		$this->readContentOptions();
		
		parent::readData();
	}
	
	public function setDateToNow() {
		$this->day = date('d', TIME_NOW);
		$this->month = date('m', TIME_NOW);
		$this->year = date('Y', TIME_NOW);
		$this->hour = date('H', TIME_NOW);
		$this->minute = date('i', TIME_NOW);
	}
	
	public function assignVariables() {
		parent::assignVariables();
				
		InlineCalendar::assignVariables();
		for ($i = 0; $i < 60; $i += 1) $minuteOptions[$i] = $i < 10 ? "0" . $i : $i;
				
		WCF::getTPL()->assign(array(
			'contentType' => $this->contentType,
			'title' => $this->title,
			'content' => $this->content,
			'url' => $this->url,
			'invisible' => $this->invisible,
			'position' => $this->position,
			'parentID' => $this->parentID,
			'contentOptions' => $this->contentOptions,
			
			'day' => $this->day,
			'month' => $this->month,
			'year' => $this->year,
			'hour' => $this->hour,
			'minute' => $this->minute,
			'minuteOptions' => $minuteOptions,
			
			'action' => 'add'
		));	
	}
	
	public function getReleaseDate() {
		return mktime($this->hour, $this->minute, 0, $this->month, $this->day, $this->year);
	}
		
	public function save() {
		parent::save();
		
		$releaseDate = $this->getReleaseDate();
		$username = WCF::getUser()->username;
		
		ContentEditor::create($this->parentID, $this->contentType, $this->title, $this->content, $this->url, 1, $this->invisible, $releaseDate, $username, TIME_NOW, $this->position, $this->additionalFields);
		
		//cache
		$this->resetCache();
		
		//success
		WCF::getTPL()->assign('success', true);
		
		//reset
		$this->title = $this->content = $this->url = $this->position = $this->parentID = '';
		$this->invisible = 0;
		
		
		$this->setDateToNow();
		$this->readContentOptions();
	}
	
	protected function resetCache() {
		Content::resetCache();
	}
	
	protected function readContentOptions() {
		$this->contentOptions = Content::getContentSelect(true, true);
	}
}
