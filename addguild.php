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
	
$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_guilds";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

$guildN = $_POST['name'];
$guildP = $_POST['pass'];
$guildD = $_POST['describe'];
$cost = 1000;

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$credits = mysql_result($result,$i,"currency");
$id = mysql_result($result,$i,"id");
$cGid = mysql_result($result,$i,"guild");
}
}

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"name") == $guildN) {
$aEx = true;
}
}


if ($cGid == 0 or $cGid == "") {
if ($credits >= $cost) {
if ($aEx != true) {
$query = "INSERT INTO va_guilds VALUES ('','" . $guildN . "','" . $id . "','" . $guildD . "','','" . $guildP . "','')";
mysql_query($query) or die("Unable to create guild!");

$query3 = "SELECT * FROM va_guilds";
$result3 = mysql_query($query3);
$num3 = mysql_numrows($result3);

for ($i = 0; $i < $num3; $i++) {
if (mysql_result($result3,$i,"name") == $guildN) {
$guildID = mysql_result($result3,$i,"id");
}
}
	
	
$UC = $credits - $cost;
$query = "UPDATE va_users SET currency='" . $UC . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to edit Credits!");

$query = "UPDATE va_users SET guild='" . $guildID . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to edit Guild!");



header("Location: index.php?page=game&gamepage=guS");
} else {
//Guild Already Exists
header("Location: index.php?page=game&gamepage=guF");
}
} else {
//Not Enough Credits
header("Location: index.php?page=game&gamepage=guF");
}
} else {
//Already in a guild!
header("Location: index.php?page=game&gamepage=guF");
}

//Ending! DO NOT ADD CODE AFTER THIS LINE!
} else {
header("Location: index.php?page=game&gamepage=guF");
}
?>
