<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/
/////////////////////////////////////////////
//                Written by:              //
//            The Auburnflame, Co.         //
/////////////////////////////////////////////

function wti($pagenum) {
$gamename = "Voided Alliance"; //Whatever your heart desires raygoe... :D
		switch ($pagenum){
		Case 0:
		echo "$gamename - Index";
		break;
		Case 1:
		echo "$gamename - Game";
		break;
		Case 2:
		echo "$gamename - Register";
		break;
		Case 3:
		echo "$gamename - Login";
		break;
		Case 4:
		echo "$gamename - Player";
		break;
		Case 5:
		echo "$gamename - Not Released";
		break;
		}
}

function menuname($menunumber) {
		switch ($menunumber) {
		Case 0:
		echo "Left Menu";
		Case 1:
		echo "Main Screen";
		Case 2:
		echo "Right Menu";
		}
}

function va_hash($string) {
	
	srand ((double) microtime( )*1000000);
	$randomString = (time() + rand(0,100000));
	for ($i = 0; $i < (strlen($randomString)); $i++) {
		$string3 = (substr($randomString,$i,1));
		$string2 .= substr("abcdefghij",(int)$string3,1);
	}
	$string .= $string2;
	$encrypted = md5($string);
	$encrypted = substr($encrypted,1,16);
	return $encrypted;
}

function writeCSS() {
echo "<style type='text/css'>";
echo "A:link {text-decoration: none; color: #ffffff;}";
echo "A:visited {text-decoration: none; color: #ffffff;}";
echo "A:active {text-decoration: none; color: #ffffff;}";
echo "A:hover {text-decoration: underline; color: #ff0000;}";
echo "#top { vertical-align: top; }";
echo "#ps  { height : 100%; vertical-align: bottom; }";
echo "#top3 { vertical-align: top; }";
echo "#top2 { vertical-align: bottom; }";
echo "body {color: #FFFFFF;";
echo "background-color: #000000;";
echo "}";
echo "#font {font-size: x-small; color: #FF00FF;}";
echo "#maintable {background-color: #000000;}";
echo "#inv {display:inline;}";
echo "</style>";
}

function redirect($url)
{
	if (strstr(urldecode($url), "\n") || strstr(urldecode($url), "\r"))
	{
		message_die(GENERAL_ERROR, 'Redirect Failed.');
	}

	$server_protocol = '';
	$server_name = '';
	$server_port = '';
	$script_name = '';
	$script_name = ($script_name == '') ? $script_name : '/' . $script_name;
	$url = trim($url);

	// Redirect via an HTML form for PITA webservers
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="2; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
		exit;
}
	
function connectDB() {
include_once('config.php');

$sql = mysql_connect ($dbhost, $dbuser, $dbpass);
if (!$sql) {

unset($dbpass);
return false;

}
else {

mysql_select_db ($dbname);
unset($dbpass);
return true;

}

}

function spellpower($sName) {
$power = 0;

if (strlen($sName) <= 11) {
$power += substr_count(strtolower($sName), 'si');
$power += (substr_count(strtolower($sName), 'yy')*250);
$power += substr_count(strtolower($sName), 'er');
$power += substr_count(strtolower($sName), 'g');
$power += (substr_count(strtolower($sName), 'xy')*65);
$power += substr_count(strtolower($sName), 'va');
$power += substr_count(strtolower($sName), 'a');
$power += substr_count(strtolower($sName), 'e');
$power += substr_count(strtolower($sName), 'i');
$power += substr_count(strtolower($sName), 'o');
$power += substr_count(strtolower($sName), 's');
$power += substr_count(strtolower($sName), 'y');
$power += substr_count(strtolower($sName), 'u');
$power += (substr_count(strtolower($sName), 'zking')*350);
$power += (substr_count(strtolower($sName), 'undeatha')*550);
$power += (substr_count(strtolower($sName), 'one')*10);
$power += (substr_count(strtolower($sName), 'z')*5);
$power += (substr_count(strtolower($sName), 'snickers')*60);
$power += (substr_count(strtolower($sName), 'twinky')*60);
$power += (substr_count(strtolower($sName), 'puck')*40);
$power += (substr_count(strtolower($sName), 'ridyam')*15);
}
else
{
$power = 0;
}

return $power;
}

function spelltype($sName) {

$powerTY += substr_count(strtolower($sName), 'ga');
$powerTY += substr_count(strtolower($sName), 'sa');
$powerTY += substr_count(strtolower($sName), 'er');
$powerTY += substr_count(strtolower($sName), 'tt');
$powerTY += substr_count(strtolower($sName), 'yy');
$powerTY += substr_count(strtolower($sName), 'g');
$powerTY += substr_count(strtolower($sName), 'z');
$powerTY += substr_count(strtolower($sName), 's');
$powerTY += substr_count(strtolower($sName), 'e');
$powerTY += substr_count(strtolower($sName), 'i');
$powerTY += substr_count(strtolower($sName), 'u');
$powerTY += substr_count(strtolower($sName), 'o');

if (substr_count(strtolower($sName), 'undeatha') > 0 or substr_count(strtolower($sName), 'sport') > 0) {
$powerTY = 5;
}

while ($powerTY > 3 or $powerTY < 0) {
$result = $powerTY - 5;
if ($result < 0) {
$result2 = 4 - abs($result);
}
else
{
$result2 = $result;
}

if (substr_count(strtolower($sName), 'undeatha') > 0 or substr_count(strtolower($sName), 'sport') > 0) {
$powerTY = 5;
break;
}

$powerTY = $result2;
$tries++;
if ($tries > 200) {
$powerTY == 0;
break;
}
}

return $powerTY;
}

function copyright_protection() {
	echo "<center>Copyright 2008 &copy; Voided Alliance<br>";

	echo "All Rights Reserved to Auburnflame Productions.<br><b>Powered by <a href='http://www.auburnflame.com/'>Auburnflame Productions</a></b></center>";
}

function spelltypeT($powerN, $powerTYN) {
if ($powerN > 0) {
//Done, set the thing up!
switch ($powerTYN) {
case 0:
$powerTYt = "healing.";
break;
case 1:
$powerTYt = "fire damage.";
break;
case 2:
$powerTYt = "frost damage.";
break;
case 3:
$powerTYt = "shock damage.";
break;
case 5:
$powerTYt = "undeath power.";
break;
default:
$powerTYt = "absolutly nothing.";
break;
}
} else {
$powerTYt = "absolutly nothing.";
}

return $powerTYt;
}

function powerDesc($power) {
if ($power < 1) {
$powerD = "useless";
}
elseif ($power > 0 and $power < 50) {
$powerD = "marginally powerful";
}
elseif ($power > 49 and $power < 221) {
$powerD = "somewhat powerful";
}
elseif ($power > 220 and $power < 600) {
$powerD = "highly powerful";
}
else {
$powerD = "unbelivibly powerful";
}

return $powerD;
}

function cpl_wra() {
	//This Function is Under GPL Protection
	//You May NOT Edit It!
	echo "<img src='http://www.auburnflame.com/brk1.gif'>";
}

function writegamemenu() {
echo "<a href='index.php?page=game&gamepage=inbox'>The Mailbox</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=stats'>Your Status</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=store'>The Store</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=battle'>Battle</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=healer'>Healer</a> &#8226; </span>";
//echo "<a href='' onclick='javascript: window.open(\"/game/chat/\",\"RoomList\",\"location=no,menubar=no,resizable=no,status=no,toolbar=no,scrollbars=yes,width=550,height=450\");'>The Chatroom</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=library'>Library</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=travel'>Travel</a> &#8226; </span>";
echo "<a href='index.php?page=game&gamepage=map'>Explore</a></span>";
}

function damageCalc($str,$lvl,$bwd) {
$AttackP = ($lvl*3)+($str*2)-20;
$APdamage = floor($AttackP/14);

$min = $bwd;
$max = ($bwd+$APdamage);

srand ((double) microtime( )*1000000);
$damage = rand($min,$max);

return $damage;
}

function writecpMenu() {

//connectDB();

if (!empty($_COOKIE['va_users'])) {
$query2 = "SELECT * FROM va_session";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);
$userSession = $_COOKIE['va_users'];

for ($i = 0; $i < $num2; $i++) {
if (mysql_result($result2,$i,"session") == $userSession) {
$timeLeft = mysql_result($result2,$i,"time");
$usr_N_R = mysql_result($result2,$i,"user");
$foundSes = true;
}
}


}

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$access = mysql_result($result,$i,"access");
}
}


switch ($access) {
case 0:
echo "Bug!";
break;
case 1:
echo "Bug!!!";
break;
case 2:
echo "<a href='index.php?page=cp&cpage=editC'>Edit User Credits</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=addban'>Add Ban</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=tick'>User Tickets</a></span>";
break;
case 3:
echo "<a href='index.php?page=cp&cpage=listitems'>View/Edit Items</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editC'>Edit User Credits</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editS'>Edit User Strength</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editD'>Edit User Defense</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editH'>Edit User Max HP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editM'>Edit User Max MP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editX'>Edit User XP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=addban'>Add Ban</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=tick'>User Tickets</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editdescription'>Edit Description</a> </span>";
break;
case 4:
echo "<a href='index.php?page=cp&cpage=addcharactor'>Create Charactor</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=listitems'>View/Edit Items</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editC'>Edit User Credits</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editS'>Edit User Strength</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editD'>Edit User Defense</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editH'>Edit User Max HP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editM'>Edit User Max MP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editX'>Edit User XP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=additem'>Create Item</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=addban'>Add Ban</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editNews'>Add News</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=version'>Version Info</a> &#8226;</span>";
echo "<a href='index.php?page=cp&cpage=tick'>User Tickets</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editdescription'>Edit Description</a></span>";
break;
default:
echo "<a href='index.php?page=cp&cpage=addcharactor'>Create Charactor</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=listitems'>View/Edit Items</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editC'>Edit User Credits</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editS'>Edit User Strength</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editD'>Edit User Defense</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editH'>Edit User Max HP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editM'>Edit User Max MP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editX'>Edit User XP</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=additem'>Create Item</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=cRes'>Activate Reset</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=addban'>Add Ban</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=editNews'>Add News</a> &#8226; </span>";
echo "<a href='index.php?page=cp&cpage=tick'>User Tickets</a> &#8226;</span>";
echo "<a href='index.php?page=cp&cpage=version'>Version Info</a> &#8226;</span>";
echo "<a href='index.php?page=cp&cpage=editdescription'>Edit Description</a></span>";
break;
}
}

function GameIsOpen() {
return true;
}

?>