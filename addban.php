<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

include('includes/functions.php');

connectDB();

if (!empty($_COOKIE['va_users']))  {
$userSession = $_COOKIE['va_users'];

$query2 = "SELECT * FROM va_session";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"session") == $_COOKIE['va_users']) {
$timeLeft = mysql_result($result2,$i,"time");
$usr_N_R = mysql_result($result2,$i,"user");
$foundSes = true;
}
}

if ($foundSes != true) {
	setcookie("va_users",FALSE);
	header("Location: index.php?page=index");
	die();
}
}

$banLength = date('ymdH');
$toG = date('i') + $_POST['length'];
if ($toG < 10) {
$banLength .= "0" . (date('i') + $_POST['length']);
} else {
$banLength .= (date('i') + $_POST['length']);
}

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$access = mysql_result($result,$i,"access");
}
}


if ($access > 1) {
$query2 = "SELECT * FROM va_users";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

for ($i = 0; $i < $num2; $i++) {
if (strtolower(mysql_result($result2,$i,"user")) == strtolower($_POST['user'])) {
$uID = mysql_result($result2,$i,"id");
$found = true;
}
}

if ($access == 2 and $_POST['length'] <= 10) {
$good = true;
}
if ($access == 3 and $_POST['length'] <= 60) {
$good = true;
}
if ($access > 3) {
$good = true;
}

if ($found == true and $good == true) {
$query = "INSERT INTO va_bans VALUES ('','" . $uID . "','" . $banLength . "')";
mysql_query($query) or die("Unable to create ticket!");
}
header("Location: index.php?page=cp");
}
else
{
header("Location: index.php?page=cp");
}
?>

?>

