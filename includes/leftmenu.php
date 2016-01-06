<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

function writeleft() {
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

echo "<td width=15% id='top'>";
echo "<center>";

$gOwns = false;

if (!empty($_COOKIE['va_users'])) {

$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"user") == $usr_N_R) {
$access = mysql_result($result,$i,"access");
$uID = mysql_result($result,$i,"id");
}
}

$query = "SELECT * FROM va_galaxy";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

for ($i = 0; $i < $num1; $i++) {
if (mysql_result($result,$i,"ownerid") == $uID) {
$gName = mysql_result($result,$i,"name");
$gOwns = true;
}
}

echo "<a href='index.php?page=index' name='map'>Home</a><br><br>";
echo "<a href='http://forum.auburnflame.com/'>Forum</a><br><br>";
echo "<a href='index.php?page=rankC'>Player Ranks</a><br><br>";
if ($access > 1) {
echo "<a href='index.php?page=cp'>VA Control Panel</a><br><br>";
}
if ($gOwns == true) {
echo "<a href='index.php?page=game&gamepage=gCP'>" . $gName . " Control Panel</a><br><br>";
}
echo "<a href='index.php?page=game'>Game</a><br><br>";
echo "<a href='index.php?page=game&gamepage=npcShops'>NPC Shop List</a><br><br>";
echo "<a href='index.php?page=game&gamepage=bounties'>Bounty Office</a><br><br>";
echo "<a href='index.php?page=game&gamepage=healer'>The Healer</a><br><br>";
echo "<a href='index.php?page=game&gamepage=inbox'>The Mail</a><br><br>";
echo "<a href='index.php?page=game&gamepage=battle'>Battle</a><br><br>";
echo "<a href='index.php?page=game&gamepage=stats'>Your Status</a><br><br>";
echo "<a href='index.php?page=game&gamepage=bank'>Your Bank</a><br><br>";
echo "<a href='index.php?page=game&gamepage=factory'>Your Factory</a><br><br>";
echo "<a href='index.php?page=game&gamepage=store'>The Store</a><br><br>";
echo "<a href='index.php?page=game&gamepage=itemSearch'>Item Search</a><br><br>";
echo "<a href='index.php?page=game&gamepage=library'>The Library</a><br><br>";
echo "<a href='index.php?page=game&gamepage=travel'>Travel</a><br><br>";
echo "<a href='' onclick='javascript: window.open(\"/game/chat/\",\"RoomList\",\"location=no,menubar=no,resizable=no,status=no,toolbar=no,scrollbars=yes,width=550,height=450\");'>The Chatroom</a><br><br>";
echo "<a href='index.php?page=game&gamepage=map'>Explore</a><br><br>";
echo "<a href='index.php?page=game&gamepage=logout'>Logout</a>";
} else {
echo "<a href='index.php?page=index'>Home</a><br><br>";
//Change After Release...
echo "<a href='index.php?page=register'>Register</a><br><br>";
echo "<a href='index.php?page=login'>Login</a><br><br>";
echo "<a href='index.php?page=rankC'>Player Ranks</a><br><br>";
echo "<a href='http://forum.auburnflame.com/'>Forum</a><br><br>";
}
echo "</center>";
echo "</td>";
}

?>
