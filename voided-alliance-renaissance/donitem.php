<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


include('includes/functions.php');

$connected = connectDB();

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

$invID = $_POST['id'];
$userNAM = $_POST['user'];
$donMessage = $_POST['message'];

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (strtolower(mysql_result($result,$i,"user")) == strtolower($userNAM)) {
$MBid = mysql_result($result,$i,"mailbox");
$TUid = mysql_result($result,$i,"id");
for ($i2 = 0; $i2 < 30; $i2++) {
if (mysql_result($result,$i,"inv" . $i2) != 0) {
$toinvID = $i2;
}
}

}
if (mysql_result($result,$i,"user") == $usr_N_R) {
$from = $usr_N_R;
$id = mysql_result($result,$i,"id");
$itemID = mysql_result($result,$i,"inv" . $invID);
}
}

if ($itemID == 0) {
die("<hr>Unable to find item...");
}

$query = "SELECT * FROM va_items";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"id") == $itemID) {
$itemname = mysql_result($result,$i,"name");
$type = mysql_result($result,$i,"type");
$AP = mysql_result($result,$i,"AP");
$isbuffed = mysql_result($result,$i,"isbuffed");
$bImage = mysql_result($result,$i,"buffimage");
$bName = mysql_result($result,$i,"buffname");
$battlesL = mysql_result($result,$i,"battlesleft");
$strPlus = mysql_result($result,$i,"str");
$defPlus = mysql_result($result,$i,"def");
$hpPlus = mysql_result($result,$i,"maxHP");
$mpPlus = mysql_result($result,$i,"maxMP");
}
}

if (isset($toinvID)) {
//Donation can be sent.
$query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to set item.");

$query = "UPDATE va_users SET inv" . $toinvID . "='" . $itemID . "' WHERE id='" . $TUid . "'";
mysql_query($query) or die("Unable to set item.");

$query = "SELECT * FROM va_mailbox";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

//START OF MESSAGE CREATION
$AutoMessage = "Hey you're in luck!<br><br>" . $from . " sent " . $NumCredits . " Credits to you!";


$subject = "Donation of an Item";
$message = "Donation: " . $itemname . "<br>Message:<br>" . $donMessage;

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

?>

