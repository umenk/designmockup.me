<?php
use Illuminate\Support\Facades\Http;
use Faker\Factory;
use Jenssegers\Blade\Blade;
use Cocur\Slugify\Slugify;

function http()
{
	return Http::withHeaders([
		'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:68.0) Gecko/20100101 Firefox/68.0'
	]);
}

function fake()
{
    return Factory::create();
}

function blade($base = null)
{
    $base = (is_null($base)) ? base_path('templates') : $base;
	return new Blade($base, base_path('cache'));
}

function slugify($string)
{
    return mb_strtolower(str_replace(' ', '-', $string));
}

if (!function_exists('mb_ucwords'))
{
	function mb_ucwords($str)
	{
		return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
	}
}

/**
 * Encode array from latin1 to utf8 recursively
 * @param $dat
 * @return array|string
 */
function convert_from_latin1_to_utf8_recursively($dat)
{
	if (is_string($dat)) {
		return utf8_encode($dat);
	} elseif (is_array($dat)) {
		$ret = [];
		foreach ($dat as $i => $d) $ret[ $i ] = convert_from_latin1_to_utf8_recursively($d);

		return $ret;
	} elseif (is_object($dat)) {
		foreach ($dat as $i => $d) $dat->$i = convert_from_latin1_to_utf8_recursively($d);

		return $dat;
	} else {
		return $dat;
	}
}

foreach (glob('Addons/*.php') as $addon) {

}
