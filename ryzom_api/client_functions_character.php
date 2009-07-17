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

function ryzom_character_simplexml($key, $part='') {
	$url = ryzom_api_base_url()."character.php?key=$key".($part!=''?"&part=$part":'');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_ENCODING , 'gzip');
	$output = curl_exec($ch);
	curl_close($ch);
	return simplexml_load_string($output);
}

function ryzom_character_xmlgz($key, $part='') {
	return file_get_contents(ryzom_api_base_url()."character.php?key=$key".($part!=''?"&part=$part":''));
}

function ryzom_character_valid_key($key, &$uid, &$slot, &$full) {
	$arr = explode("R", $key);
	if(sizeof($arr) != 4) return false;
	$full = ($arr[0]=='F');
	$uid = $arr[1];
	$slot = $arr[2];
	return true;
}

?>