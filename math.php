<?php
//Start the session so we can store what the code actually is.
session_start();

//Generation
$num1 = rand(1, 9);
$num2 = rand(1, 9);

/*
We dont need a 32 character long string so we trim it down to 5
*/

$string=$num1 . " + " . $num2 . " = ";

/*
Now for the GD stuff, for ease of use lets create
 the image from a background image.
*/

switch ($i = rand(0, 3)) {
case 0:
$captcha = imagecreatefrompng("./captcha.png");
break;
case 1:
$captcha = imagecreatefrompng("./captcha2.png");
break;
case 2:
$captcha = imagecreatefrompng("./captcha3.png");
break;
case 3:
$captcha = imagecreatefrompng("./captcha4.png");
break;
}

/*
Lets set the colours, the colour $line is used to generate lines.
 Using a blue misty colours. The colour codes are in RGB
*/

$black = imagecolorallocate($captcha, 255, 255, 255);
$line = imagecolorallocate($captcha,233,239,239);

/*
Now to make it a little bit harder for any bots to break, 
assuming they can break it so far. Lets add some lines
in (static lines) to attempt to make the bots life a little harder
*/
imageline($captcha,0,0,39,29,$line);
imageline($captcha,40,0,64,29,$line);

/*
Now for the all important writing of the randomly generated string to the image.
*/
imagestring($captcha, 5, 20, 10, $string, $black);

$_SESSION['math']=$num1+$num2;

/*
Output the image
*/
header("Content-type: image/png");
imagepng($captcha);
?> 
