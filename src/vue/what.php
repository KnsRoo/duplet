<?php
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}

$files = rglob('*.*');
$len = 0;
foreach ($files as $file) {
	$path = ltrim($file,'.');
	$len+=count(file($file));
}
echo $len." strings in ".count($files)." files";
?>
