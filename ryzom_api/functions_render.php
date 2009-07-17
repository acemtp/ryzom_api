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

// Call this inside the <head> part if you wan to use ryzom_render_window()
function ryzom_render_header() {
	return '<link type="text/css" href="ryzom_api/render/ryzom_ui.css" rel="stylesheet" media="all" />';
}

// Render a Ryzom style window
function ryzom_render_window($title, $content) {
	return '
		<div class="ryzom-ui ryzom-ui-header">
			<div class="ryzom-ui-tl"><div class="ryzom-ui-tr">
				<div class="ryzom-ui-t">'.$title.'</div>
			</div>
		</div>
		<div class="ryzom-ui-l"><div class="ryzom-ui-r"><div class="ryzom-ui-m">
			<div class="ryzom-ui-body">
				'.$content.'
			</div>
		</div></div></div>
		<div class="ryzom-ui-bl"><div class="ryzom-ui-br"><div class="ryzom-ui-b"></div></div></div>
		<p class="ryzom-ui-notice">powered by <a class="ryzom-ui-notice" href="http://dev.ryzom.com/projects/ryzom-api/wiki">ryzom-api</a></span>
		</div>
	';
}

// Call this inside the <head> part if you want to use ryzom_render_www
function ryzom_render_header_www() {
	return '
		<style type="text/css">
			body{background-image:url(http://www.ryzom.com/data/bg.jpg);background-repeat:no-repeat;background-color:black}
			#main{width:710px;height:300px;margin-left:auto;margin-right:auto;text-align:left}
			a, a:visited{text-decoration:none;color:#ffff11}
			a:hover{color:white}
			.error{padding:.5em;background:#ff5555;color:white;font-weight:bold;}
		</style>
	';
}

// Render a webpage using www.ryzom.com style
function ryzom_render_www($content) {
	return '
		<div id="main">
			<div style="text-align: right;">
				<a href="http://www.ryzom.com/"><img border="0" src="http://www.ryzom.com/data/logo.gif" alt=""/></a>
			</div>
			<a href="?language=en"><img hspace="5" border="0" src="http://www.ryzom.com/data/en_v6.jpg" alt="English" /></a>
			<a href="?language=fr"><img hspace="5" border="0" src="http://www.ryzom.com/data/fr_v6.jpg" alt="Français" /></a>
			<a href="?language=de"><img hspace="5" border="0" src="http://www.ryzom.com/data/de_v6.jpg" alt="Deutsch" /></a>
			'.$content.'
		</div>
	';
}

?>