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

//---Step 1---
switch ($_GET['step']) {
case 1:
$q2 = "SELECT * FROM va_users";
$result = mysql_query($q2);
$num = mysql_numrows($result);
$istaken = 0;
for ($i = 0; $i < $num; $i++) {
if (strtolower(mysql_result($result,$i,"user")) == strtolower($_POST['user'])) {
$istaken++;
}
}

$q3 = "SELECT * FROM va_mailbox";
$result1 = mysql_query($q3);
$num1 = mysql_numrows($result1);

for ($i = 15; $i < 9999999; $i++) {
		for ($i2 = 0; $i2 < $num1; $i2++) {
				if (mysql_result($result1,$i2,"id") == $i) {
				$istakenMB++;
				}
		}
		if ($istakenMB < 1) {
	 		 $pmbox = $i;
			 $query = "INSERT INTO va_mailbox VALUES ('" . $pmbox . "','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')";
			 mysql_query($query) or die("Unable to create Mailbox.");
			 break;
		}
		else
		{
		 	 $istakenMB = 0;
		}
}

if ($istaken < 1) {

$query = "INSERT INTO va_users VALUES ('','" . $_POST['user'] . "','" . md5($_POST['pass']) . "','" . $_POST['email'] . "','" . date("n.j.y") . "','1','1','1','1','0','100','1000','1000','1001','1001','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','" . $pmbox . "','10','10','10','10','200','5','3','3','0','0','0','0','0','0','0','0','0','0','0','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','0','','')";
mysql_query($query) or die("Unable to register account");
setcookie("va_reg",$_POST['user'],time()+86400);
header("Location: index.php?page=rstep1");
}
else
{
header("Location: index.php?page=regtaken");
}
break;
case 2:
//---Step 2---
if (!isset($_GET['gal']) or $_GET['gal'] < 1 or $_GET['gal'] > 12) {
echo "Invalid Galaxy";
} else {
if (isset($_COOKIE['va_reg'])) {
$query = "UPDATE va_users SET galaxy='" . $_GET['gal'] . "' WHERE user='" . $_COOKIE['va_reg'] . "'";
mysql_query($query) or die("Unable to switch galaxies.");
$query = "UPDATE va_users SET Cgalaxy='" . $_GET['gal'] . "' WHERE user='" . $_COOKIE['va_reg'] . "'";
mysql_query($query) or die("Unable to switch galaxies.");
header("Location: index.php?page=rstep2");
}
}

//I could add more security later...
//I've ended the Step If - Note to self.
break;
case 3:
//---Step 3---
if (!isset($_GET['cla']) or $_GET['cla'] < 1 or $_GET['cla'] > 2) {
echo "Invalid Class";
} else {
if (isset($_COOKIE['va_reg'])) {
$query = "UPDATE va_users SET class='" . $_GET['cla'] . "' WHERE user='" . $_COOKIE['va_reg'] . "'";
mysql_query($query) or die("Unable to switch classes.");

//MOVE THIS LINE TO THE END OF THE REGISTRATION SEQUENCE!
setcookie("va_reg",FALSE);
header("Location: index.php?page=regsuccess");
//MOVE THIS LINE TO THE END OF THE REGISTRATION SEQUENCE!
}
}
break;
}
}
else
{
echo "Database Could not Connect...";
}

?>
