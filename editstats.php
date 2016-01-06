<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


include('includes/functions.php');

$connected = connectDB();
if ($connected == true) {

if (!empty($_COOKIE['va_users'])) {
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

$stat = $_POST['stat'];

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$access = mysql_result($result,$i,"access");
}
}


if ($access > 2) {
$query2 = "SELECT * FROM va_users";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

for ($i = 0; $i < $num2; $i++) {
if (strtolower(mysql_result($result2,$i,"user")) == strtolower($_POST['user'])) {
$uID = mysql_result($result2,$i,"id");
$Alvl = mysql_result($result2,$i,"level");
$found = true;
}
}

if ($found == true) {
switch ($stat) {
case 1:
$query = "UPDATE va_users SET str='" . $_POST['AP'] . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to send -- Message.");
break;
case 2:
$query = "UPDATE va_users SET def='" . $_POST['AP'] . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to send -- Message.");
break;
case 3:
$query = "UPDATE va_users SET maxHP='" . $_POST['AP'] . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to send -- Message.");
break;
case 4:
$query = "UPDATE va_users SET maxMP='" . $_POST['AP'] . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to send -- Message.");
break;
case 5:
$lev = 1;

$need = ((pow($lev,2))*200); 

$tnl = $need - $_POST['AP'];

while ($tnl < 1) {
$lev++;

$need = ((pow($lev,2))*200); 

$tnl = $need - $_POST['AP'];
}
$query = "UPDATE va_users SET level='" . $lev . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to update level!");

$query = "UPDATE va_users SET points='" . (($Apoints + 3)*$lev) . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to update points!");
$query = "UPDATE va_users SET currentXP='" . $_POST['AP'] . "' WHERE id='" . $uID . "'";
mysql_query($query) or die("Unable to update XP!");
break;
}
}
header("Location: index.php?page=cp");
}
else
{
header("Location: index.php?page=cp");
}
} else {
header("Location: index.php?page=cp");
}
?>