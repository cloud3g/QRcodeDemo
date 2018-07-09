<?php
include 'phpqrcode.php'; 

$value = $_GET['text'];//二维码内容 

$logo = $_GET['logo'];
$errorCorrectionLevel = 'H';//容错级别

if(isset($_GET['size'])){
	$matrixPointSize = intval($_GET['size']);
}else{
	$matrixPointSize = 8;//生成图片大小 
}

$urlarr = parse_url($value);
$host = $urlarr['host'];

//第一种
//$host = substr($host,-8);

//第二种
preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
$host = $matches[0];

//if($host =='qr.com')
//{
	//生成二维码图片 
	//$m = QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
	//echo $m;
	//$logo = 'logo.png';//准备好的logo图片 
	//$QR = 'qrcode.png';//已经生成的原始二维码图

    $QR = QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
    
	if (isset($logo)) {
		//$QR = imagecreatefromstring(file_get_contents($QR)); 
		$QR = QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
		$logo = imagecreatefromstring(file_get_contents($logo)); 
		$QR_width = imagesx($QR);//二维码图片宽度 
		$QR_height = imagesy($QR);//二维码图片高度 
		$logo_width = imagesx($logo);//logo图片宽度 
		$logo_height = imagesy($logo);//logo图片高度 
		$logo_qr_width = $QR_width / 3; 
		$logo_qr_height = $QR_height / 3;
		$scale = $logo_width/$logo_qr_width; 
		$logo_qr_height = $logo_height/$scale;
		$from_width = ($QR_width - $logo_qr_width) / 2;
		$from_height = ($QR_height - $logo_qr_height) / 2;
		//重新组合图片并调整大小 
		imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 
	}

	//输出图片 
	Header("Content-type: image/png");
	ImagePng($QR);
	imagedestroy($QR);
//}

// <img src=/img.php?text=http://m.qr.com?lmreg=qq&logo=http://www.qr.com/Images/logo.png>
?>