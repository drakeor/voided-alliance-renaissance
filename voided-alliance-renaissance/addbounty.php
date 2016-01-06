<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

include('includes/functions.php');

connectDB();

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

//$que = "SELECT * FROM va_users WHERE user='" . $usr_N_R . "' AND pass='" . md5($_POST['pass']) . "'";
$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_ticket";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

$name = $usr_N_R;
$amount = $_POST['bounty'];

for ($i = 0; $i < $num1; $i++) {
if (strtolower(mysql_result($result,$i,"user")) == strtolower($_POST['recieve'])) {
$userID = mysql_result($result,$i,"id");
$found = true;
}
if (strtolower(mysql_result($result,$i,"user")) == strtolower($name)) {
$user2ID = mysql_result($result,$i,"id");
$credits = mysql_result($result,$i,"currency");
}
}

if ($credits >= $amount and $amount > 0 and $found == true) {
$NC = $credits - $amount;
$query = "UPDATE va_users SET currency='" . $NC . "' WHERE id='" . $user2ID . "'";
mysql_query($query) or die("Unable to edit currency.");

$query = "INSERT INTO va_bounty VALUES ('','" . $user2ID . "','" . $userID . "','" . $amount . "')";
mysql_query($query) or die("Unable to create bounty!");

header("Location: index.php?page=game&gamepage=bca");
die();
} else {
header("Location: index.php?page=game&gamepage=bca2");
die();
}
?>
