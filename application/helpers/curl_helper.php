<?php


function curl_http_post($url, $post_args){

	$curl_handler = curl_init();

	curl_setopt($curl_handler, CURLOPT_URL, $url);
	curl_setopt($curl_handler, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_handler, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl_handler, CURLOPT_FAILONERROR, FALSE);

	#
	# it's a post
	#

	curl_setopt($curl_handler, CURLOPT_POST, 1);
	curl_setopt($curl_handler, CURLOPT_POSTFIELDS, $post_args);


	#
	# send the request
	#

	$body = @curl_exec($curl_handler);
	$info = @curl_getinfo($curl_handler);


	#
	# close the connection
	#

	curl_close($curl_handler);


	#
	# return
	#

	return array(
		'status'	=> $info['http_code'],
		'body'		=> $body,
		'info'		=> $info,
	);
}
	