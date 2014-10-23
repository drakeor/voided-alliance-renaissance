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

$query2 = "SELECT * FROM va_users";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);
for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"user") == $usr_N_R) {
$id = mysql_result($result2,$i,"id");
$currentHP = mysql_result($result2,$i,"currentHP");
$currentMP = mysql_result($result2,$i,"currentMP");
$HP = $currentHP;
$MP = $currentMP;
}
}

$query = "SELECT * FROM va_buffs";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$NB = 0;
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"playerid") == $id) {
//Buff Found!
$buffD[$NB] = mysql_result($result,$i2,"id");
$buffN[$NB] = mysql_result($result,$i2,"name");

$NB++;
$NoB++;
}
}


for ($i = 0; $i < 30; $i++) {
if (isset($_POST["wtd" . $i])) {
switch ($_POST["wtd" . $i]) {
case 'toss':
$query = "UPDATE va_users SET inv" . $_POST["invID" . $i] . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to delete item!");
break;
case 'factory':
$invID = $_POST["invID" . $i];
$itemID = $_POST["itemID" . $i];

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"user") == $usr_N_R) {
$Uid = mysql_result($result,$i2,"id");
$invIDz = mysql_result($result,$i2,"inv" . $invID);
}
}

$query = "SELECT * FROM va_items";
$result = mysql_query($query);
$num1 = mysql_numrows($result);
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $invIDz) {
$itemID = mysql_result($result,$i2,"id");
}
}

$query = "INSERT INTO va_worktable VALUES ('','" . $Uid . "','" . $itemID . "')";
mysql_query($query) or die("Unable to create ticket!");

$query = "UPDATE va_users SET inv" . $invID . " = '0' WHERE id='" . $Uid . "'";
mysql_query($query) or die($query);
break;
case 'bank':
$invID = $_POST["invID" . $i];
$itemID = $_POST["itemID" . $i];

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"user") == $usr_N_R) {
$Uid = mysql_result($result,$i2,"id");
$invIDz = mysql_result($result,$i2,"inv" . $invID);
}
}

$query = "SELECT * FROM va_items";
$result = mysql_query($query);
$num1 = mysql_numrows($result);
for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $invIDz) {
$itemID = mysql_result($result,$i2,"id");
}
}

$query = "INSERT INTO va_bank VALUES ('','" . $Uid . "','" . $itemID . "')";
mysql_query($query) or die("Unable to create ticket!");

$query = "UPDATE va_users SET inv" . $invID . " = '0' WHERE id='" . $Uid . "'";
mysql_query($query) or die($query);
break;
case 'use':
$invID = $_POST["invID" . $i];
$itemID = $_POST["itemID" . $i];



$query = "SELECT * FROM va_items";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $itemID) {
$itemname = mysql_result($result,$i2,"name");
$type = mysql_result($result,$i2,"type");
$AP = mysql_result($result,$i2,"AP");
$isbuffed = mysql_result($result,$i2,"isbuffed");
$bImage = mysql_result($result,$i2,"buffimage");
$bName = mysql_result($result,$i2,"buffname");
$battlesL = mysql_result($result,$i2,"battlesleft");
$strPlus = mysql_result($result,$i2,"str");
$defPlus = mysql_result($result,$i2,"def");
$hpPlus = mysql_result($result,$i2,"maxHP");
$mpPlus = mysql_result($result,$i2,"maxMP");
}
}

for ($i2 = 0; $i2 < $num1; $i2++) {
if (mysql_result($result,$i2,"id") == $weaponID) {
$wName = mysql_result($result,$i2,"name");
}
}

switch ($type) {
case 0:
$HP += $AP;
//HP Potion
$query = "UPDATE va_users SET currentHP='" . $HP . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to use item.");

$query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to delete item.");
break;
case 1:
//Weapon
if ($weaponID == 0) {
$query = "UPDATE va_users SET eqWeapon='" . $itemID . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to use item.");

$query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to delete item.");

} else {

}
break;
case 6:
//Book
			switch ($AP) {
			case 1:
			echo "<hr>You try very hard but you cannot read the Flaxen Symbols.<hr>";
			break;
			}
break;
case 7:
//Mana Potion
$MP += $AP;
$query = "UPDATE va_users SET currentMP='" . $MP . "' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to use item.");

$query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to delete item.");
break;
case 8:
//Buff Item
$query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
mysql_query($query) or die("Unable to delete item.");
break;
default:
header("Location: index.php?page=game&gamepage=stats");
break;
}

for ($i2 = 0; $i2 < $NoB; $i2++) {
if ($buffN[$i2] == $bName) {
$cantBuff = true;
}
}

$NoIDs = 0;
$IDno = 0;
$stID = ($i - 1);

for ($i2 = $stID; $i2 >= 0; $i2--) {
if ($itemID == $_POST["itemID" . $i2]) {
$cannotBuff =  true;
}
}

if ($isbuffed == 1 and $cantBuff != true and $cannotBuff != true) {
//Add Buff
$query = "INSERT INTO va_buffs VALUES ('','" . $bName . "','" . $bImage . "','" . $id . "','" . $battlesL . "','" . $strPlus . "','" . $defPlus . "','" . $hpPlus . "','" . $mpPlus . "')";
mysql_query($query) or die("Unable to create buff!");
}

break;
}
}
}

header("Location: index.php?page=game&gamepage=stats");
} else {
header("Location: index.php?page=game&gamepage=stats");
}

?>
