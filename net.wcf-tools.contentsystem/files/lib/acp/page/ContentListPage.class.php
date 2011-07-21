<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/data/content/Content.class.php');

class ContentListPage extends AbstractPage {
	public $templateName = 'contentList';
	
	public $content, $contentStructure;
	public $contentList = array();
	
	public function readData() {
		parent::readData();
		
		$this->renderContentList();
	}

	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'content' => $this->contentList
		));
	}

	protected function renderContentList() {
		$this->contentStructure = WCF::getCache()->get('content', 'contentStructure');
		$this->content = WCF::getCache()->get('content', 'content');
				
		$this->makeContentList();
	}
	
	protected function makeContentList($parentID = 0, $depth = 1, $openParents = 0) {
		if (!isset($this->contentStructure[$parentID])) return;
		
		$i = 0; $children = count($this->contentStructure[$parentID]);
		foreach ($this->contentStructure[$parentID] as $contentID) {
			$content = $this->content[$contentID];
			
			// contentlist depth on index
			$childrenOpenParents = $openParents + 1;
			$hasChildren = isset($this->contentStructure[$contentID]);
			$last = $i == count($this->contentStructure[$parentID]) - 1;
			if ($hasChildren && !$last) $childrenOpenParents = 1;
			$this->contentList[] = array('depth' => $depth, 'hasChildren' => $hasChildren, 'openParents' => ((!$hasChildren && $last) ? ($openParents) : (0)), 'content' => $content, 'parentID' => $parentID, 'position' => $i+1, 'maxPosition' => $children);
			
			// make next level of the content list
			$this->makeContentList($contentID, $depth + 1, $childrenOpenParents);
			
			$i++;
		}
	}
		
	public function show() {
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.content.contentsystem.view');
		WCF::getUser()->checkPermission('admin.content.contentsystem.canEdit');
		
		parent::show();
	}
	
}
