<?php
/**
 * Azzet v0.1
 *
 * Fabien Sa - 2012
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0
 */

// Default config
$conf = array(
	'debug' => false,
	'dir' => './',
	'type' => 'css',
	'ext' => '',
	'min' => false
);
// ----

if(isset($_GET['js'])) $conf['type'] = 'js';
if(isset($_GET['dir'])) $conf['dir'] = rtrim($_GET['dir'], '/') . '/';
if(isset($_GET['type'])) $conf['type'] = $_GET['type'];
if(isset($_GET['ext'])) $conf['ext'] = ltrim($_GET['ext'], '.');
if(isset($_GET['min'])) $conf['min'] = true;
if(isset($_GET['debug'])) $conf['debug'] = true;

if($conf['type'] === 'js') $conf['type'] = 'javascript';
if($conf['ext'] !== '') $conf['ext'] = '.' . $conf['ext'];

if(strtolower($conf['type']) === 'css') {
	header('Content-type: text/css; charset: utf-8');
}
elseif(in_array(strtolower($conf['type']) , array('json', 'javascript', 'xml', 'zip', 'gzip'))) {
	header('Content-type: application/javascript; charset: utf-8');
}

$thisName = basename(__FILE__);
$pathInfo = strstr(getCleanURI(), $thisName);
$pathInfo = substr($pathInfo, strlen($thisName));

$compressCSS = $conf['type'] === 'css' && $conf['min'];

echo '/* azzet ' . time() . " */ \n";

if($compressCSS) ob_start("compressCSS");
includeFilesFromURI($pathInfo);
if($compressCSS) ob_end_flush();


// -- Functions --

/**
 * Compress CSS
 * Remove comments, tabs, spaces, etc.
 */
function compressCSS($buffer) {
	// Remove comments
	$buffer = preg_replace('#/\*[^*]*\*+([^/][^*]*\*+)*/#', '', $buffer);
	// Remove tabs, spaces, etc.
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '  '), '', $buffer);
	$buffer = preg_replace('/ *([:{}>,*;]) */', '\1', $buffer);
	return $buffer;
}

/**
 * Call includeFile with files paths from URI string
 */
function includeFilesFromURI($pathInfo) {
	$pathPartArray = explode('&', trim($pathInfo, '&'));

	foreach ($pathPartArray as $part) {
		$part = trim($part);
		preg_match("/^\/?([^\[]*)\[([^\]]+)\]$/", $part, $matches);

		// Multiple files
		if(3 === count($matches)) {
			$files = preg_split('/[,|;]/', $matches[2]);
			if(!empty($files)) {
				for ($i = 0; $i < count($files); ++$i) {
					$base = '';
					if($matches[1] !== '') {
						$base = rtrim($matches[1], '/ ') . '/';
					}

					$path = $base . trim($files[$i]);
					includeFile($path);
				}
			}
		// Single file
		} else {
			$part = ltrim($part, '/');
			includeFile($part);
		}
	}
}

/**
 * Include file if exists, else call error
 */
function includeFile($path) {
	global $conf;
	if(is_readable($conf['dir'] . $path . $conf['ext'])) {
		include $conf['dir'] . $path . $conf['ext'];
		echo "\n";
	} else {
		error("Don't find '$path'");
	}
}

/**
 * Return clean URI without host, base path and options
 */
function getCleanURI() {
	$cleanURI = str_replace(array('%5B', '%5D', '%20'), array('[', ']', ' '), $_SERVER['REQUEST_URI']);

	$pos = strrpos($cleanURI, "?"); // Split $_GET
	if ($pos !== false) {
		$cleanURI = substr($cleanURI, 0, $pos);
	}

	return $cleanURI;
}

/**
 * Show error if debug is true
 */
function error($message) {
	global $conf;
	if($conf['debug'])
		echo "\n\n/*!! " . $message . " !!*/\n\n";
}
