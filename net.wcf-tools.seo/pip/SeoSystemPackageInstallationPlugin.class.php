<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

class SeoSystemPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	public $tagName = 'seo';
	public $tableName = 'seo';
	
	/** 
	 * @see PackageInstallationPlugin::install()
	 */
	public function install() {
		parent::install();
		
		if (!$xml = $this->getXML()) {
			return;
		}
		
		// Create an array with the data blocks (import or delete) from the xml file.
		$acpMenuXML = $xml->getElementTree('data');
		
		// Loop through the array and install or uninstall acp-menu items.
		foreach ($acpMenuXML['children'] as $key => $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through acp-menu items and create or update them.
					foreach ($block['children'] as $acpMenuItem) {
						// Extract item properties.
						foreach ($acpMenuItem['children'] as $child) {
							if (!isset($child['cdata'])) continue;
							$acpMenuItem[$child['name']] = $child['cdata'];
						}
					
						// default values
						$classFile = $className = '';
						
						// get values
						if (isset($acpMenuItem['classFile'])) $classFile = $acpMenuItem['classFile'];
						if (isset($acpMenuItem['className'])) $className = $acpMenuItem['className'];
						
						
						// Update through the mysql "ON DUPLICATE KEY"-syntax. 
						$sql = "INSERT INTO			wcf".WCF_N."_seo
											(classFile, className)
							VALUES				(
											'".escapeString($classFile)."',
											'".escapeString($className)."')
							ON DUPLICATE KEY UPDATE 	
											classFile = VALUES(classFile),
											className = VALUES(className)";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
	}
	
	public function hasUninstall() {
		return 0;
	}
	
	public function uninstall() {
	}
	
}
?>