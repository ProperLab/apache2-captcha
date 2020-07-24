<?php

/**
 * apache2-captcha
 * © 2020 ProperLab (MakerLab & ProperCloud)
 * Licensed under MIT (https://github.com/ProperLab/apache2-captcha/blob/master/LICENSE)
 */

// Config:
$baseUrl = 'https://example.com';
$cookieName = 'noauth'; // Change this to whatever you want
$cookieContent = 'IF-ONLY-MACHINES-KNEW-THIS'; // Optional
$cookieExpire = 31; // Days
$isValidAuth = false;

session_start();

// If the captcha was sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

	$token = strtolower($_POST['token']);

	// Validation
	if (isset($_SESSION['captcha_token']) && $_SESSION['captcha_token'] == $token) {
		// Correct captcha
		$isValidAuth = true;
	} else {
		$isValidAuth = false;
		echo "Error: Invalid CAPTCHA code";
		die;
	}
}
// If the cookie was already set
else if (isset($_COOKIE[$cookieName])) {
	$isValidAuth = true;
}

$target = "/";
if (isset($_GET["target"])) {
	$target = $_GET["target"];
}
if ($isValidAuth) {
	setAuth($cookieName, $cookieContent, $cookieExpire);
	session_destroy();
	unset($_COOKIE['PHPSESSID']);
	setcookie('PHPSESSID', '', time() - 3600, '/');
	echo "true";
	die;
}

// Creates the cookie
function setAuth($name, $content, $expire)
{
	setcookie($name, $content, [
		'expires' => time() + 3600 * 24 * $expire,
		'path' => '/',
		'secure' => true,
		'samesite' => 'Strict',
	]);
}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>apache2-captcha - Verification</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="robots" content="noindex,nofollow,noarchive">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>

	<div>
		<div class="modal-overlay" id="modal-overlay"></div>

		<div class="modal" id="modal">
			<div id="main" class="modal-guts">
				<div class="box-captcha">
					<div class="header">
						<h1>Captcha verification required</h1>
						<div>
							<p>After submiting the captcha you will be redirected to: <input id="target" class="text-target" disabled value="<?php echo $baseUrl . $target; ?>"></p>
						</div>
					</div>
					<div class="captcha">
						<img src="captcha/image.php" alt="CAPTCHA" id="image-captcha">
					</div>
					<div class="box-generar">
						<button id="refresh-captcha" class="button" title="refresh">Refresh</button>
					</div>
					<div class="box-entrada">
						<input class="text-captcha" type="text" name="token" id="token" placeholder="Captcha">
					</div>
					<div id="message" style="padding: 3px;"></div>
					<div class="box-button">
						<button class="button" name="submit" onclick="verify()">Submit</button>
					</div>
				</div>

			</div>
		</div>

	</div>

	<script src="js/captcha.js"></script>

</body>

</html>