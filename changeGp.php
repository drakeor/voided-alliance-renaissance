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


$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$gID = mysql_result($result,$i,"guild");
$uID = mysql_result($result,$i,"id");
}
}

$query = "SELECT * FROM va_guilds";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"id") == $gID) {
$gName = mysql_result($result,$i,"name");
$ownerID = mysql_result($result,$i,"ownerid");
}
}

$pageT = addslashes($_POST['pageT']);

if ($uID == $ownerID) {
$query = "UPDATE va_guilds SET page='" . $_POST['pageT'] . "' WHERE id='" . $gID . "'";
mysql_query($query) or die("Unable to update XP!");
}
header("Location: index.php?page=game&gamepage=guild");
} else {
header("Location: index.php?page=game&gamepage=guild");
}

?>
