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

function ryzom_guild_icon_image($icon, $size) {
	return '<img src="'.ryzom_guild_icon_url($icon, $size).'"/>';
}

function ryzom_guild_icon_url($icon, $size) {
	return ryzom_api_base_url()."guild_icon.php?icon=$icon&size=$size";
}

?>