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

/**
 * Verifies $key against guild format and extracts guild id from it.
 *
 * @param string $key guild api key
 * @param integer $gid will contain guild id if key seems to be valid, passed by reference
 * @return boolean false if key is invalid, true if key seems to be valid
 */
function ryzom_guild_valid_key($key, &$gid) {
	if(preg_match('/^GR(\d+)R([0-9A-F]{8})$/', $key, $matches)){
		$gid=$matches[1];
		return true;
	}else{
		return false;
	}
}

function ryzom_guild_simplexml($key) {
	$url = ryzom_api_base_url()."guild.php?key=$key";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_ENCODING , 'gzip');
	$output = curl_exec($ch);
	curl_close($ch);
	return simplexml_load_string($output);
}

function ryzom_guild_xmlgz($key) {
	return file_get_contents(ryzom_api_base_url()."guild.php?key=$key");
}

?>
