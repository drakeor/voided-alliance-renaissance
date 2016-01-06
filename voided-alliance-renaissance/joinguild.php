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

//$que = "SELECT * FROM va_users WHERE user='" . $_POST['user'] . "' AND pass='" . md5($_POST['pass']) . "'";
$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_guilds";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"name")==$_POST['name'] and mysql_result($result2,$i,"password")==$_POST['pass']) {
$login = 1;
}
}

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$id = mysql_result($result,$i,"id");
}
}

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"name") == $_POST['name']) {
$gID = mysql_result($result2,$i,"id");
}
}

if ($login != 1) { 
header("Location: index.php?page=game&gamepage=joinF");
}
else
{
//Log In!
$query = "UPDATE va_users SET guild='" . $gID . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to edit Guild!");

header("Location: index.php?page=game&gamepage=joinS");
}

?>
