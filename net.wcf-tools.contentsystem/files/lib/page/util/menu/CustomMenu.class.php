<? 
require_once(WCF_DIR.'lib/data/content/Content.class.php');

class CustomMenu {
	public $contentStructure;
	public static $activeContentID = 0;
	public $contentArray = null;
	public $content;
	
	
	public function __construct() {
		if (isset($_REQUEST['contentID'])) self::$activeContentID = intval($_REQUEST['contentID']);
	
		$this->getContentStructure();
	}
	
	public static function setActiveContentID($contentID) {
		self::$activeContentID = intval($contentID);
	}
	
	public static function getActiveContentID($contentID) {
		return self::$activeContentID ;
	}
	
	public function getContentStructure() {
		$this->contentStructure = WCF::getCache()->get('content', 'contentStructure');
		$this->content = WCF::getCache()->get('content', 'content');
	}

	public function getContentArray($parentID = 0, $maxDepth = 0) {
		$this->contentArray = array();
		$this->clearStructure();
		$this->makeContentArray($parentID, 1, 0, $maxDepth);
		return $this->contentArray;
	}
	

	public function clearStructure($parentID = 0) {
		if (!isset($this->contentStructure[$parentID])) return;
		
		// remove invisible contents
		foreach ($this->contentStructure[$parentID] as $key => $contentID) {
			$content = $this->content[$contentID];
			if ($content->invisible || $content->releaseDate > TIME_NOW) {
				unset($this->contentStructure[$parentID][$key]);
				continue;
			}
			
			$this->clearStructure($contentID);
		}
		
		if (!count($this->contentStructure[$parentID])) {
			unset($this->contentStructure[$parentID]);
		}
	}
	
	protected function makeContentArray($parentID = 0, $depth = 1, $openParents = 0, $maxDepth = 0) {
		if (!isset($this->contentStructure[$parentID])) return;
				
		$i = 0; $children = count($this->contentStructure[$parentID]);
		foreach ($this->contentStructure[$parentID] as $contentID) {
			$content = $this->content[$contentID];
			
			$childrenOpenParents = $openParents + 1;
			
			if($maxDepth != 0 && $maxDepth <= $depth) 
				$hasChildren = false;
			else
				$hasChildren = isset($this->contentStructure[$contentID]);
			
			$last = $i == count($this->contentStructure[$parentID]) - 1;
			if ($hasChildren && !$last) $childrenOpenParents = 1;
			
			
			$active = false;			
			if(!empty(WCF::getRequest()->type))					//RequestHandler exception
				$active = $this->isActive($content->contentID);
				
			$this->contentArray[] = array('depth' => $depth, 'hasChildren' => $hasChildren, 'openParents' => ((!$hasChildren && $last) ? ($openParents) : (0)), 'content' => $content, 'parentID' => $parentID, 'position' => $i+1, 'maxPosition' => $children, 'active' => $active, 'last' => $last);
			
			// make next level of the content array
			if($maxDepth == 0 || $maxDepth > $depth) $this->makeContentArray($contentID, $depth + 1, $childrenOpenParents, $maxDepth);
			$i++;
		}
	}
	
	protected function isActive($contentID) {
		$type = WCF::getRequest()->type; 
		$match = str_replace(ucfirst($type),'',WCF::getRequest()->$type);
		
		if($contentID == self::$activeContentID)
			return true;
			
		if(WCF::getRequest()->page != "ContentPage" && strpos($this->content[$contentID]->url, $match))
			return true;
			
		if($this->searchChildren($contentID, $match))
			return true;

			
		return false;
	}
	
	protected function searchChildren($parentID, $match) {
		if (isset($this->contentStructure[$parentID])) {
			foreach ($this->contentStructure[$parentID] as $contentID) {
				if ($contentID == self::$activeContentID) 
					return true;
				
				if(WCF::getRequest()->page != "ContentPage" && strpos($this->content[$contentID]->url, $match))
					return true;
				
				if ($this->searchChildren($contentID, $match)) return true;
			}
		}
		
		return false;
	}
}

?>