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

$cPassF = md5($_POST['cPass']);
$cPassD = "";

$nPassF = md5($_POST['nPass']);

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$cPassD = mysql_result($result,$i,"password");
$id = mysql_result($result,$i,"id");
}
}

if ($cPassF == $cPassD) {

$query = "UPDATE va_users SET password='" . $nPassF . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to edit password!");

header("Location: index.php?page=game&gamepage=cpS");
die();

} else {
header("Location: index.php?page=game&gamepage=cpF");
die();
}

} else {
echo "Could not connet to Database!";
}

?>
