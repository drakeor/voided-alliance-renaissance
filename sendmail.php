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
$to = $_POST['to'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$LCmessage = strtolower($message);

if (preg_match("/([\<])([^\>]{1,})*([\>])/i", $message)) {
  //Contains HTML!
	header("Location: index.php?page=game&gamepage=mailHTML");
	die();
} 

$query = "SELECT * FROM va_mailbox";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_users";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

for ($i = 0; $i < $num2; $i++) {
if (strtolower(mysql_result($result2,$i,"user")) == strtolower($to)) {
$MBid = mysql_result($result2,$i,"mailbox");
$userfound=true;
}
}

if ($userfound == true) {

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"user") == $usr_N_R) {
$from = mysql_result($result2,$i,"user");
}
}

for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $MBid) {

for ($i = 0; $i < 30; $i++) {
if (mysql_result($result,$i2,"mbF" . $i) == "") {

$notfull = true;
$query = "UPDATE va_mailbox SET mbS" . $i . "='" . $subject . "' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send!");

$query = "UPDATE va_mailbox SET mbM" . $i . "='" . $message . "' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send!");

$query = "UPDATE va_mailbox SET mbF" . $i . "='" . $from . "' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send!");

$query = "UPDATE va_mailbox SET mbT" . $i . "='Unread' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send!");
break;
}
}

}
}
if ($notfull == true) {
header("Location: index.php?page=game&gamepage=inbox");
}
else
{
header("Location: index.php?page=game&gamepage=mbFull");
}
}
else
{
header("Location: index.php?page=game&gamepage=mailUNF");
}
}
else
{
echo "Could not connect";
}

?>
