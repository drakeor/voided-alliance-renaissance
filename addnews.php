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

$subject = $_POST['subject'];
$text = $_POST['text'];
$user = $_COOKIE['va_users'];
$date = date("n.j.y");

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$access = mysql_result($result,$i,"access");
}
}

if ($access > 3) {
$good = true;
}

if ($good == true) {
$query = "INSERT INTO va_news VALUES ('','" . $subject . "','" . $text . "','" . $user . "','" . $date . "')";
mysql_query($query) or die("Unable to create news!");
header("Location: index.php?page=cp");
die();
}
else
{
header("Location: index.php?page=cp");
die();
}

?>

