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

/**
 * Verifies $key against character api key format 
 * and extracts user id, character slot and key type from it.
 *
 * @param string $key character api key
 * @param integer $uid will contain user id if key seems to be valid, passed by reference
 * @param integer $slot will contain user slot number if key seems to be valid, passed by reference
 * @param boolean $full if key type is full, this is set to TRUE, passed by reference
 * @return boolean false if key is invalid, true if key seems to be valid
 */
function ryzom_character_valid_key($key, &$uid, &$slot, &$full) {
	if(preg_match('/^(F|P)R(\d+)R([0-4])R[0-9A-F]{8}$/', $key, $matches)){
		$full=$matches[1]=='F';
		$uid=$matches[2];
		$slot=$matches[3];
		return true;
	}else{
		return false;
	}
}

?>
