<?php
	session_start();

	$width = 170;
	$height = 40;
	$font_size = 20;
	$font = "./verdana.ttf";
	$font = realpath($font);

	$captcha_characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$image = imagecreatetruecolor($width, $height);
	$bg_color = imagecolorallocate($image, rand(0,255), rand(0,255), rand(0,255));
	$font_color = imagecolorallocate($image, 255, 255, 255);
	imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

	//background with random string and pos
	$hori_line = round($height/8);
	$vert_line = round($width/8);
	$random_string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$color = imagecolorallocate($image, 255, 255, 255);
	for($i=0; $i < $vert_line; $i++) {
		for ($j=0; $j < $hori_line; $j++) {
			$random_letter = $random_string[rand(0, strlen($random_string)-1)];
			imagestring($image, rand(1,2), rand(0,$width), rand(0,$height), $random_letter, $color);
		}
	}

	$digit = '';
	for($i = 1; $i < 6; $i++) {
		$letter = $captcha_characters[rand(0, strlen($captcha_characters)-1)];
		$digit .= $letter;
		imagettftext($image, $font_size, rand(-10,20), $i*25, rand(22, $height-5), $font_color, $font, $letter);
	}

	// record token in session variable
	$_SESSION['captcha_token'] = strtolower($digit);

	// display image
	header('Content-Type: image/png');
	imagepng($image);
	imagedestroy($image);
?>
