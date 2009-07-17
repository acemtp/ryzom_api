<?php
/* Copyright (C) 2009 Winch Gate Property Limited
 *
 * This file is part of ryzom_api.
 * ryzom_api is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ryzom_api is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with ryzom_api.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('functions_aes.php');

function ryzom_generate_passphrase() {
	return md5($_SERVER['SCRIPT_FILENAME'].$_SERVER['SERVER_ADMIN'].$_SERVER['DOCUMENT_ROOT']);
}

function ryzom_encrypt($key, $passphrase='') {
	if($passphrase=='') $passphrase = ryzom_generate_passphrase();
	return rtrim(strtr(AESEncryptCtr($key, $passphrase, 256), '+/', '-_'), '=');
}

function ryzom_decrypt($key, $passphrase='') {
	if($passphrase=='') $passphrase = ryzom_generate_passphrase();
	return AESDecryptCtr(strtr($key, '-_', '+/'), $passphrase, 256);
}

function ryzom_ckey_handle($passphrase='') {
	if(isset($_GET['get_ckey'])) {
		die(ryzom_encrypt($_GET['get_ckey'], $passphrase));
	}
}

function ryzom_api_base_url() {
	return 'http://atys.ryzom.com/api/';
}

function ryzom_display_xml_header() {
	header('Content-Type: application/xml; charset=UTF-8');
}

function ryzom_die($err, $format='txt') {
	die(ryzom_error($err, $format));
}

function ryzom_error($err, $format='txt') {
	switch($format) {
	case 'txt':
		$res = $err;
		break;
	case 'simplexml':
		$res = simplexml_load_string("<error>$err</error>");
		break;
	case 'xml':
		$res = ryzom_error($err, 'simplexml')->asXML();
		break;
	case 'xmlgz':
		$res = gzencode(ryzom_error($err, 'xml'));
		break;
	}
	return $res;
}

function ryzom_bench_start() {
	return microtime(true);
}

function ryzom_bench_end($begin_bench, $text) {
	$dt = intval((microtime(true) - $begin_bench)*1000);
	ryzom_log("$text: $dt ms");
}

// for PHP4 from http://us.php.net/manual/en/function.file-put-contents.php#68329

if ( !function_exists('file_put_contents') && !defined('FILE_APPEND') ) {
	define('FILE_APPEND', 1);
	function file_put_contents($n, $d, $flag = false) {
		$mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'w';
		$f = @fopen($n, $mode);
		if ($f === false) {
			return 0;
		} else {
			if (is_array($d)) $d = implode($d);
			$bytes_written = fwrite($f, $d);
			fclose($f);
			return $bytes_written;
		}
	}
}

$ryzom_bench = 0;
$ryzom_fn = false;
function ryzom_log_start($api_name) {
	global $ryzom_bench, $ryzom_fn;
	if(!file_exists('log')) mkdir('log');
	$ryzom_fn = "log/$api_name.log";
	$ryzom_bench = ryzom_bench_start();
}

function ryzom_log_end() {
	global $ryzom_bench;
	ryzom_bench_end($ryzom_bench, 'total');
}

function ryzom_log($str) {
	global $ryzom_fn;
	if($ryzom_fn!==false) file_put_contents($ryzom_fn, date("Y-m-d H:i:s") .' '. $_SERVER['REMOTE_ADDR'] .' '. $_SERVER['REQUEST_URI'] ." $str\n", FILE_APPEND);
}

?>