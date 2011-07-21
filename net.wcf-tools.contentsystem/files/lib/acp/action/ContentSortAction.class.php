<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Saves the content structure.
 * 
 * @author	Patrick Bauer, Adrian Cieluch
 */

class ContentSortAction extends AbstractAction {
	public $data = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if(isset($_POST['list']))	$this->data = $_POST['list'];	
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		if(count($this->data)){
			require_once(WCF_DIR.'lib/data/content/ContentEditor.class.php');
			ContentEditor::sortContent($this->data);

			WCF::getCache()->clearResource('content');
		}
	}
}
?>
