<?php
require_once(WCF_DIR.'lib/data/content/Content.class.php');

class ContentEditor extends Content {

	public function __construct($contentID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($contentID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_content
				WHERE	contentID = ".$contentID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}	
	 
	public function removePositions() {
		// unshift content
		$sql = "SELECT 	parentID, position
			FROM	wcf".WCF_N."_content_structure
			WHERE	contentID = ".$this->contentID;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$sql = "UPDATE	wcf".WCF_N."_content_structure
				SET	position = position - 1
				WHERE 	parentID = ".$row['parentID']."
					AND position > ".$row['position'];
			WCF::getDB()->sendQuery($sql);
		}
		
		// delete content
		$sql = "DELETE FROM	wcf".WCF_N."_content_structure
			WHERE		contentID = ".$this->contentID;
		WCF::getDB()->sendQuery($sql);
	}
	

	public static function sortContent($data){
		$position = array();

		foreach ($data as $contentID => $parentID) {
			if(!isset($position[intval($parentID)]))
				$position[intval($parentID)] = 0;

			self::updatePosition(intval($contentID), intval($parentID), ++$position[intval($parentID)]);
		}	
	}
	
	public static function updatePosition($contentID, $parentID, $position) {
		$sql = "UPDATE	wcf".WCF_N."_content
			SET	parentID = ".$parentID."
			WHERE 	contentID = ".$contentID;
		WCF::getDB()->sendQuery($sql);
		
		$sql = "REPLACE INTO	wcf".WCF_N."_content_structure
					(contentID, parentID, position)
			VALUES		(".$contentID.", ".$parentID.", ".$position.")";
		WCF::getDB()->sendQuery($sql);
	}

	public function addPosition($parentID, $position = null) {
		// shift content
		if ($position !== null) {
			$sql = "UPDATE	wcf".WCF_N."_content_structure
				SET	position = position + 1
				WHERE 	parentID = ".$parentID."
					AND position >= ".$position;
			WCF::getDB()->sendQuery($sql);
		}
		
		// get final position
		$sql = "SELECT 	IFNULL(MAX(position), 0) + 1 AS position
			FROM	wcf".WCF_N."_content_structure
			WHERE	parentID = ".$parentID."
				".($position ? "AND position <= ".$position : '');
		$row = WCF::getDB()->getFirstRow($sql);
		$position = $row['position'];
		
		// save position
		$sql = "INSERT INTO	wcf".WCF_N."_content_structure
					(parentID, contentID, position)
			VALUES		(".$parentID.", ".$this->contentID.", ".$position.")";
		WCF::getDB()->sendQuery($sql);
	}
	
	
	public function delete() {
		$this->removePositions();
		
		$sql = "UPDATE	wcf".WCF_N."_content
			SET	parentID = ".$this->parentID."
			WHERE	parentID = ".$this->contentID;
		WCF::getDB()->sendQuery($sql);
		
		$sql = "UPDATE	wcf".WCF_N."_content_structure
			SET	parentID = ".$this->parentID."
			WHERE	parentID = ".$this->contentID;
		WCF::getDB()->sendQuery($sql);
		
		
		self::deleteData($this->contentID);
	}
	
	public static function deleteData($contentIDs) {
		$sql = "DELETE FROM	wcf".WCF_N."_content
			WHERE		contentID IN (".$contentIDs.")";
		WCF::getDB()->sendQuery($sql);
	}
	
	public static function create($parentID, $contentType, $title, $content, $url = '', $active, $invisible, $releaseDate, $username, $lastChangedDate, $position, $additionalFields = array()) {
		$contentID = self::insert($title, array_merge($additionalFields, array(
			'parentID' => $parentID,
			'contentType' => $contentType,
			'content' => $content,
			'url' => $url,
			'active' => $active,
			'invisible' => $invisible,
			'releaseDate' => $releaseDate,
			'username' => $username,
			'lastChangedDate' => $lastChangedDate
		)));
		
		$content = new ContentEditor($contentID, null, null, false);
		$content->addPosition($parentID, $position);
		
		return $content;
	}
	
	
	public function update($parentID, $contentType, $title, $content, $url = '', $active, $invisible, $releaseDate, $username, $lastChangedDate, $position, $additionalFields = array()) {
		$updates = '';
		
		$additionalFields = array_merge($additionalFields, array(
			'parentID' => $parentID,
			'contentType' => $contentType,
			'content' => $content,
			'url' => $url,
			'active' => $active,
			'invisible' => $invisible,
			'releaseDate' => $releaseDate,
			'username' => $username,
			'lastChangedDate' => $lastChangedDate
		));
		
		foreach ($additionalFields as $key => $value) {
			$updates .= ','.$key."='".escapeString($value)."'";
		}
		
		$sql = "UPDATE	wcf".WCF_N."_content
			SET	title = '".escapeString($title)."'
				".$updates."
			WHERE	contentID = ".$this->contentID;
		WCF::getDB()->sendQuery($sql);
	}

	
	public static function insert($title, $additionalFields = array()) { 
		$keys = $values = '';
		foreach ($additionalFields as $key => $value) {
			$keys .= ','.$key;
			$values .= ",'".escapeString($value)."'";
		}
		
		$sql = "INSERT INTO	wcf".WCF_N."_content
					(title
					".$keys.")
			VALUES		('".escapeString($title)."'
					".$values.")";
		WCF::getDB()->sendQuery($sql);
		return WCF::getDB()->getInsertID();
	}

}
?>