<? 
class ContentSeo {
	
	public function writeRules($file) {
		$file->write("RewriteRule ^([0-9]+)-([^/\.]*)/?$ index.php?page=Content&contentID=$1 [L,QSA]\n");
	}
	
}
?>