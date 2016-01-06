<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/
//Yes I am an important file!  Do not mess with me!


function writetop() {
$rightmenu = "Who is Online";
$leftmenu = "Links Menu";
$middlemenu = "";

switch($_GET['page']) {
Case 'index':
$middlemenu = "Main Page";
break;
Case 'login':
$middlemenu = "Login";
break;
Case 'register':
$middlemenu = "Register";
break;
Case 'game':
$middlemenu = "Main Game";
break;
Case 'rstep1':
$middlemenu = "Register";
break;
Case 'rstep2':
$middlemenu = "Register";
break;
Case 'regtaken':
$middlemenu = "Registration Failed";
break;
Case 'loginfailed':
$middlemenu = "Login Failed";
break;
Case 'loginsuccess':
$middlemenu = "Successful Login";
break;
Case 'regsuccess':
$middlemenu = "Successful Register";
break;
Case 'player':
$middlemenu = "Player Information";
break;
Case 'nr':
$middlemenu = "Page Not Released";
break;
Case 'rank':
$middlemenu = "Player Ranking List";
break;
Case 'rankC':
$middlemenu = "Choose Ranking Type";
break;
Case 'logoutsuccess':
$middlemenu = "Successful Logout";
break;
default:
$middlemenu = "Unknown Page";
break;
}
if (isset($_GET['gamepage'])) {
switch($_GET['gamepage']) {
Case 'excha':
$middlemenu .= " - Exchange";
break;
Case 'stats':
$middlemenu .= " - Stats";
break;
Case 'store':
$middlemenu .= " - Store";
break;
Case 'hire':
$middlemenu .= " - For Hire";
break;
Case 'map':
$middlemenu .= " - Map";
break;
Case 'univ':
$middlemenu .= " - Your Universe Information";
break;
Case 'inbox':
$middlemenu .= " - Mailbox";
break;
Case 'sendmail':
$middlemenu .= " - Mailbox";
break;
Case 'clearmail':
$middlemenu .= " - Mailbox";
break;
Case 'mailUNF':
$middlemenu .= " - Mailbox";
break;
Case 'mailHTML':
$middlemenu .= " - Mailbox";
break;
Case 'mbFull':
$middlemenu .= " - Mailbox";
break;
Case 'donC':
$middlemenu .= " - Donate Credits";
break;
Case 'donS':
$middlemenu .= " - Donation Succeeded!";
break;
Case 'donF':
$middlemenu .= " - Donation Failed!";
break;
Case 'battle':
$middlemenu .= " - Battle!";
break;
Case 'attack':
$middlemenu .= " - Attack!";
break;
}
}
echo "<font>";
echo "<table id='maintable' border='2' align='center' width='1024' bgcolor='#c0c0c0' cellpadding='0' cellspacing='0'>";
echo "<tr>";
echo "<td colspan='3'><a href='index.php?page=index' style='text-decoration: none;'><img src='images/mb1e.png' border=0></a></td>";
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "<center>";
echo $leftmenu;
echo "</center>";
echo "</td>";
echo "<td>";
echo "<center>";
echo $middlemenu;
echo "</center>";
echo "</td>";
echo "<td>";
echo "<center>";
echo $rightmenu;
echo "</center>";
echo "</td>";
echo "</tr>";
echo "<tr>";
}

?>
