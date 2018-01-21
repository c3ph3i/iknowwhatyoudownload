<?php

require_once "inc/commonjs-ansi-color/lib/ansi-color.php";

use PhpAnsiColor\Color;
echo "
 ___   _  ___   _  _____        __ __        ___   _    _  _____ 
|_ _| | |/ / \ | |/ _ \ \      / / \ \      / / | | |  / \|_   _|
 | |  | ' /|  \| | | | \ \ /\ / /   \ \ /\ / /| |_| | / _ \ | |  
 | |  | . \| |\  | |_| |\ V  V /     \ V  V / |  _  |/ ___ \| |  
|___| |_|\_\_| \_|\___/  \_/\_/       \_/\_/  |_| |_/_/   \_\_|  
                                                                 
__   _____  _   _   ____   _____        ___   _ _     ___    _    ____  
\ \ / / _ \| | | | |  _ \ / _ \ \      / / \ | | |   / _ \  / \  |  _ \ 
 \ V / | | | | | | | | | | | | \ \ /\ / /|  \| | |  | | | |/ _ \ | | | |
  | || |_| | |_| | | |_| | |_| |\ V  V / | |\  | |__| |_| / ___ \| |_| |
  |_| \___/ \___/  |____/ \___/  \_/\_/  |_| \_|_____\___/_/   \_\____/ 
                                                                        
 ______   __   ____ _____ ____  _   _ ________ 
| __ ) \ / /  / ___|___ /|  _ \| | | |___ /_ _|
|  _  \\ V /  | |     |_ \| |_) | |_| | |_ \| | 
| |_) || |   | |___ ___) |  __/|  _  |___) | | 
|____/ |_|    \____|____/|_|   |_| |_|____/___|


I: IKnowWhatYouDownload By Cephei - Check Torrent downloads for last 30 days by User IP

_____________________________________________________________________________


";


//Getting IP address from user
$ip = readline("Enter IP to check downloads: ");

echo"
_____________________________________________________________________________

";

$ch = curl_init();

$request_uri = "https://api.antitor.com/history/peer?ip=". $ip ."&days=30&lang=en&key=51413faacf4b487394509cb6afd9170d";

curl_setopt($ch, CURLOPT_URL, $request_uri);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = json_decode(curl_exec ($ch));

//If we don't have any errors
if($server_output){
	if(!empty($server_output->contents)){
		$server_output->hasPorno = ($server_output->hasPorno) ? 'True' : 'False';
		$server_output->hasChildPorno = ($server_output->hasChildPorno) ? 'True' : 'False';
		echo PHP_EOL . Color::set("Information", "green+bold") . PHP_EOL;
		echo Color::set("ISP: ", "white+bold") . $server_output->isp . PHP_EOL;
		echo Color::set("Has porn: ", "white+bold") . $server_output->hasPorno . PHP_EOL;
		echo Color::set("Has child porn: ", "white+bold") . $server_output->hasChildPorno . PHP_EOL . PHP_EOL;

		if(isset($server_output->geoData)){
			echo Color::set("GEODATA", "green+bold") . PHP_EOL;
			echo Color::set("Country: ", "white+bold") . $server_output->geoData->country . PHP_EOL;
			echo Color::set("Latitude: ", "white+bold") . $server_output->geoData->latitude . PHP_EOL;
			echo Color::set("Logitude: ", "white+bold") . $server_output->geoData->longitude . PHP_EOL . PHP_EOL;
		}

		echo Color::set("Downloads", "green+bold") . PHP_EOL;
		$mask = "|%-8s |%-50s | %-30s | %-10s | %-8s |\n";
		printf($mask, 'Category', 'Name', 'Date', 'Size', 'Child porn');			
		foreach($server_output->contents as $download){
			printf($mask, 
				$download->category,
				$download->name, 
				$download->startDate, 
				$download->torrent->size,
				($download->childPorno) ? 'True' : 'False'
);			
		}
	}
	else
		echo Color::set("I: We couldn't find any downloads from this IP address", "blue+bold") . PHP_EOL;

}

//If we have errors in the response
else
	echo Color::set("E: Something went wrong. ", "red+bold") . PHP_EOL;

curl_close ($ch);


echo PHP_EOL;

