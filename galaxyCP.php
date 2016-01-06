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
$uID = mysql_result($result,$i,"id");
}
}

$query = "SELECT * FROM va_galaxy";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"ownerid") == $uID) {
$gName = mysql_result($result,$i,"name");
$gOwns = true;
$gID = mysql_result($result,$i,"id");
$tog = mysql_result($result,$i,"gov");
}
}

if ($gOwns == true) {
switch ($_POST['todo']) {
case 1:
$query = "UPDATE va_galaxy SET tollEnter='" . $_POST['AP'] . "' WHERE id='" . $gID . "'";
mysql_query($query) or die("Unable to edit.");
break;
case 2:
$query = "UPDATE va_galaxy SET tollExit='" . $_POST['AP'] . "' WHERE id='" . $gID . "'";
mysql_query($query) or die("Unable to edit.");
break;
}
header("Location: index.php?page=game&gamepage=gCP");
} else {
header("Location: index.php?page=game&gamepage=gCP");
}

} else {
header("Location: index.php?page=game&gamepage=gCP");
}