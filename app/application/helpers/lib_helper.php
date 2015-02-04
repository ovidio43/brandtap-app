<?php

function dump($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function download_str($string, $filename, $contentType = 'application/octet-stream')
{
    header('Content-Description: File Transfer');
	header("Content-Type: $contentType");
//		header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=$filename");
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma:  public');
	header('Content-Length: ' . strlen($string));
	
	echo $string;
}

function download_file($path, $filename)
{
    if( ! file_exists($path)){
        echo "File not exists: $filename path=?";
        return FALSE;
    }
   	header('Content-Disposition: attachment; filename=' . basename($filename)); 
//	header('Content-Type: plain/text'); 
    header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary'); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
	
	readfile($path);
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function logv2($file,$msg)
{
    $log_row = "[ " . date('Y-m-d H:i:s') . "] $msg \n";
    $log_file = "media/logsv2/$file" . '.log';
    file_put_contents($log_file, $log_row, FILE_APPEND);
    @ chmod($log_file, 0777);
//echo 'preventing redirect temporary...';
    return true;
}


/**
* Used for menu marking as active...
**/
function set_active($page)
{
    $url = current_url();
    $is_active = substr($url, strlen($page) - (2*strlen($page)) ) == $page;

    return $is_active ? ' menu-active ' : "";
}

function send_email($to,$subject,$message)
{
    $header  = "From: " . FROM_EMAIL . "\r\n";
    $header .= "BCC: " . BCC_EMAIL . "\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $mail_success = mail($to, $subject, $message, $header);

    return $mail_success;
}