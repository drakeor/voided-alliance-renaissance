<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

function writeright() {
//connectDB();
$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num = mysql_numrows($result);

$query2 = "SELECT * FROM va_guilds";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

$query3 = "SELECT * FROM va_bans";
$result3 = mysql_query($query3);
$num3 = mysql_numrows($result3);

echo "<td width=15% id='top' height=70%>";
echo "<center>";
$numberonline = 0;
$onlinelist = "";
$logouttime = date('ymdHi');
$logouttime = floor($logouttime);
$isbanned3 = false;

$banTime = date('ymdHi');
$banTime = floor($banTime);


for ($i = 0; $i < $num; $i++) {
$id = mysql_result($result,$i,"id");
$row = mysql_result($result,$i,"lastref");
$row2 = mysql_result($result,$i,"user");
$access = mysql_result($result,$i,"access");
$guildID = mysql_result($result,$i,"guild");

for ($i2 = 0; $i2 < $num3; $i2++) {
if (mysql_result($result3,$i2,"playerid") == $id) {
$isbanned3 = true;
}
}

if ($row >= $logouttime and $isbanned3 != true) {
$numberonline++;
if ($access == 1) {
$onlinelist .= "<a href='index.php?page=player&id=" . $id . "'>" . $row2 . "</a><br>";
}
elseif ($access == 0) {
$onlinelist .= "<a href='index.php?page=player&id=" . $id . "'>" . $row2 . "</a><br>";}
elseif ($access == 2) {
//moderator
$onlinelist .= "<b><a href='index.php?page=player&id=" . $id . "'><span style='color: #ffffff'>" . $row2 . "</span></a></b> (Mod)</span><br>";
}
elseif ($access == 3) {
//Global Moderator
$onlinelist .= "<b><a href='index.php?page=player&id=" . $id . "'><span style='color: #c27000'>" . $row2 . "</span></a></b><span style='color: #c27000'> (G. Mod)</span><br>";
}
elseif ($access == 4) {
//Admin
$onlinelist .= "<b><a href='index.php?page=player&id=" . $id . "'><span style='color: #00c700'>" . $row2 . "</span></a></b> <span style='color: #00c700'>(Admin)</span><br>";
}
elseif ($access > 4)
{
//Zepheria...
$onlinelist .= "<b><a href='index.php?page=player&id=" . $id . "'><span style='color: #ff0000'>" . $row2 . "</span></a></b> <span style='color: #ff0000'>(Owner)</span><br>";
}
//Access Done... Now guild:
if ($guildID != 0) {
for($i2 = 0; $i2 < $num2; $i2++) {
if (mysql_result($result2, $i2, "id") == $guildID) {
$onlinelist .= "Guild:  " . mysql_result($result2, $i2, "name") . "<hr>";
}
}
}
else {
$onlinelist .= "Guild:  None<hr>";
}


}

if ($isbanned3 == true) {
$isbanned3 = false;
}

}


if ($numberonline > 0) {
echo $onlinelist;
$noneOnline = "";
}
else
{
echo "There is no one online.";
$noneOnline = "<hr>";
}
echo $noneOnline;
echo "Player Search";
?>
<form action="Psearch.php" method="post">
<input type="text" name="name" /><br />
<button type="submit">Search</button>
</form>
<script type="text/javascript">

var currenttime = '<!--#config timefmt="%B %d, %Y %H:%M:%S"--><!--#echo //var="DATE_LOCAL" -->' //SSI method of getting server date
var currenttime = '<?php print date("F d, Y H:i:s", time())?>' //PHP method of getting server date

var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("servertime").innerHTML=timestring
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

</script>

<p><b>Current Game Time:</b> <span id="servertime"></span></p>

<hr>
<?php
echo "</center>";
echo "</td>";

}

?>