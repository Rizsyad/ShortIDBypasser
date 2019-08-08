<?php
ob_clean();

class LinkBypasserShortID 
{

	public function get_header($url) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "indosecbot/0.1 (https://www.indsc.me/home/) ");
		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//enable headers
		curl_setopt($ch, CURLOPT_HEADER, true);
		//get only headers
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		// $output contains the output string
		$output = curl_exec($ch);
		curl_close($ch);

		$headers = [];
		$output = rtrim($output);
		$data = explode("\n",$output);
		$cookie = $data[6];
		$dapatcookie = explode(":", $cookie);

		return $dapatcookie[1];

	}

	public function get_link_visited($url) 
	{
		$data = file_get_contents($url);
		preg_match('/<a href="(.*)" rel="nofollow">(.*)<\/a>/mi', $data, $matches, PREG_OFFSET_CAPTURE, 0);
		return "https://zanbooredana.com".$matches[1][0];
	}

	public function showing_link($urls,$cookie) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urls);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, "indosecbot/0.1");
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$execute = curl_exec($ch);
		curl_close($ch);
		
		preg_match('/content="0;URL=(.*)">/mi', $execute, $matches, PREG_OFFSET_CAPTURE, 0);

		return $matches[1][0];
	}
}

// ex. https://shortid.co/YQMxj

$class = new LinkBypasserShortID();

echo "\n [?] Masukan Link ShortID ( ex. https://shortid.co/YQMxj ): ";
$input = trim(fgets(STDIN, 1024));
echo "\n [!] Loading.....";
sleep(5);
echo "\n [!] Prepare to get real link.....";
$cookie = $class->get_header($input); // masukan linknya untuk mendapatkan cookie
$shotlink = $class->get_link_visited($input); // masukan link nya
sleep(5);
echo "\n [!] Please wait, almost to get real link.....";
sleep(3);
$show = $class->showing_link($shotlink,$cookie);
if ($show != "") 
{
	echo "\n [+] Success to Bypassed: ".$show."\n\n";
} 
else 
{
	echo "\n [-] Can't to Bypassed :( \n\n";
}
