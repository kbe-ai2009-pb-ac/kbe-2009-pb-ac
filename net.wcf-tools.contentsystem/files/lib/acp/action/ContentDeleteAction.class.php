<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

class ContentDeleteAction extends AbstractAction {
	public $contentID = 0;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['contentID'])) $this->contentID = intval($_REQUEST['contentID']);
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.content.contentsystem.canDelete');
				
		// delete content
		require_once(WCF_DIR.'lib/data/content/ContentEditor.class.php');
		$content = new ContentEditor($this->contentID);
		$content->delete();
		
		WCF::getCache()->clearResource('content');
		
		//redirect
		HeaderUtil::redirect('index.php?page=ContentList&deletedContentID='.$this->contentID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>
