<?php

function build_get_url ($base_url, $args, $more=array())
{
	
	foreach ($more as $k => $v){
		$args[$k] = $v;
	}
	
	$pairs = array();
	
	foreach ($args as $k => $v){
		$pairs[] = urlencode($k).'='.urlencode($v);
	}
	
	return $base_url.'?'.implode('&', $pairs);
	
}