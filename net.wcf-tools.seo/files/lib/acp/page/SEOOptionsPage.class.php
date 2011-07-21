<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/system/seo/RewriteRulesFile.class.php');

class SEOOptionsPage extends AbstractPage {
	public $templateName = 'seoOptions';
	
	public function readData() {
		parent::readData();
		
		if(isset($_POST['dir'])) {
			if(isset($_POST['write'])) {
				$ressource = new RewriteRulesFile(FileUtil::addTrailingSlash($_POST['dir']).".htaccess");
				$ressource->writeRules();
				$ressource->close();
			}
			
			if(isset($_POST['delete'])) {
				SEOUtil::removeSEO(FileUtil::addTrailingSlash($_POST['dir']).".htaccess");
			}
		}
	}

	
	public function show() {
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.content.seo');
		parent::show();
	}
	
}
