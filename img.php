<?php
include 'phpqrcode.php'; 

$value = $_GET['text'];//��ά������ 

$logo = $_GET['logo'];
$errorCorrectionLevel = 'H';//�ݴ���

if(isset($_GET['size'])){
	$matrixPointSize = intval($_GET['size']);
}else{
	$matrixPointSize = 8;//����ͼƬ��С 
}

$urlarr = parse_url($value);
$host = $urlarr['host'];

//��һ��
//$host = substr($host,-8);

//�ڶ���
preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
$host = $matches[0];

//if($host =='qr.com')
//{
	//���ɶ�ά��ͼƬ 
	//$m = QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
	//echo $m;
	//$logo = 'logo.png';//׼���õ�logoͼƬ 
	//$QR = 'qrcode.png';//�Ѿ����ɵ�ԭʼ��ά��ͼ

    $QR = QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
    
	if (isset($logo)) {
		//$QR = imagecreatefromstring(file_get_contents($QR)); 
		$QR = QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
		$logo = imagecreatefromstring(file_get_contents($logo)); 
		$QR_width = imagesx($QR);//��ά��ͼƬ��� 
		$QR_height = imagesy($QR);//��ά��ͼƬ�߶� 
		$logo_width = imagesx($logo);//logoͼƬ��� 
		$logo_height = imagesy($logo);//logoͼƬ�߶� 
		$logo_qr_width = $QR_width / 3; 
		$logo_qr_height = $QR_height / 3;
		$scale = $logo_width/$logo_qr_width; 
		$logo_qr_height = $logo_height/$scale;
		$from_width = ($QR_width - $logo_qr_width) / 2;
		$from_height = ($QR_height - $logo_qr_height) / 2;
		//�������ͼƬ��������С 
		imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 
	}

	//���ͼƬ 
	Header("Content-type: image/png");
	ImagePng($QR);
	imagedestroy($QR);
//}

// <img src=/img.php?text=http://m.qr.com?lmreg=qq&logo=http://www.qr.com/Images/logo.png>
?>