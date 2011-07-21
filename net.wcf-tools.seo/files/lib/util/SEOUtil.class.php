<?php

class SEOUtil {
	public static function formatString($string) {
		$string = StringUtil::toLowerCase($string);
		$string = preg_replace('/[\x0-\x2F\x3A-\x40\x5B-\x60\x7B-\x7F\x80-\xFF]+/', '-', $string);
		$string = trim($string, '-');
		return $string;
	}
	
	public static function appendQueryString($link, $queryString) {
		if (!empty($queryString)) {
			$queryString = StringUtil::decodeHTML($queryString);
			if ($queryString[0] == '&') $queryString = '?'.substr($queryString, 1);
			$queryString = StringUtil::encodeHTML($queryString);
			$link .= $queryString;
		}
		
		return $link;
	}
	
	public static function removeSEO($filename) {
		if (file_exists($filename)) {
			$existingContent = StringUtil::unifyNewlines(file_get_contents($filename));
				
			// filter wcf seo rules
			$existingContent = preg_replace("~\n?# WCF-SEO-START.*# WCF-SEO-END~s", '', $existingContent);
			
			if (!StringUtil::trim($existingContent)) {
				@unlink($filename);
			}
			else {
				// update file
				require_once(WCF_DIR.'lib/system/io/File.class.php');
				$file = new File($filename);
				$file->write($existingContent);
				$file->close();
			}
		}
	}
}
?>