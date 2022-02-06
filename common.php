<?php
$json = json_decode(file_get_contents('preferences.json'), true);
$nsfw = [];

foreach($json['tagFilters'] as $filter)
{
	if($filter['extreme'])
	{
		array_push($nsfw, ... $filter['tags']);
	}
}

function isExtreme($game, $nsfw)
{
	$tags = explode('; ', $game['tagsStr']);
	foreach($tags as $tag)
	{
		if(in_array($tag, $nsfw))
		{
			return true;
		}
	}
	
	return false;
}
