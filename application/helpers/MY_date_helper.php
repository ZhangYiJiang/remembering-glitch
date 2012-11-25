<?php 

function glitch_time ($timestamp) {

	$weekday_name = array('Hairday','Moonday','Twoday','Weddingday','Theday','Fryday','Standday','Fabday');
	$month_name = array('Primuary','Spork','Bruise','Candy','Fever','Junuary','Septa','Remember','Doom','Widdershins','Eleventy');

	if (is_string($timestamp)) {
		$timestamp = strtotime($timestamp);
	}

	#
	# how many real seconds have elapsed since game epoch?
	#

	$sec = $timestamp - 1238562000;

	#
	# there are 4435200 real seconds in a game year
	# there are 14400 real seconds in a game day
	# there are 600 real seconds in a game hour
	# there are 10 real seconds in a game minute
	#

	$year = floor($sec / 4435200);
	$sec -= $year * 4435200;

	$day_of_year = floor($sec / 14400);
	$sec -= $day_of_year * 14400;

	$hour = floor($sec / 600);
	$sec -= $hour * 600;

	$minute = floor($sec / 10);
	$sec -= $minute * 10;

	# Pad 0s for minutes and seconds
	if ($minute < 10) $minute = '0' . $minute;
	if ($sec < 10) $sec = '0' . $sec;

	# Create 12h time
	if ($hour > 12){
		$hour -= 12;
		$hour_postfix = 'pm';
	} else {
		$hour_postfix = 'am';
	}



	#
	# turn the 0-based day-of-year into a day & month
	#

	list($month, $day_of_month) = glitch_day_to_md($day_of_year);


	#
	# get day-of-week
	#

	$days_since_epoch = $day_of_year + (307 * $year);
	$day_of_week = $days_since_epoch % 8;

	# obtain ordinal to day of month
	switch ($day_of_month % 10) {
		case 1: 
			$ordinal = 'st';
			break; 
		case 2: 
			$ordinal = 'nd';
			break;
		case 3: 
			$ordinal = 'rd';
			break;
		default: 
			$ordinal = 'th';
	}

	return "$hour:$minute $hour_postfix	 $weekday_name[$day_of_week], $day_of_month$ordinal of $month_name[$month] Year $year";
}



function glitch_day_to_md ($id){

	$months = array(29, 3, 53, 17, 73, 19, 13, 37, 5, 47, 11, 1);
	$cd = 0;

	for ($i=0; $i<count($months); $i++){
		$cd += $months[$i];
		if ($cd > $id){
			$m = $i;
			$d = $id+1 - ($cd - $months[$i]);
			return array($m, $d);
		}
	}

	return array(0, 0);
}
