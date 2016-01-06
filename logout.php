<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

include('includes/functions.php');

connectDB();

$query = "SELECT * FROM va_session";
$result = mysql_query($query);
$num = mysql_numrows($result);

for ($i = 0; $i < $num; $i++) {
if (mysql_result($result,$i,"session") == $_COOKIE['va_users']) {
$sID = mysql_result($result,$i,"id");
$userSNf = mysql_result($result,$i,"user");
$logged_in = true;
}
}

$query = "DELETE FROM va_session WHERE id = '" . $sID . "'";
mysql_query($query) or die("Unable to delete session!");
$sql="UPDATE va_users SET lastref = '0' WHERE user = '" . $userSNf . "'";
mysql_query($sql);
mysql_close();
setcookie("va_users",FALSE);
header("Location: index.php?page=logoutsuccess");
?>
