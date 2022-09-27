<?php

/** 
 * HARAM UNTUK DIJUAL LAGI
 * Created By: Jumady (https://web.facebook.com/dyvretz/)
 * Spesial Thanks: SGB TEAM & M REZA RIZALDI
**/

error_reporting(0);
date_default_timezone_set("Asia/Jakarta");

$colors = new Colors();
echo "-------------------- [".$colors->getColoredString("STARBUCK CREATOR WITH SMSHUB", "green")."] --------------------".PHP_EOL.PHP_EOL;
echo ' >> '.$colors->getColoredString("MADE 2022 WITH CUP AND LOVE", "green").''.PHP_EOL;
echo ' >> '.$colors->getColoredString("BOT BY JUMADY", "green").''.PHP_EOL;
echo ' >> '.$colors->getColoredString("DONATION: https://saweria.co/mrjumady", "green").''.PHP_EOL.PHP_EOL;

if(!file_exists("alerts.txt")) {
    $apikey = input("[ ".date('H:i:s')." ] ?| ".$colors->getColoredString("Apikey smshub", "green"));
    $durasi = input("[ ".date('H:i:s')." ] ?| ".$colors->getColoredString("OTP Waiting Time (seconds)", "green"));
    file_put_contents("alerts.txt", "off");
    file_put_contents("config.json", json_encode(['apikey' => $apikey, 'waitingTime' => $durasi]));
} 


$readConfig  = json_decode(file_get_contents("config.json"), true);
$apikey = trim($readConfig['apikey']);
$durasi = trim($readConfig['waitingTime']);

/** CHECK SALDO SMSHUB **/
$url = 'smshub.org';
$saldo = explode(':', GetBalance($url, $apikey));
$saldo = $saldo[1];
/** END CHECK SALDO SMSHUB **/
 
echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Last Credits $saldo RUB", "green").PHP_EOL;

createAgain:
$first_name = getName();
$last_name = getName();

$get_imel = curlContents("https://www.1secmail.com/api/v1/?action=genRandomMailbox&count=1");
$imelna = json_decode($get_imel,true);
$dptimel = $imelna['0'];


enterNo:
$getNumber = getNumber($url, $apikey);
if(preg_match("/ACCESS_NUMBER/", $getNumber)) {
    $exGet = explode(':', $getNumber); $idOrder = $exGet[1]; $enterNo = '+'.$exGet[2];
    echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Trying to Register By Number $enterNo", "green").PHP_EOL;
} else {
    echo "[ ".date('H:i:s')." ] !| ".$colors->getColoredString("Failed Get Numbers!!", "red").PHP_EOL;
} 

$data = '{"phoneNumber":"'.$enterNo.'"}';
$headers = [
    "user-agent: Dart/2.17 (dart:io)",
    "x-signature: 1663685232458:e75c544db1782228865f27a2f1fed11acaad1308587538bdf7546ac104fe4d3c",
    "accept-language: en",
    "content-length: ".strlen($data),
    "host: api2.sbuxcard.com",
    "Content-Type: application/json",
];
$sendOTP = curl("https://api2.sbuxcard.com//v1/auth/generate-sms-otp", $data, $headers);
$statusSendOTP = get_between($sendOTP[1], '{"status":', ',');
if ($statusSendOTP == 200) {
    echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Status 200 -> OK, Otp Successfully Send To $enterNo", "green").PHP_EOL;
    echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Waiting for OTP for $durasi Seconds", "green").PHP_EOL;
    sleep(2);
    $time = time();
    CheckUlangOTP:
    $funcOTPRegist = GetOtp($url, $apikey, $idOrder);
    $otp = explode(':', $funcOTPRegist);
    $enterOTP = $otp[1];
    if ($enterOTP) {
        $enterOTP = get_between($enterOTP, 'Your Starbucks Card Verification Code ', ' Db');
        $enterOTP = trim($enterOTP);
        echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("OTP: $enterOTP", "green").PHP_EOL;
        } else {
            if (time()-$time > $durasi) {
                echo "[ ".date('H:i:s')." ] !| ".$colors->getColoredString("Failed Get OTP!!", "red").PHP_EOL;
                $funcDeleteOtp = ChangeCancel($url, $apikey, $idOrder);
                goto enterNo;
            } else {
                goto CheckUlangOTP;
            }
        }
    $data = '{"otp":"'.$enterOTP.'","phoneNumber":"'.$enterNo.'"}';
    $headers = [
        "user-agent: Dart/2.17 (dart:io)",
        "x-signature: 1663685259200:1e4bc50096b733dab0ff47df665c4d0f571dc8eb8f2728b9846473acf160170b",
        "accept-language: en",
        "content-length: ".strlen($data),
        "host: api2.sbuxcard.com",
        "Content-Type: application/json"
    ];
    $validOTP = curl("https://api2.sbuxcard.com//v1/auth/validate-sms-otp", $data, $headers);
    $statusValidOTP = get_between($validOTP[1], '{"status":', ',');
    $messageValidOTP = get_between($validOTP[1], '"message":"', '"}');
    if ($statusValidOTP == 200) {
        echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Status 200 -> OK, $messageValidOTP", "green").PHP_EOL;
        $data = '{"email":"'.$dptimel.'","password":"Jumady05@#","external_id":null,"first_name":"'.$first_name.'","last_name":"'.$last_name.'","dob":"1999-2-21","fav_beverage":"double chocolate greentea","direct_marcomm":true,"phone_number":"'.$enterNo.'","referralCode":"MUHAMMADJ-7EA50B","otp":"'.$enterOTP.'"}';
        $headers = [
            "user-agent: Dart/2.17 (dart:io)",
            "x-signature: 1663685319338:0b8592b21c9b6448e3fa674f206ecd9750ed77e2b91d09ad2c970a6b2c79dba7",
            "accept-language: en",
            "content-length: ".strlen($data),
            "host: api2.sbuxcard.com",
            "Content-Type: application/json",
        ];
        $registered = curl("https://api2.sbuxcard.com//v1/customer/registration", $data, $headers);
        $statusRegistered = get_between($registered[1], '{"status":', ',');
        $messageRegistered = get_between($registered[1], '"message":"', '"}');
        if ($statusRegistered == 200) {
            echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Status 200 -> OK, $messageRegistered", "green").PHP_EOL;
            echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Account Created Successfully, [$first_name $last_name|$enterNo|$dptimel|Jumady05@#|https://mailaccess.mrjumady.xyz/?email=$dptimel] Check Folder log", "green").PHP_EOL;
            echo "[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Email Access: https://mailaccess.mrjumady.xyz/?email=$dptimel", "green").PHP_EOL;
            if(!is_dir("log")) mkdir("log");
            file_put_contents("log/sbuxAccount.txt", trim($first_name)." ".trim($last_name)."|".$enterNo."|".$dptimel."|Jumady05@#|https://mailaccess.mrjumady.xyz/?email=$dptimel".PHP_EOL, FILE_APPEND);
            $ulangLagi = input("[ ".date('H:i:s')." ] ?| ".$colors->getColoredString("Create Again? (y/N)", "green"));
            if (strtolower($ulangLagi) == "y") {
                goto createAgain;
            } else {
                die("[ ".date('H:i:s')." ] *| ".$colors->getColoredString("Thanks for use my bot :) see u", "green")).PHP_EOL;
            }
        } else {
            $ChangeCancel = ChangeCancel($url, $apikey, $idOrder);
            echo "[ ".date('H:i:s')." ] !| ".$colors->getColoredString("Status 401 -> Registration Failed", "red").PHP_EOL;
            goto enterNo;
        }
    } else {
        $ChangeCancel = ChangeCancel($url, $apikey, $idOrder);
        echo "[ ".date('H:i:s')." ] !| ".$colors->getColoredString("Status 401 -> Verification Code invalid", "red").PHP_EOL;
        goto enterNo;
    }
} else {
    $ChangeCancel = ChangeCancel($url, $apikey, $idOrder);
    echo "[ ".date('H:i:s')." ] !| ".$colors->getColoredString("Status 401 -> Failed to generate otp before 30 Second", "red").PHP_EOL;
    goto enterNo;
}



function GetBalance($url, $apikey) {
    $get = file_get_contents('https://'.$url.'/stubs/handler_api.php?api_key='.$apikey.'&action=getBalance');
    return $get;
}

function getNumber($url, $apikey) {
    $get = file_get_contents('https://'.$url.'/stubs/handler_api.php?api_key='.$apikey.'&action=getNumber&service=sr&operator=&country=6');
    return $get;
}

function ChangeConfirm($url, $apikey, $idOrder) {
    $get = file_get_contents('https://'.$url.'/stubs/handler_api.php?api_key='.$apikey.'&action=setStatus&status=6&id='.$idOrder);
    return $get;
}

function RetrySMS($url, $apikey, $idOrder) {
    $get = file_get_contents('https://'.$url.'/stubs/handler_api.php?api_key='.$apikey.'&action=setStatus&status=3&id='.$idOrder);
    return $get;
}

function ChangeCancel($url, $apikey, $idOrder) {
    $get = file_get_contents('https://'.$url.'/stubs/handler_api.php?api_key='.$apikey.'&action=setStatus&status=8&id='.$idOrder);
    return $get;
}

function GetOtp($url, $apikey, $idOrder) {
    $get = file_get_contents('https://'.$url.'/stubs/handler_api.php?api_key='.$apikey.'&action=getStatus&id='.$idOrder);
    return $get;
}

function input($text) {
    echo $text.": ";
    $a = trim(fgets(STDIN));
    return $a;
}


function get_between($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function getName() {
    $r = file_get_contents('https://www.random-name-generator.com/indonesia?gender=&n=1&s='.rand(111,999));
    $namenya = get_between($r,'<div class="col-sm-12 mb-3" id="','-');
    $nama_indo = preg_replace('/s+/', '', $namenya);
    return ucfirst($nama_indo);
}

function curlContents($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function curl($url, $post = 0, $httpheader = 0, $proxy = 0) { 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    if($post){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if($httpheader){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    }
    if($proxy){
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    }
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if(!$httpcode) return "Curl Error : ".curl_error($ch); else{
        $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array($header, $body);
    }
}

function curlget($url,$post,$headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $headers == null ? curl_setopt($ch, CURLOPT_POST, 1) : curl_setopt($ch, CURLOPT_HTTPGET, 1);
    if ($headers !== null) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    $result = curl_exec($ch);
    $header = substr($result, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    $body = substr($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
    preg_match_all("/^Set-Cookie:\s*([^;]*)/mi", $result, $matches);
    $cookies = array();	
    foreach($matches[1] as $item) {
        parse_str($item, $cookie);
        $cookies = array_merge($cookies, $cookie);
    }
    return array (
        $header,
        $body,
        $cookies
    );
}

class Colors {
    private $foreground_colors = array();
    private $background_colors = array();

    public function __construct() {
        // Set up shell colors
        $this->foreground_colors['black'] = '0;30';
        $this->foreground_colors['dark_gray'] = '1;30';
        $this->foreground_colors['blue'] = '0;34';
        $this->foreground_colors['light_blue'] = '1;34';
        $this->foreground_colors['green'] = '0;32';
        $this->foreground_colors['light_green'] = '1;32';
        $this->foreground_colors['cyan'] = '0;36';
        $this->foreground_colors['light_cyan'] = '1;36';
        $this->foreground_colors['red'] = '0;31';
        $this->foreground_colors['light_red'] = '1;31';
        $this->foreground_colors['purple'] = '0;35';
        $this->foreground_colors['light_purple'] = '1;35';
        $this->foreground_colors['brown'] = '0;33';
        $this->foreground_colors['yellow'] = '1;33';
        $this->foreground_colors['light_gray'] = '0;37';
        $this->foreground_colors['white'] = '1;37';

        $this->background_colors['black'] = '40';
        $this->background_colors['red'] = '41';
        $this->background_colors['green'] = '42';
        $this->background_colors['yellow'] = '43';
        $this->background_colors['blue'] = '44';
        $this->background_colors['magenta'] = '45';
        $this->background_colors['cyan'] = '46';
        $this->background_colors['light_gray'] = '47';
    }

    // Returns colored string
    public function getColoredString($string, $foreground_color = null, $background_color = null) {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return $colored_string;
    }

    // Returns all foreground color names
    public function getForegroundColors() {
        return array_keys($this->foreground_colors);
    }

    // Returns all background color names
    public function getBackgroundColors() {
        return array_keys($this->background_colors);
    }
}
