<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


include('includes/functions.php');

connectDB();

//$que = "SELECT * FROM va_users WHERE user='" . $_POST['user'] . "' AND pass='" . md5($_POST['pass']) . "'";
$que = "SELECT * FROM va_users";
$ec = mysql_query($que);
$num = mysql_numrows($ec);

for ($i = 0; $i < $num; $i++) {
if (strtolower(mysql_result($ec,$i,"user"))==strtolower($_POST['user']) and mysql_result($ec,$i,"password")==md5($_POST['pass'])) {
$login = 1;
}
}


if ($login != 1) { 
header("Location: index.php?page=loginfailed");
}
else
{
$query = "SELECT * FROM va_session";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $_POST['user']) {
$tobeRev = mysql_result($result,$i,"time");
	if ($tobeRev <= time()) {
		$query = "DELETE FROM va_session WHERE id = '" . mysql_result($result,$i,"id") . "'";
		mysql_query($query) or die($query);
		$completed = false;
	} else {
		setcookie("va_users",mysql_result($result,$i,"session"),$tobeRev);
		$completed = true;
	}
}
}

if ($completed != true) {
//Log In!
$userSession = va_hash($_POST['user']);
$timeLeft = time()+86400;

$query = "INSERT INTO va_session VALUES ('','" . $userSession . "','" . $_POST['user'] . "','" . $timeLeft . "')";
mysql_query($query) or die("Unable to register session!");

setcookie("va_users",$userSession,$timeLeft);
}
header("Location: index.php?page=game");
echo "You are now logged in, " . $_POST['user'] . "!";
}


?>
