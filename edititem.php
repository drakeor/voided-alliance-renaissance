<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


include('includes/functions.php');
$connected = connectDB();

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

$id = $_POST['id'];
$rareRoll = $_POST['rareroll'];
$name = $_POST['itemName'];
$description = $_POST['itemDescribe'];
$image = $_POST['itemimage'];
$type = $_POST['itemtype'];
$AP = $_POST['itemAP'];
$isbuffed = $_POST['isbuffed'];
$buffimage = $_POST['buffName'];
$buffname = $_POST['buffImage'];
$battlesleft = $_POST['buffBL'];
$str = $_POST['buffSTR'];
$def = $_POST['buffDEF'];
$maxHP = $_POST['buffHP'];
$maxMP = $_POST['buffMP'];

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

$query = "UPDATE va_items SET rareRoll='" . $rareRoll . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET name='" . $name . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET description='" . $description . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET image='" . $image . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET type='" . $type . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET AP='" . $AP . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET isbuffed='" . $isbuffed . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET buffimage='" . $buffimage . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET buffname='" . $buffname . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET battlesleft='" . $battlesleft . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET str='" . $str . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET def='" . $def . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET maxHP='" . $maxHP . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");
$query = "UPDATE va_items SET maxMP='" . $maxMP . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to add name!");

//End...
header("Location: index.php?page=cp");
} else {
header("Location: index.php?page=cp");
}

?>
