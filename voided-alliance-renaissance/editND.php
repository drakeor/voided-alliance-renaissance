<?
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
	
$name = $_POST['name'];
$desc = $_POST['desc'];

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
$shopID = mysql_result($result2,$i,"shopid");
}
}

$query = "UPDATE va_shops SET name='" . $name . "' WHERE id='" . $shopID . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_shops SET description='" . $desc . "' WHERE id='" . $shopID . "'";
mysql_query($query) or die("Unable to add desc to " . $desc . "!");

header("Location: index.php?page=game&gamepage=store&wtd=S");
die();
} else {
header("Location: index.php?page=game&gamepage=store&wtd=F");
die();
}
?>
