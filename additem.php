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

$stat = $_POST['stat'];

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$access = mysql_result($result,$i,"access");
}
}


if ($access > 3) {
$query = "SELECT * FROM va_items";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 15; $i < 9999999; $i++) {
		for ($i2 = 0; $i2 < $num1; $i2++) {
				if (mysql_result($result,$i2,"id") == $i) {
				$istakenMB++;
				}
		}
		if ($istakenMB < 1) {
	 		 $ItemID = $i;
			 break;
		}
		else
		{
		 	 $istakenMB = 0;
		}
}

if ($_POST['isbuffed'] == true) {
$query = "INSERT INTO va_items VALUES ('" . $ItemID . "','" . $_POST['rareroll'] . "','" . $_POST['itemName'] . "','" . $_POST['itemDescribe'] . "','" . $_POST['itemimage'] . "','" . $_POST['itemtype'] . "','" . $_POST['itemAP'] . "','1','" . $_POST['buffImage'] . "','" . $_POST['buffName'] . "','" . $_POST['buffBL'] . "','" . $_POST['buffSTR'] . "','" . $_POST['buffDEF'] . "','" . $_POST['buffHP'] . "','" . $_POST['buffMP'] . "')";
mysql_query($query) or die("Unable to create item!");
} else {
$query = "INSERT INTO va_items VALUES ('" . $ItemID . "','" . $_POST['rareroll'] . "','" . $_POST['itemName'] . "','" . $_POST['itemDescribe'] . "','" . $_POST['itemimage'] . "','" . $_POST['itemtype'] . "','" . $_POST['itemAP'] . "','0','" . $_POST['buffImage'] . "','" . $_POST['buffName'] . "','" . $_POST['buffBL'] . "','" . $_POST['buffSTR'] . "','" . $_POST['buffDEF'] . "','" . $_POST['buffHP'] . "','" . $_POST['buffMP'] . "')";
mysql_query($query) or die("Unable to create item!");
}
header("Location: index.php?page=cp");
}

} else {
header("Location: index.php?page=cp");
}

?>
