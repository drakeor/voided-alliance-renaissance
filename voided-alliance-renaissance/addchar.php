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

$stat = $_POST['stat'];

$query = "SELECT * FROM va_classes";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++){
$query = "SELECT * FROM va_classes";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 15; $i < 9999999; $i++) {
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $i) {
$istakenMB++;
}
}
if ($istakenMB < 1) {
$ClassesID = $i;
break;
}
else
{
$istakenMB = 0;
}
}

$query = "INSERT INTO va_classes VALUES ('" . $ClassesID . "','" . $_POST['name'] . "','" . $_POST['description'] . "','" . $_POST['image'] . "')";
mysql_query($query) or die("Unable to create item!");

}
header("Location: index.php?page=cp");
}

else {
header("Location: index.php?page=cp");
}
?>