<?php
namespace common\helpers;

/**
 * Description of Strings
 *
 * @author maf
 */
class Strings {

    public static function getHumanDate($date)
    {
    	$day = '';
        switch (date('l', strtotime($date))) {
	    	case 'Sunday': $day = 'Minggu'; break;
	    	case 'Monday': $day = 'Senin'; break;
	    	case 'Tuesday': $day = 'Selasa'; break;
	    	case 'Wednesday': $day = 'Rabu'; break;
	    	case 'Thursday': $day = 'Kamis'; break;
	    	case 'Friday': $day = 'Jumat'; break;
	    	case 'Saturday': $day = 'Sabtu'; break;
        }
        return $day.', '.date('d-m-Y', strtotime($date));
    }

    public static function getHumanDatetime($datetime)
    {
    	$day = '';
        switch (date('l', strtotime($datetime))) {
	    	case 'Sunday': $day = 'Minggu'; break;
	    	case 'Monday': $day = 'Senin'; break;
	    	case 'Tuesday': $day = 'Selasa'; break;
	    	case 'Wednesday': $day = 'Rabu'; break;
	    	case 'Thursday': $day = 'Kamis'; break;
	    	case 'Friday': $day = 'Jumat'; break;
	    	case 'Saturday': $day = 'Sabtu'; break;
        }
        return $day.', '.date('d-m-Y', strtotime($datetime)).' jam '.date('h:i:s', strtotime($datetime));
    }

    public static function getMonthName($date) {
		$month = '';
		switch (date('m', strtotime($date))) {
		    case '01': $month = 'Januari'; break;
		    case '02': $month = 'Februari'; break;
		    case '03': $month = 'Maret'; break;
		    case '04': $month = 'April'; break;
		    case '05': $month = 'Mei'; break;
		    case '06': $month = 'Juni'; break;
		    case '07': $month = 'Juli'; break;
		    case '08': $month = 'Agustus'; break;
		    case '09': $month = 'September'; break;
		    case '10': $month = 'Oktober'; break;
		    case '11': $month = 'November'; break;
		    case '12': $month = 'Desember'; break;
		}
		return $month;    	
    }

    public static function getFullDate($date) {
		$month = '';
		switch (date('m', strtotime($date))) {
		    case '01': $month = 'Januari'; break;
		    case '02': $month = 'Februari'; break;
		    case '03': $month = 'Maret'; break;
		    case '04': $month = 'April'; break;
		    case '05': $month = 'Mei'; break;
		    case '06': $month = 'Juni'; break;
		    case '07': $month = 'Juli'; break;
		    case '08': $month = 'Agustus'; break;
		    case '09': $month = 'September'; break;
		    case '10': $month = 'Oktober'; break;
		    case '11': $month = 'November'; break;
		    case '12': $month = 'Desember'; break;
		}
		return date('d', strtotime($date)).' '.$month.' '.date('Y', strtotime($date));
    }

    public static function getRupiah($angka) {
    	return 'Rp. '. number_format($angka, "0", ",", ".");
    }

	public static function sluggify($str, $replace=array(), $delimiter='-') {
		if ( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		
		return $clean;
	}

	public static function timeAgo($time_ago) {
		date_default_timezone_set('Asia/Jakarta');
		$time_ago =  strtotime($time_ago) ? strtotime($time_ago) : $time_ago;
		$time  = time() - $time_ago;
		switch($time):
			case $time <= 60;
				return 'recently';
				// return 'less than a minute ago';
			case $time >= 60 && $time < 3600;
				return (round($time/60) == 1) ? 'a minute ago' : round($time/60).' minutes ago';
				// return (round($time/60) == 1) ? 'a minute' : round($time/60).' minutes ago';
			case $time >= 3600 && $time < 86400;
				return (round($time/3600) == 1) ? 'an hour ago' : round($time/3600).' hours ago';
				// return (round($time/3600) == 1) ? 'a hour ago' : round($time/3600).' hours ago';
			case $time >= 86400 && $time < 604800;
				return (round($time/86400) == 1) ? 'a day ago' : round($time/86400).' days ago';
				// return (round($time/86400) == 1) ? 'a day ago' : round($time/86400).' days ago';
			case $time >= 604800 && $time < 2600640;
				return (round($time/604800) == 1) ? 'a week ago' : round($time/604800).' weeks ago';
				// return (round($time/604800) == 1) ? 'a week ago' : round($time/604800).' weeks ago';
			case $time >= 2600640 && $time < 31207680;
				return (round($time/2600640) == 1) ? 'a month ago' : round($time/2600640).' months ago';
				// return (round($time/2600640) == 1) ? 'a month ago' : round($time/2600640).' months ago';
			case $time >= 31207680;
				return (round($time/31207680) == 1) ? 'a year ago' : round($time/31207680).' years ago';
				// return (round($time/31207680) == 1) ? 'a year ago' : round($time/31207680).' years ago' ;
		endswitch;
	}

	public static function sortText($text, $max)
	{
		if (strlen($text) > $max) {
            return substr($text, 0, $max).' ...';
        } else {
            return $text;
        }
	}


}