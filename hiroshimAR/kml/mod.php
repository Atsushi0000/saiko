<?php

$directory_path = "./newKml";
if(file_exists($directory_path)){
    echo "A directory already exists<br />";
}else{
    if(mkdir($directory_path, 0777)){
        chmod($directory_path, 0777);
        echo "I made a directory";
    }else{
        echo "I couldn't make a directory";
    }
}

$list;

$files = glob('*.kml');
$list = array();
foreach ($files as $file) {
    if (is_file($file)) {
        $list[] = $file;
    }
    if (is_dir($file)) {
        $list = array_merge($list, getFileList($file));
    }
}

foreach ($list as $value){
	$filePath = $value;

	$xml = simplexml_load_file($filePath);
	echo 'A KML file "' . $value . '" is loaded' . '<br />';

	foreach ($xml->Document->Folder->Placemark as $placemark) {
		$coordinates = explode(',',$placemark->Point->coordinates);
		$lat = $coordinates[1];
		$lon = $coordinates[0];		
		$descriptionOriginal = $placemark->description;
		$descriptionNew = (string)($descriptionOriginal . '<p id="routeButton"><a href="http://maps.apple.com/maps?saddr=&daddr=' . $lat . ',' . $lon . '&z=16"><img src="http://hiroshima.archiving.jp/data/ios/navi.png" class="route"></a></p>');
		$placemark->description = $descriptionNew;
	}
		$newfileName = "newKml/" . $filePath;
		$xml->asXml($newfileName);
	echo 'A modified KML file is saved' . '<br />';	
}

?>