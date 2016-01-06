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
	
$toID = $_POST['id'];
$toNA = "";

$MaxCredits = 0;
$NumCredits = $_POST['amount'];

if ($NumCredits <= 0) {
header("Location: index.php?page=game&gamepage=donF");
die();
}

$AutoMessage = "";
$UserMessage = $_POST['message'];

$query = "SELECT * FROM va_mailbox";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_users";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"id") == $toID) {
$toNA = mysql_result($result2,$i,"user");
$beforeC = mysql_result($result2,$i,"currency");
$MBid = mysql_result($result2,$i,"mailbox");
$userfound=true;
}
}

if ($userfound == true) {

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"user") == $usr_N_R) {
$fromID = mysql_result($result2,$i,"id");
$from = mysql_result($result2,$i,"user");
$MaxCredits = mysql_result($result2,$i,"currency");
}
}

if ($MaxCredits >= $NumCredits and $fromID != $toID) {
$Donated = $beforeC + $NumCredits;

$query = "UPDATE va_users SET currency='" . $Donated . "' WHERE id='" . $toID . "'";
mysql_query($query) or die("Unable to send Credits!");

$DonatedF2 = $MaxCredits - $NumCredits;

$query = "UPDATE va_users SET currency='" . $DonatedF2 . "' WHERE id='" . $fromID . "'";
mysql_query($query) or die("Unable to send Credits!");

//START OF MESSAGE CREATION
$AutoMessage = "Hey you're in luck!<br><br>" . $from . " sent " . $NumCredits . " Credits to you!";


$subject = "Donation of Credits";
$message = "Donation: " . $NumCredits . "<br>Message:<br>" . $UserMessage;

//END OF MESSAGE CREATION


//SEND MESSAGE
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $MBid) {

for ($i = 0; $i < 30; $i++) {
if (mysql_result($result,$i2,"mbS" . $i) == "") {
$notfull = true;
$query = "UPDATE va_mailbox SET mbS" . $i . "='" . $subject . "' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send -- Subject.");

$query = "UPDATE va_mailbox SET mbM" . $i . "='" . $message . "' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send -- Message.");

$query = "UPDATE va_mailbox SET mbF" . $i . "='" . $from . "' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send -- From.");

$query = "UPDATE va_mailbox SET mbT" . $i . "='Unread' WHERE id='" . $MBid . "'";
mysql_query($query) or die("Unable to send -- Status");
break;
}
}

}
}

header("Location: index.php?page=game&gamepage=donS");
die();
} else {

header("Location: index.php?page=game&gamepage=donF");
die();
}

} else {
header("Location: index.php?page=game&gamepage=donF");
die();
}

} else {

echo "Could not connect...";

}
?>
