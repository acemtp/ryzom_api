<?php

require_once('ryzom_api/ryzom_api.php');

if(isset($_POST['key']) && $_POST['key'] != '') header('Location: ?ckey='.ryzom_encrypt($_POST['key']));

ryzom_ckey_handle();

header('Content-Type:text/html; charset=UTF-8');

echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
	<title>Character Profile</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	'.ryzom_render_header().'
	'.ryzom_render_header_www().'
	</head>
	<body>
';

if (isset($_GET['ckey']) && $_GET['ckey'] != '') {

	ryzom_log_start('example_profile');

	// Display the profile
	$ckey = $_GET['ckey'];
	$key = ryzom_decrypt($ckey);

	$uid=0;$gid=0;$slot=0;$full=false;
	if(ryzom_character_valid_key($key, $uid, $slot, $full)) {
		$xml = ryzom_character_simplexml($key, 'full');
	} else if(ryzom_guild_valid_key($key, $gid)) {
		$xml = ryzom_guild_simplexml($key);
	} else {
		$xml = ryzom_error('Not valid character or guild key', 'simplexml');
	}
	if($xml->getName() == 'error') {
		$content = '<div class="error">'.$xml.'</div>';
		$content.= '<hr/><a class="ryzom-ui-button" href="?">back</a>';
		die(ryzom_render_www(ryzom_render_window('API error', $content)));
	}

    // Display the profile
	if((string)$xml->getName()=='character') {
		$title='Character profile';
		$content=render_character($xml,$key);
	} else {
		$title='Guild profile';
		$content=render_guild($xml);
	}

	ryzom_log_end();
} else {
	// Display the form to enter the API Key
	$title = 'Profile';
	$content = '<form action="" method="post">';
	$content .= 'Please enter the API Key (guild or character) that you can find on <a href="https://secure.ryzom.com/payment_profile">your profile page</a>:</br>';
	$content .= '<input type="text" name="key"><br/>';
	$content .= '<input type="submit" value="Submit" />';
	$content .= '</form>';
}

echo ryzom_render_www(ryzom_render_window($title, $content));
echo '</body></html>';

function render_guild($xml){
	$content = '<h1>'.$xml->name.'</h1>';

	$content .= '<ul>';
	$content .= '<li>Created: <b>'.ryzom_time_txt(ryzom_time_array($xml->creation_date, '')).'</b></li>';
	$content .= '<li>Cult: <b>'.$xml->cult.'</b></li>';
	$content .= '<li>Civilization: <b>'.$xml->civ.'</b></li>';

	$guild_icon = ryzom_guild_icon_image($xml->icon, 'b');
	$guild_icon_small = ryzom_guild_icon_image($xml->icon, 's');
	$content .= "<li>Guild Icon: $guild_icon $guild_icon_small</li>";

	$content .= '</ul>';

	// read the members, then sort them by grade and name
	$result = $xml->xpath('/guild/members/*');
	$members=array();
	$s_gk=array();// multi array sort keys
	$s_nk=array();
	$key=0;
	while(list(,$item)=each($result)) {
		$members[$key]=array(
			'joined' => intval($item->joined_date), // joined_date is in server tick (ingame date)
			'grade'  => (string)$item->grade,
			'name'   => (string)$item->name,
			);
		$s_gk[$key]=memberGrade($members[$key]['grade']);
		$s_nk[$key]=$members[$key]['name'];
		$key++;
	}
	// sort members by grade, then by name
	array_multisort($s_gk, SORT_ASC, $s_nk, SORT_ASC, $members);
	$content .= '<h2>Members:'.count($members).'</h2>';
	$content .= '<table width="500px" border="1" cellpadding="1" cellspacing="0">';
	$content .= '<thead><tr><td>Name</td><td>Rank</td><td>Joined</td></thead>';
	foreach($members as $member) {
		$content .=
			'<tr>'.
			'<td>'.$member['name'].'</td>'.
			'<td>'.$member['grade'].'</td>'.
			'<td>'.ryzom_time_txt(ryzom_time_array($member['joined'], '')).'</td>'.
			'</tr>';
	}
	$content .= '</table>';

	$content.= '<hr/><a class="ryzom-ui-button" href="?">Back</a>';
	return $content;
}

/**
  * memberGrade will convert memebr grade string value to int for sorting
  *
  * @param string $grade one of 'Leader', 'HighOfficer', 'Officer', 'Member'
  * @return int
  */
function memberGrade($grade) {
	switch(strtolower($grade)) {
	case 'leader': return 0;
	case 'highofficer': return 1;
	case 'officer': return 2;
	case 'member':
	default: return 3;
	}
}

function render_character($xml, $key) {
	$content = '<h1>'.$xml->name.'</h1>';
	$content .= '<ul>';
	$content .= '<li>Shard: <b>'.$xml->shard.'</b></li>';
	$content .= '<li>Race: <b>'.$xml->race.'</b></li>';
	$gender = $xml->gender;
	$content .= '<li>Gender: <b>'.($gender=='f'?'Female':'Male').'</b></li>';
	$title = ryzom_title_txt($xml->titleid, 'en', $gender);
	$content .= '<li>Title: <b>'.$title.'</b></li>';
	$content .= '</ul>';

	$content .= '<h2>Hands</h2>';
	$result = $xml->xpath('/character/hands/*');
	while(list( , $item) = each($result))
	{
		$content .= ryzom_item_icon_image_from_simplexml($item).' ';
	}

	$content .= '<h2>Equipments</h2>';
	$result = $xml->xpath('/character/equipments/*');
	while(list( , $item) = each($result))
	{
		$content .= ryzom_item_icon_image_from_simplexml($item).' ';
	}

	if($xml->guild->name != '') {
		$content .= '<h2>Guild</h2>';
		$content .= '<ul>';
		$content .= '<li>Name: <b>'.$xml->guild->name.'</b></li>';
		$guild_icon = ryzom_guild_icon_image($xml->guild->icon, 'b');
		$guild_icon_small = ryzom_guild_icon_image($xml->guild->icon, 's');
		$content .= "<li>Guild Icon: $guild_icon $guild_icon_small</li>";
		$content .= '</ul>';
	} else {
		$content .= '<h2>No Guild</h2>';
	}

	// inventories

	$inv_xml = ryzom_character_simplexml($key, 'items');

	if(isset($inv_xml->inventories->bag)) {
		$content .= '<h2>Bag</h2>';
		$content .= '<p>(leave the mouse on an item to display its custom text, if any)</p>';
		$result = $inv_xml->xpath('/items/inventories/bag/*');
		while(list( , $item) = each($result))
		{
			$content .= ryzom_item_icon_image_from_simplexml($item);
		}
	}

	if(isset($inv_xml->item_in_store)) {
		$content .= '<h2>Item in store</h2>';
		$content .= '<p>(leave the mouse on an item to display its price)</p>';
		$result = $inv_xml->xpath('/items/item_in_store/*');
		while(list( , $item) = each($result))
		{
			$content .= ryzom_item_icon_image_from_simplexml($item, "Price: ".$item['price']);
		}
	}

	$content.= '<hr/><a class="ryzom-ui-button" href="?">Back</a>';
	return $content;
}
