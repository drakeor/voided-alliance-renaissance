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

$invID = $_POST['invID'];
$price = $_POST['price'];

$query = "SELECT * FROM va_shops";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_users";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

$query3 = "SELECT * FROM va_items";
$result3 = mysql_query($query3);
$num3 = mysql_numrows($result3);

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"user") == $usr_N_R) {
$itemID = mysql_result($result2,$i,"inv" . $invID);
$shopID = mysql_result($result2,$i,"shopid");
$id = mysql_result($result2,$i,"id");
}
}

for ($i = 0; $i < $num3; $i++) {
if (mysql_result($result3,$i,"id") == $itemID) {
$itemI = mysql_result($result3,$i,"image");
$itemN = mysql_result($result3,$i,"name");
$itemD = mysql_result($result3,$i,"description");
$itemT = mysql_result($result3,$i,"type");
$itemA = mysql_result($result3,$i,"AP");
}
}

if ($price > 0) {

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"id") == $shopID) {
for ($i2 = 0; $i2 < 30; $i2++) {
$SinvID[$i2] = mysql_result($result,$i,"inv" . $i2);
}
}
}

for ($i = 0; $i < 30; $i++) {
if ($SinvID[$i] == 0) {

$query = "UPDATE va_shops SET inv" . $i . "='" . $itemID . "' WHERE id='" . $shopID . "'";
mysql_query($query) or die("Unable to add item!");
$query = "UPDATE va_shops SET price" . $i . "='" . $price . "' WHERE id='" . $shopID . "'";
mysql_query($query) or die("Unable to add price!");
$query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to delete item!");
$areafound = true;
break;
}
}

if ($areafound == true) {
header("Location: index.php?page=game&gamepage=sellS");
die();
} else {
header("Location: index.php?page=game&gamepage=sellF");
die();
}

}
else
{
header("Location: index.php?page=game&gamepage=sellF");
die();
}

} else {
header("Location: index.php?page=game&gamepage=sellF");
die();
}
?>
