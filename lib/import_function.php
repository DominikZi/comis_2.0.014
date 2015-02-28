<?php
function import_comis($file) {
	if(is_file($file)) {
		$import=file($file);
		$type=array_shift($import);
		$type=substr($type, 1);
		if(strstr($type,"preflist")) {
			foreach($import as $conf_f) {
				$import_tmp=explode("=",$conf_f);$formatted_content[str_replace(PHP_EOL,null,$import_tmp[0])]=str_replace(PHP_EOL,null,$import_tmp[1]);
			}
		}
		elseif(strstr($type,"itemlist")) {
			$formatted_content=$import;
		}
		return $formatted_content;
	}
	else {
		return false;
	}
}