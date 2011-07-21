<?php
require_once(WCF_DIR.'lib/data/content/Content.class.php');
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

class CacheBuilderContent implements CacheBuilder {
	
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array('content' => array(), 'contentStructure' => array());
		
		//Content
		$sql = "SELECT	*
				FROM 	wcf".WCF_N."_content";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['content'][$row['contentID']] = new Content(null, $row);
		}
		
		//Content Structure
		$sql = "SELECT		*
				FROM 		wcf".WCF_N."_content_structure
				ORDER BY 	parentID ASC, position ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['contentStructure'][$row['parentID']][] = $row['contentID'];
		}
		
		return $data;
	}
}
?>