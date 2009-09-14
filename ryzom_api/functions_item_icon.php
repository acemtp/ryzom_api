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

function ryzom_item_icon_image($sheetid, $c=-1, $q=-1, $s=-1, $sap=-1, $destroyed=false) {
	return '<img src="'.ryzom_item_icon_url($sheetid, $c, $q, $s, $sap, $destroyed).'"/>';
}

function ryzom_item_icon_url($sheetid, $c=-1, $q=-1, $s=-1, $sap=-1, $destroyed=false) {
	$common=ryzom_api_base_url()."item_icon.php?sheetid=$sheetid&c=$c";
	if(preg_match('/\.sbrick$/', $sheetid)) {
		$common.=($q===true ? '&lvl=1' : '').($s!=-1 ? "&txt=".urlencode($s) : '');
	} else {
		$common.="&q=$q&s=$s&sap=$sap".($destroyed?'&destroyed=1':'');
	}
	return $common;
}

function ryzom_item_icon_image_from_simplexml($item, $add_text='') {
	$c = ($item['c'] != '') ? $item['c']:-1;
	$s = ($item['s'] != '') ? $item['s']:-1;
	$q = ($item['q'] != '') ? $item['q']:-1;
	$sap = ($item['sap'] != '') ? $item['sap']:-1;
	$text = $item['text'];
	$text = str_replace('%mfc', '', $text);
	$text = str_replace("\n", ' ', $text);
//	$text = htmlentities($text);
	if($add_text != '') {
		$text = ($text!='')?"$add_text - $text":$add_text;
	}
	$url = ryzom_item_icon_url($item, $c, $q, $s, $sap);
	return "<img title=\"$text\" src=\"$url\"/> ";
}

?>