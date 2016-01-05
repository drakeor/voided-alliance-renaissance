<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/




if (!isset($_GET['page'])) {
    if (!isset($_COOKIE['va_users'])) {
        header('Location: index.php?page=index');
    } else {
        header('Location: index.php?page=game');
    }
}
include('includes/header.php');
include('includes/footer.php');
include('includes/rightmenu.php');
include('includes/leftmenu.php');
include('includes/functions.php');

$connected = connectDB();

if (!empty($_COOKIE['va_users'])) {
    $userSession = $_COOKIE['va_users'];
    
    $query2  = "SELECT * FROM va_session";
    $result2 = mysql_query($query2);
    $num2    = mysql_numrows($result2);
    
    for ($i = 0; $i < $num2; $i++) {
        if (mysql_result($result2, $i, "session") == $_COOKIE['va_users']) {
            $timeLeft = mysql_result($result2, $i, "time");
            $usr_N_R  = mysql_result($result2, $i, "user");
            $foundSes = true;
        }
    }
    
    if ($foundSes != true) {
        setcookie("va_users", FALSE);
        header("Location: index.php?page=index");
        die();
    }
}

?>
<html>
<head>
<?

$pagename = $_GET['page'];
$pagenum  = 0;

switch ($pagename) {
    case 'index':
        $pagenum = 0;
        break;
    case 'game':
        $pagenum = 1;
        break;
    case 'register':
        $pagenum = 2;
        break;
    case 'login':
        $pagenum = 3;
        break;
    case 'player':
        $pagenum = 4;
        break;
    case 'nr':
        $pagenum = 5;
        break;
    case 'rstep1':
        $pagenum = 2;
        break;
    case 'rstep2':
        $pagenum = 2;
        break;
    case 'regtaken':
        $pagenum = 2;
        break;
    case 'regsuccess':
        $pagenum = 2;
        break;
    case 'loginfailed':
        $pagenum = 3;
        break;
    case 'loginsuccess':
        $pagenum = 3;
        break;
}

writeCSS();
?>
<title>
<?
wti($pagenum);
?>
</title>

<script language="javascript" type="text/javascript">
function emoticon(newtext) {
	document.sendmail.message.value += newtext;
}
</script>

<?
if (empty($_COOKIE['va_users']) == false) {
    $lateRef = date('ymdH');
    $lateRef .= (date('i') + 15);
    $query = "UPDATE va_users SET lastref='" . $lateRef . "' WHERE user='" . $usr_N_R . "'";
    mysql_query($query) or die("Unable to edit Refresh!");
}
?>


</head>
<body>
<?
writetop();
writeleft();

//Start write of center
echo "<td width=70% id='top'>";
echo "<center>";
if (empty($_COOKIE['va_users']) == false) {
    //Write the form if you have unread mail!
    $query  = "SELECT * FROM va_mailbox";
    $result = mysql_query($query);
    $num1   = mysql_numrows($result);
    
    $badpass = md5("abcd");
    
    $query2  = "SELECT * FROM va_users";
    $result2 = mysql_query($query2);
    $num2    = mysql_numrows($result2);
    
    for ($i = 0; $i < $num2; $i++) {
        if (mysql_result($result2, $i, "user") == $usr_N_R) {
            $MBid = mysql_result($result2, $i, "mailbox");
            $pass = mysql_result($result2, $i, "password");
        }
    }
    
    for ($i2 = 0; $i2 < $num1; $i2++) {
        if (mysql_result($result, $i2, "id") == $MBid) {
            //die($MBid);
            for ($i3 = 0; $i3 < 30; $i3++) {
                if (mysql_result($result, $i2, "mbT" . $i3) == "Unread") {
                    $unread = true;
                }
            }
            
        }
    }
    if ($unread == true and isset($_GET['mail']) == false) {
        echo "<b><a href='index.php?page=game&gamepage=inbox'><span style='color: #ff0000'>You have unread mail! Click here to Read it!</span></a></b><hr>";
    }
    
    if ($badpass == $pass) {
        echo "<b><a href='index.php?page=game&gamepage=changepass'><span style='color: #00ff00'>It is highly recommended that you change your password, click here to do so.</span></a></b><hr>";
    }
    
}
switch ($pagename) {
    case 'rankC':
        echo "<br>Please choose what to order by:<br><a href='index.php?page=rank&type=0'>Credits</a><br><a href='index.php?page=rank&type=1'>Level</a><br><a href='index.php?page=rank&type=2'>Strength</a><br><a href='index.php?page=rank&type=3'>Defense</a><br><a href='index.php?page=rank&type=4'>Armor Class</a><br><a href='index.php?page=rank&type=5'>HP</a><br><a href='index.php?page=rank&type=6'>MP</a><br><a href='index.php?page=rank&type=7'>PVP Score</a><hr>";
        break;
    case 'rank':
        //if (empty($_COOKIE['va_users'])==true) {
        //$connected = connectDB();
        //}
        echo "<br><b>Ranks</b><hr>";
        if (isset($_GET['type'])) {
            switch ($_GET['type']) {
                case 0:
                    $measure  = "currency";
                    $measureT = "Credits";
                    break;
                case 1:
                    $measure  = "level";
                    $measureT = "Level";
                    break;
                case 2:
                    $measure  = "str";
                    $measureT = "Strength";
                    break;
                case 3:
                    $measure  = "def";
                    $measureT = "Defense";
                    break;
                case 4:
                    $measure  = "AC";
                    $measureT = "Armor Class";
                    break;
                case 5:
                    $measure  = "maxHP";
                    $measure2 = "currentHP";
                    $measureT = "HP";
                    break;
                case 6:
                    $measure  = "maxMP";
                    $measure2 = "currentMP";
                    $measureT = "MP";
                    break;
                case 7:
                    $measure  = "pvp";
                    $measureT = "PvP Score";
                    $pvp      = true;
                    break;
            }
        } else {
            $measure  = "currency";
            $measureT = "Credits";
        }
?>
<table border="0" width="100%">
<tr>
<td width="10%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Rank</font></small></td>
<td width="50%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">User</font></small></td>
<td width="20%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">
<?
        echo $measureT;
?>
</font></small></td>
<td width="20%" bgcolor="#a16410"><p align="left"><small><font face="Verdana">Join Date</font></small></td>
<?
        $query = "SELECT * FROM va_users ORDER BY " . $measure . " DESC";
        $result = mysql_query($query) or die(mysql_error());
        $number = mysql_numrows($result) or die(mysql_error());
        
        if ($number) {
            $rank = 1;
            $i2   = 0;
            for ($i = 0; $i < $number; $i++) {
                $uID[$i]   = mysql_result($result, $i, "id");
                $name[$i]  = mysql_result($result, $i, "user");
                $rankT[$i] = mysql_result($result, $i, $measure);
                if (isset($measure2)) {
                    $rankT2[$i] = mysql_result($result, $i, $measure2);
                }
                $joindate[$i] = mysql_result($result, $i, "joindate");
                $access[$i]   = mysql_result($result, $i, "access");
                $usertype[$i] = mysql_result($result, $i, "usertype");
            }
            
            
            while ($uID[$i2] != "") {
                if ($access[$i2] == 1 and $usertype[$i2] == 0) {
                    print("</tr><tr>");
                    if ($color == "#636363") {
                        $color = "#000000";
                    } else {
                        $color = "#636363";
                    }
                    
                    switch ($access[$i2]) {
                        case 0:
                            $name1[$i2] = "<i>" . $name[$i2] . " (Banned)</i>";
                            break;
                        case 1:
                            $name1[$i2] = $name[$i2];
                            break;
                        case 2:
                            $name1[$i2] = "<span style='color: #ffffff'><b>" . $name[$i2] . "</b> (Moderator)</span>";
                            break;
                        case 3:
                            $name1[$i2] = "<span style='color: #c27000'><b>" . $name[$i2] . "</b> (G. Mod)</span>";
                            break;
                        case 4:
                            $name1[$i2] = "<span style='color: #00c700'><b>" . $name[$i2] . "</b> (Admin)</span>";
                            break;
                        case 5:
                            $name1[$i2] = "<span style='color: #ff0000'><b>" . $name[$i2] . "</b> (Owner)</span>";
                            break;
                    }
                    
                    print("<td width=\"6%\" bgcolor=\"$color\"><center><small>");
                    print("<font face=\"Verdana\">" . $rank . "</font></small></center></td>");
                    print("<td width=\"7%\" bgcolor=\"$color\"><center><small>");
                    print("<font face=\"Verdana\"><a href='index.php?page=player&id=" . $uID[$i2] . "'>" . $name1[$i2] . "</a></font></small></center></td>");
                    print("<td width=\"11%\" bgcolor=\"$color\"><center><small>");
                    if (isset($rankT2[$i2])) {
                        print("<font face=\"Verdana\">" . $rankT2[$i2] . "/" . $rankT[$i2] . "</font></small></center></td>");
                    } else {
                        print("<font face=\"Verdana\">" . $rankT[$i2] . "</font></small></center></td>");
                    }
                    print("<td width=\"76%\" bgcolor=\"$color\"><left><small>");
                    print("<font face=\"Verdana\">" . $joindate[$i2] . "</font></small></left></td>");
                    
                    $rank++;
                }
                $i2++;
            }
        }
?>
</table>
<?
        echo "<hr>";
        break;
    case 'nr':
        echo "The game is not relesed yet, once it is, we will activate the menu.<br>Until then try this: <a href='http://forum.auburnflame.com/'>Click Here to teleport to the forum.</a>";
        break;
    
    case 'index':
        //FIXED: edits main page via Control panel.
        $query  = "SELECT * FROM va_config";
        $result = mysql_query($query);
        $num1   = mysql_numrows($result);
        
        for ($i = 0; $i < $num1; $i++) {
            if (mysql_result($result, $i, "id") != "") {
                $description = mysql_result($result, $i, "description");
            }
        }
        echo $description;
        
        //You may edit the above text...
        break;
    
    case 'cp':
        $query  = "SELECT * FROM va_users";
        $result = mysql_query($query);
        $num1   = mysql_numrows($result);
        
        for ($i = 0; $i < $num1; $i++) {
            if (mysql_result($result, $i, "user") == $usr_N_R) {
                $access = mysql_result($result, $i, "access");
            }
        }
        
        switch ($_GET['cpage']) {
            case 'editNews2':
                if ($access > 3) {
                    $subject = $_POST['subject'];
                    $text    = $_POST['text'];
                    $newsID  = $_POST['newsID'];
                    
                    $query = "UPDATE va_news SET subject='" . $subject . "' WHERE id='" . $newsID . "'";
                    mysql_query($query) or die("Unable!");
                    $query = "UPDATE va_news SET text='" . $text . "' WHERE id='" . $newsID . "'";
                    mysql_query($query) or die("Unable again!");
                    echo "Successfully edited!";
                }
                break;
            case 'editNew':
                if ($access > 3) {
                    $newsID = $_GET['nID'];
                    
                    $query  = "SELECT * FROM va_news";
                    $result = mysql_query($query);
                    $num1   = mysql_numrows($result);
                    
                    for ($i = 0; $i < $num1; $i++) {
                        if (mysql_result($result, $i, "id") == $newsID) {
                            $subject = mysql_result($result, $i, "subject");
                            $text    = mysql_result($result, $i, "text");
                        }
                    }
                    
?>
<form action="index.php?page=cp&cpage=editNews2" method="post">
<input type="hidden" name="newsID" value=<?
                    print("\"" . $newsID . "\"");
?> >
Subject: <input type="text" name="subject"
<?
                    echo "value=\"" . $subject . "\" /><br /><br />";
?>
Text<hr> <textarea cols="45" rows="15" name="text">
<?
                    echo $text;
?>
</textarea><hr><br />
<button type="submit">Edit News</button>
</form>
<?
                } else {
                    echo "<br />You cannot delete news--You lack sufficient rank.";
                }
                break;
            case 'delNews':
                if ($access > 3) {
                    $newsID = $_GET['nID'];
                    
                    $query = "DELETE FROM va_news WHERE id = '" . $newsID . "'";
                    mysql_query($query) or die($query);
                    echo "<br>News Deleted!";
                } else {
                    echo "<br />You cannot delete news--You lack sufficient rank.";
                }
                break;
            case 'addban':
?>
<form action="addban.php" method="post">
User: <input type="text" name="user" /><br />
Minutes to Ban: <input type="text" name="length" /><br />(Moderators can ban up to 10 minutes, Global Moderators can ban up to an hour, and Admins can perma-ban.)<br />
<button type="submit">Add Ban</button>
</form>
<?
                break;
            case 'editdescription':
?>
<form action="editdescription.php" method="post">
Description:<br><textarea  name="frontpage"  rows = "15" cols = "45"></textarea><br>
<button type="Submit">Edit Front Page</button>
</form>
<?
                break;
            case 'addcharactor':
?>
<form action="addchar.php" method="post">
Name: <input type="text" name="name" /><br />
Description: <input type="text" name="description" /><br />
Image: <input type="text" name="image" /> <br />(Please use it in this format: images/class/item.png)
<br />
<button type="submit">Add Charactor</button>
</form>
<?
                break;
            case 'editNews':
                if ($access > 3) {
?>
<form action="addnews.php" method="post">
Subject: <input type="text" name="subject" /><br /><br />
Text<hr> <textarea cols="45" rows="15" name="text"></textarea><hr><br />
<button type="submit">Add News</button>
</form>
<?
                }
                break;
            case 'editC':
                if ($access > 1) {
?>
<form action="editcredits.php" method="post">
User: <input type="text" name="user" /><br />
New Credits Amount: <input type="text" name="credits" /><br />
<br />
<button type="submit">Edit Credits</button>
</form>
<?
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'editS':
                if ($access > 2) {
?>
<form action="editstats.php" method="post">
User: <input type="text" name="user" /><br />
New Strength Amount: <input type="text" name="AP" /><br />
<input type="hidden" name="stat" value="1" />
<br />
<button type="submit">Edit Strength</button>
</form>
<?
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'editD':
                if ($access > 2) {
?>
<form action="editstats.php" method="post">
User: <input type="text" name="user" /><br />
New Defense Amount: <input type="text" name="AP" /><br />
<input type="hidden" name="stat" value="2" />
<br />
<button type="submit">Edit Defense</button>
</form>
<?
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'editH':
                if ($access > 2) {
?>
<form action="editstats.php" method="post">
User: <input type="text" name="user" /><br />
New Max Health: <input type="text" name="AP" /><br />
<input type="hidden" name="stat" value="3" />
<br />
<button type="submit">Edit Health</button>
</form>
<?
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'editM':
                if ($access > 2) {
?>
<form action="editstats.php" method="post">
User: <input type="text" name="user" /><br />
New Max Mana: <input type="text" name="AP" /><br />
<input type="hidden" name="stat" value="4" />
<br />
<button type="submit">Edit Mana</button>
</form>
<?
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'editX':
                if ($access > 2) {
?>
<form action="editstats.php" method="post">
User: <input type="text" name="user" /><br />
New User XP: <input type="text" name="AP" /><br />
<input type="hidden" name="stat" value="5" />
<br />
<button type="submit">Edit XP</button>
</form>
<?
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'itemTypes':
                echo "<hr>";
                echo "0 = Health Potion<br />";
                echo "1 = Weapon<br />";
                echo "2 = Shield<br />";
                echo "3 = Helmet<br />";
                echo "4 = Armor<br />";
                echo "5 = Gloves<br />";
                echo "6 = Book, you need my Help if you want to add one. -Raygoe<br />";
                echo "7 = Mana Potion<br />";
                echo "8 = Buff Item (No Effect Accept Buff)<br />";
                echo "9 = Null Item (No Effect, DO NOT ADD BUFF!)<br />";
                echo "10 = Boots";
                echo "<hr>";
                break;
            case 'edititem':
                if ($access > 3) {
                    $id = $_GET['itemid'];
                    
                    $query2  = "SELECT * FROM va_items";
                    $result2 = mysql_query($query2);
                    $num2    = mysql_numrows($result2);
                    
                    for ($i = 0; $i < $num2; $i++) {
                        if (mysql_result($result2, $i, "id") == $id) {
                            $rareRoll    = mysql_result($result2, $i, "rareRoll");
                            $name        = mysql_result($result2, $i, "name");
                            $description = mysql_result($result2, $i, "description");
                            $image       = mysql_result($result2, $i, "image");
                            $type        = mysql_result($result2, $i, "type");
                            $AP          = mysql_result($result2, $i, "AP");
                            $isbuffed    = mysql_result($result2, $i, "isbuffed");
                            $buffimage   = mysql_result($result2, $i, "buffimage");
                            $buffname    = mysql_result($result2, $i, "buffname");
                            $battlesleft = mysql_result($result2, $i, "battlesleft");
                            $str         = mysql_result($result2, $i, "str");
                            $def         = mysql_result($result2, $i, "def");
                            $maxHP       = mysql_result($result2, $i, "maxHP");
                            $maxMP       = mysql_result($result2, $i, "maxMP");
                        }
                    }
                    
?>
<form action="edititem.php" method="post">
<input type="hidden" name="id" <?
                    print("value='" . $id . "'");
?> />
Rarity Roll: <input type="text" name="rareroll" <?
                    print("value='" . $rareRoll . "'");
?> /> <br />(Number of sides the Drop Rate dice has)<br /><br />
Item Name: <input type="text" name="itemName" <?
                    print("value='" . $name . "'");
?>/><br />
Item Description: <input type="text" name="itemDescribe" <?
                    print("value='" . $description . "'");
?>/><br />
Item Image: <input type="text" name="itemimage" <?
                    print("value='" . $image . "'");
?>/> <br />(Please use it in this format: images/items/item.png) <a href='images/items/' target="_blank">Click Here For List (Opens new window)</a><br /><br />
Item Type: <input type="text" name="itemtype" <?
                    print("value='" . $type . "'");
?>/> <br /><a href="index.php?page=cp&cpage=itemTypes" target="_blank">Click Here for list (Opens new Window)</a><br /><br />
Action Points: <input type="text" name="itemAP" <?
                    print("value='" . $AP . "'");
?>/> <br />(How much the effect of the item has, HP = Health Restored, Weapon=Damage, etc.)<br /><br />
Is Buffed: <input type="checkbox" name="isbuffed" <?
                    print("value='" . $isbuffed . "'");
?>/> <br />(If it isn't buffed you dont have to go on, just leave it as it is)<br /><br />
Buff Name: <input type="text" name="buffName" <?
                    print("value='" . $buffimage . "'");
?>/> <br />
Buff Image: <input type="text" name="buffImage" <?
                    print("value='" . $buffname . "'");
?>/> <br />(Please use it in this format: images/items/item.png) <a href='images/items/' target="_blank">Click Here For List (Opens new window)</a><br /><br />
Buff Battles Left: <input type="text" name="buffBL" <?
                    print("value='" . $battlesleft . "'");
?>/> <br />(How many battles it has until the buff would expire)<br /><br />
Buff Strength Gained: <input type="text" name="buffSTR" <?
                    print("value='" . $str . "'");
?>/><br />
Buff Defense Gained: <input type="text" name="buffDEF" <?
                    print("value='" . $def . "'");
?>/><br />
Buff Max HP Gained: <input type="text" name="buffHP" <?
                    print("value='" . $maxHP . "'");
?>/><br />
Buff Max MP Gained: <input type="text" name="buffMP" <?
                    print("value='" . $maxMP . "'");
?>/><br />
<br />
<button type="submit">Edit Item</button>
</form>
<?
                }
                break;
            case 'listitems':
                if ($access > 3) {
                    
                    $query  = "SELECT * FROM va_items";
                    $result = mysql_query($query);
                    $num1   = mysql_numrows($result);
                    
                    for ($i = 0; $i < $num1; $i++) {
                        if ($color == "#636363") {
                            $color = "#000000";
                        } else {
                            $color = "#636363";
                        }
                        $Iid          = mysql_result($result, $i, "id");
                        $Iimage       = mysql_result($result, $i, "image");
                        $Iname        = mysql_result($result, $i, "name");
                        $Itype        = mysql_result($result, $i, "type");
                        $Idescription = mysql_result($result, $i, "description");
                        $AP           = mysql_result($result, $i, "AP");
                        
                        switch ($Itype) {
                            case 0:
                                $typeT = "Health Potion";
                                break;
                            case 1:
                                $typeT = "Weapon";
                                break;
                            case 2:
                                $typeT = "Shield";
                                break;
                            case 3:
                                $typeT = "Helmet";
                                break;
                            case 4:
                                $typeT = "Armor";
                                break;
                            case 5:
                                $typeT = "Gloves";
                                break;
                            case 6:
                                $typeT = "Scripted Item";
                                break;
                            case 7:
                                $typeT = "Mana Potion";
                                break;
                            case 8:
                                $typeT = "Buff Item";
                                break;
                            case 9:
                                $typeT = "Nulled Item";
                                break;
                            case 10:
                                $typeT = "Boots";
                                break;
                        }
                        
                        $textI .= "<tr><td bgcolor=\"$color\"><center><small>";
                        $textI .= "<font face=\"Verdana\">" . $Iid . "</font></small></center></td>";
                        $textI .= "<td bgcolor=\"$color\"><center><small>";
                        $textI .= "<font face=\"Verdana\"><a href='index.php?page=cp&cpage=edititem&itemid=" . $Iid . "'><img src=\"" . $Iimage . "\" border=\"2\"></a></font></small></center></td>";
                        $textI .= "<td bgcolor=\"$color\"><center><small>";
                        $textI .= "<font face=\"Verdana\">" . $Iname . "</font></small></center></td>";
                        $textI .= "<td bgcolor=\"$color\"><center><small>";
                        $textI .= "<font face=\"Verdana\">" . $typeT . "</font></small></center></td>";
                        $textI .= "<td bgcolor=\"$color\"><center><small>";
                        $textI .= "<font face=\"Verdana\">" . $AP . "</font></small></center></td>";
                        $textI .= "<td bgcolor=\"$color\"><center><small>";
                        $textI .= "<font face=\"Verdana\">" . $Idescription . "</font></small></center></td></tr>";
                    }
                    
                    
?>
<hr>
List of Items<br>
(Click image to edit item)
<hr>
<table border="0" width="100%">
<tr>
<td width="5%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item ID</font></small></td>
<td width="15%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item Image</font></small></td>
<td width="10%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item Name</font></small></td>
<td width="5%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item Type</font></small></td>
<td width="5%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">AP</font></small></td>
<td width="60%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Description</font></small></td>
</tr>
<?
                    echo $textI;
                    
?>
</tr></table>
<?
                    
                } else {
                    echo "<hr>You lack the sufficient rank!<hr>";
                }
                
                break;
            case 'additem':
?>
<form action="additem.php" method="post">
Rarity Roll: <input type="text" name="rareroll" /> <br />(Number of sides the Drop Rate dice has)<br /><br />
Item Name: <input type="text" name="itemName" /><br />
Item Description: <input type="text" name="itemDescribe" /><br />
Item Image: <input type="text" name="itemimage" /> <br />(Please use it in this format: images/items/item.png) <a href='images/items/' target="_blank">Click Here For List (Opens new window)</a><br /><br />
Item Type: <input type="text" name="itemtype" /> <br /><a href="index.php?page=cp&cpage=itemTypes" target="_blank">Click Here for list (Opens new Window)</a><br /><br />
Action Points: <input type="text" name="itemAP" /> <br />(How much the effect of the item has, HP = Health Restored, Weapon=Damage, etc.)<br /><br />
Is Buffed: <input type="checkbox" name="isbuffed" /> <br />(If it isn't buffed you dont have to go on, just leave it as it is)<br /><br />
Buff Name: <input type="text" name="buffName" /> <br />
Buff Image: <input type="text" name="buffImage" /> <br />(Please use it in this format: images/items/item.png) <a href='images/items/' target="_blank">Click Here For List (Opens new window)</a><br /><br />
Buff Battles Left: <input type="text" name="buffBL" /> <br />(How many battles it has until the buff would expire)<br /><br />
Buff Strength Gained: <input type="text" name="buffSTR" /><br />
Buff Defense Gained: <input type="text" name="buffDEF" /><br />
Buff Max HP Gained: <input type="text" name="buffHP" /><br />
Buff Max MP Gained: <input type="text" name="buffMP" /><br />
<br />
<button type="submit">Add Item</button>
</form>
<?
                break;
            case 'tickR':
                if ($access > 1) {
                    $query2  = "SELECT * FROM va_ticket";
                    $result2 = mysql_query($query2);
                    $num2    = mysql_numrows($result2);
                    
                    for ($i = 0; $i < $num2; $i++) {
                        if (mysql_result($result2, $i, "id") == $_GET['id']) {
                            $user = mysql_result($result2, $i, "sender");
                            $text = mysql_result($result2, $i, "text");
                            $date = mysql_result($result2, $i, "date");
                        }
                    }
                    echo "<br /><br>Sent By: " . $user . "<br><hr>Text<hr><span style='color: #00FF00'>" . $text . "</span><hr>Date: " . $date . "<hr>";
                    echo "<br /><br /><a href='index.php?page=cp&cpage=tickD&id=" . $_GET['id'] . "'>Delete Ticket</a>";
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'tickD':
                if ($access > 1) {
                    $query = "DELETE FROM va_ticket WHERE id = '" . $_GET['id'] . "'";
                    mysql_query($query) or die($query);
                    echo "<br>Ticket Deleted!";
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            case 'cRes':
                if ($access > 4) {
                    echo "<br />Are you sure you want to activate a Reset?<br />";
                    echo "[<a href='index.php?page=cp&cpage=reset'>Yes</a> | <a href='index.php?page=cp'>No</a>]";
                } else {
                    echo "<br />You do not have the required rank to reset the game.";
                }
                break;
            
            case 'version':
                if ($access > 1) {
                    $query  = "SELECT * FROM va_config";
                    $result = mysql_query($query);
                    $num1   = mysql_numrows($result);
                    
                    for ($i = 0; $i < $num1; $i++) {
                        if (mysql_result($result, $i, "id") == 1) {
                            $lastReset = mysql_result($result, $i, "lastreset");
                            
                        }
                    }
                    $fName = 'http://www.auburnflame.com/va_version.php';
                    
                    $handle    = fopen($fName, "rb");
                    $latestVer = '';
                    while (!feof($handle)) {
                        $latestVer .= fread($handle, 8192);
                    }
                    fclose($handle);
                    
                    echo "Your version is: <b>Revolution</b><br> Latest Version is: <b>" . $latestVer . "</b><br> Last Reset: <b>" . $lastReset . "</b><br><br>To find updates visit the <a href='http://www.auburnflame.com/'>Auburnflame Productions Website</a><br>";
                    
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                
                break;
            
            case 'cRes':
                if ($access > 4) {
                    echo "<br />Are you sure you want to activate a Reset?<br />";
                    echo "[<a href='index.php?page=cp&cpage=reset'>Yes</a> | <a href='index.php?page=cp'>No</a>]";
                } else {
                    echo "<br />You do not have the required rank to reset the game.";
                }
                break;
            
            case 'reset':
                if ($access > 4) {
                    //Ok here goes, this can be extremely dangerous if done wrong...
                    //I will need to set all of the un-needed fields to nil or nothing...
                    //Starting with users...
                    $query  = "SELECT * FROM va_users";
                    $result = mysql_query($query);
                    $num1   = mysql_numrows($result);
                    
                    $query2  = "SELECT * FROM va_worktable";
                    $result2 = mysql_query($query);
                    $num2    = mysql_numrows($result);
                    
                    $query3  = "SELECT * FROM va_bank";
                    $result3 = mysql_query($query);
                    $num3    = mysql_numrows($result);
                    
                    $query = "UPDATE va_config SET lastreset='" . date("n.j.y") . "' WHERE id='" . 1 . "'";
                    mysql_query($query) or die("Unable to reset (Date)!");
                    
                    for ($i = 0; $i < $num2; $i++) {
                        if (mysql_result($result2, $i, "id") != "") {
                            $iderz = mysql_result($result2, $i, "id");
                            $query = "DELETE FROM va_worktable WHERE id='" . $iderz . "'";
                            mysql_query($query) or die("Unable to reset!");
                        }
                    }
                    
                    for ($i = 0; $i < $num3; $i++) {
                        if (mysql_result($result3, $i, "id") != "") {
                            $iderz2 = mysql_result($result3, $i, "id");
                            $query  = "DELETE FROM va_bank WHERE id='" . $iderz2 . "'";
                            mysql_query($query) or die("Unable to reset!");
                        }
                    }
                    
                    for ($i = 0; $i < $num1; $i++) {
                        if (mysql_result($result, $i, "user") != "") {
                            if (mysql_result($result, $i, "usertype") == "0") {
                                $ids   = mysql_result($result, $i, "id");
                                $query = "UPDATE va_users SET level='1' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET currency='100' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $query = "UPDATE va_users SET inv" . $i2 . "='0' WHERE id='" . $ids . "'";
                                    mysql_query($query) or die("Unable to reset!");
                                }
                                $query = "UPDATE va_users SET guild='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET shopid='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET maxHP='10' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET currentHP='10' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET maxMP='10' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET currentMP='10' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET currentXP='200' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET str='3' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET def='3' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET AC='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET eqWeapon='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET eqShield='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET eqHelmet='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET eqArmor='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET eqGloves='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET eqBoots='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET pvp='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET pk='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                $query = "UPDATE va_users SET points='0' WHERE id='" . $ids . "'";
                                mysql_query($query) or die("Unable to reset!");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $query = "UPDATE va_users SET spell" . $i2 . "='' WHERE id='" . $ids . "'";
                                    mysql_query($query) or die("Unable to reset!");
                                }
                            }
                        }
                    }
                    
                    //Ok I think that is all... Cross your fingers and hope it works...
                    
                    echo "Reset Completed...";
                } else {
                    echo "<br />You do not have the required rank to reset the game.";
                }
                break;
            case 'tick':
                if ($access > 1) {
                    $query2  = "SELECT * FROM va_ticket";
                    $result2 = mysql_query($query2);
                    $num2    = mysql_numrows($result2);
                    $tickets = 0;
                    for ($i = 0; $i < $num2; $i++) {
                        if (mysql_result($result2, $i, "id") != "") {
                            $tID[$i]  = mysql_result($result2, $i, "id");
                            $user[$i] = mysql_result($result2, $i, "sender");
                            $date[$i] = mysql_result($result2, $i, "date");
                            $tickets++;
                        }
                    }
                    
                    $max = $tickets + 1;
                    $i2  = 0;
                    for ($i = 1; $i < $max; $i++) {
                        
                        $ticketT .= "</tr><tr>";
                        if ($color == "#636363") {
                            $color = "#000000";
                        } else {
                            $color = "#636363";
                        }
                        
                        $ticketT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                        $ticketT .= "<font face=\"Verdana\">" . $i . "</font></small></center></td>";
                        $ticketT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                        $ticketT .= "<font face=\"Verdana\"><a href='index.php?page=cp&cpage=tickR&id=" . $tID[$i2] . "'>" . $user[$i2] . "</a></font></small></center></td>";
                        $ticketT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                        $ticketT .= "<font face=\"Verdana\">" . $date[$i2] . "</font></small></center></td>";
                        $i2++;
                    }
                    
                    
                    if ($tickets > 0) {
?>
<table border="0" width="100%">
<tr>
<td width="20%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Ticket</font></small></td>
<td width="40%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Sender</font></small></td>
<td width="40%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Date</font></small></td>
<?
                        echo $ticketT;
                        echo "</td></tr></table>";
                    } else {
                        echo "<br>There are no user tickets!";
                    }
                } else {
                    echo "<br />You cannot use the control panel--You have no rank.";
                }
                break;
            default:
                
                
                switch ($access) {
                    case 0:
                        echo "<br />You cannot use the control panel--You have no rank.";
                        break;
                    case 1:
                        echo "<br />You cannot use the control panel--You have no rank.";
                        break;
                    case 2:
?>
<br />
<table border=2 cellpadding="0" cellspacing="0" id="gamet" width=100% bgcolor="#2b2b2b">
<tr>
<td><center>
<?
                        writecpMenu();
?>
</center></td>
</tr>
</table>
<?
                        
                        
                        break;
                    case 3:
?>
<br />
<table border=2 cellpadding="0" cellspacing="0" id="gamet" width=100% bgcolor="#2b2b2b">
<tr>
<td><center>
<?
                        writecpMenu();
?>
</center></td>
</tr>
</table>
<?
                        
                        
                        break;
                    case 4:
?>
<br />
<table border=2 cellpadding="0" cellspacing="0" id="gamet" width=100% bgcolor="#2b2b2b">
<tr>
<td><center>
<?
                        writecpMenu();
?>
</center></td>
</tr>
</table>
<?
                        
                        
                        break;
                    default:
?>
<br />
<table border=2 cellpadding="0" cellspacing="0" id="gamet" width=100% bgcolor="#2b2b2b">
<tr>
<td><center>
<?
                        writecpMenu();
?>
</center></td>
</tr>
</table>
<?
                        
                        break;
                }
                
                break;
        }
        
        break;
    case 'player':
        
        $id     = $_GET['id'];
        $found  = false;
        $query  = "SELECT * FROM va_users";
        $result = mysql_query($query);
        $num1   = mysql_numrows($result);
        
        $query2  = "SELECT * FROM va_classes";
        $result2 = mysql_query($query2);
        $num2    = mysql_numrows($result2);
        
        $query3  = "SELECT * FROM va_guilds";
        $result3 = mysql_query($query3);
        $num3    = mysql_numrows($result3);
        
        $query4  = "SELECT * FROM va_galaxy";
        $result4 = mysql_query($query4);
        $num4    = mysql_numrows($result4);
        
        $name     = ""; //Check
        $guildID  = 0; //Check
        $guildN   = ""; //Check
        $guildD   = ""; //Check
        $pNote    = "";
        $avatar   = "";
        $galaxyID = 0; //Check
        $galaxyN  = ""; //Check
        $level    = 0; //Check
        $classID  = 0; //Check
        $classN   = ""; //Check
        $credits  = 0; //Check
        
        for ($i = 0; $i < $num1; $i++) {
            if (mysql_result($result, $i, "id") == $id) {
                //Great found the user! Get all the info I can!
                $found     = true;
                $name      = mysql_result($result, $i, "user");
                $guildID   = mysql_result($result, $i, "guild");
                $galaxyID  = mysql_result($result, $i, "galaxy");
                $classID   = mysql_result($result, $i, "class");
                $credits   = mysql_result($result, $i, "currency");
                $level     = mysql_result($result, $i, "level");
                $access    = mysql_result($result, $i, "access");
                $maxHP     = mysql_result($result, $i, "maxHP");
                $currentHP = mysql_result($result, $i, "currentHP");
                $maxMP     = mysql_result($result, $i, "maxMP");
                $currentMP = mysql_result($result, $i, "currentMP");
                $currentXP = mysql_result($result, $i, "currentXP");
                $str       = mysql_result($result, $i, "str");
                $def       = mysql_result($result, $i, "def");
                $AC        = mysql_result($result, $i, "AC");
                $PVP       = mysql_result($result, $i, "pvp");
            }
        }
        
        for ($i = 0; $i < $num2; $i++) {
            if (mysql_result($result2, $i, "id") == $classID) {
                //Great found the class! Get all the info I can!
                $classN = mysql_result($result2, $i, "name");
            }
        }
        
        if ($guildID != 0) {
            for ($i = 0; $i < $num3; $i++) {
                if (mysql_result($result3, $i, "id") == $guildID) {
                    //Great found the guild! Get all the info I can!
                    $guildN = mysql_result($result3, $i, "name");
                    $guildD = mysql_result($result3, $i, "description");
                }
            }
        } else {
            $guildN = $name . " does not have one!";
            $guildD = "";
            $none   = true;
        }
        
        for ($i = 0; $i < $num4; $i++) {
            if (mysql_result($result4, $i, "id") == $galaxyID) {
                //Great found the galaxy! Get all the info I can!
                $galaxyN = mysql_result($result4, $i, "name");
            }
        }
        
        switch ($access) {
            case 0:
                $name1 = "<i>" . $name . " (Banned)</i>";
                break;
            case 1:
                $name1 = $name;
                break;
            case 2:
                $name1 = "<span style='color: #ffffff'><b>" . $name . "</b> (Moderator)</span>";
                break;
            case 3:
                $name1 = "<span style='color: #c27000'><b>" . $name . "</b> (G. Mod)</span>";
                break;
            case 4:
                $name1 = "<span style='color: #00c700'><b>" . $name . "</b> (Admin)</span>";
                break;
            case 5:
                $name1 = "<span style='color: #ff0000'><b>" . $name . "</b> (Owner)</span>";
                break;
        }
        
        if ($currentHP == $maxHP) {
            $HPmessage = "<span style='color: #0000ff;'>HP: " . $currentHP . "/" . $maxHP . "</span>";
        } elseif ($currentHP > ($maxHP / 2)) {
            $HPmessage = "<span style='color: #00ff00;'>HP: " . $currentHP . "/" . $maxHP . "</span>";
        } elseif ($currentHP <= ($maxHP / 2) and $currentHP > 0) {
            $HPmessage = "<span style='color: #ffff00;'>HP: " . $currentHP . "/" . $maxHP . "</span>";
        } else {
            $HPmessage = "<span style='color: #ff0000;'>HP: " . $currentHP . "/" . $maxHP . "</span>";
        }
        
        if ($currentMP == $maxMP) {
            $MPmessage = "<span style='color: #0000ff;'>MP: " . $currentMP . "/" . $maxMP . "</span>";
        } elseif ($currentMP > ($maxMP / 2)) {
            $MPmessage = "<span style='color: #00ff00;'>MP: " . $currentMP . "/" . $maxMP . "</span>";
        } elseif ($currentMP <= ($maxMP / 2) and $currentMP > 0) {
            $MPmessage = "<span style='color: #ffff00;'>MP: " . $currentMP . "/" . $maxMP . "</span>";
        } else {
            $MPmessage = "<span style='color: #ff0000;'>MP: " . $currentMP . "/" . $maxMP . "</span>";
        }
        
        //Write the page
        echo "<br><table border=2 cellpadding=0 cellspacing=0 width=95%>";
        echo "<tr>";
        echo "<td height='16' id='top3' colspan='2'><center>";
        echo "User:&nbsp;&nbsp;" . $name1;
        echo "</center></td></tr>";
        echo "<tr>";
        echo "<td width='120' height='120' id='top3'>";
        echo "<img src='images/ava.png'>";
        echo "</td>";
        echo "<td height='120' id='top3'><center>";
        echo $HPmessage . "<br>" . $MPmessage . "<br>XP: " . $currentXP . "<br>Guild: " . $guildN;
        if ($none != true) {
            echo "<br>Description: " . $guildD . "</center>";
        }
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td colspan='2' id='top3'>";
        
        echo "<table id='inv' border=0 align='left' cellpadding=0 cellspacing=0>";
        echo "<tr>";
        echo "<td id='top3'>";
        echo "Level: " . $level . "<br><br>" . $name . " has " . $credits . " credits!";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        
        echo "<table id='inv' border=0 align='right' cellpadding=0 cellspacing=0>";
        echo "<tr>";
        echo "<td id='top3'>";
        echo "<div align='right'>Galaxy: " . $galaxyN . "<br><br>";
        if ($name != $usr_N_R) {
            echo "<a href='index.php?page=game&gamepage=donC&id=" . $id . "'><b><span style='color:#00ff00'>Donate " . $name . " Credits</span></b></a>";
        }
        echo "</div></td>";
        echo "</tr>";
        echo "</table>";
        echo "<br><br>";
        
        echo "</tr><tr><td colspan='2' id='top3'><center>";
        echo "Strength: " . $str . "<br>";
        echo "Defense: " . $def . "<br>";
        echo "Armor Class: " . $AC . "<br>";
        echo "PVP Score: " . $PVP . "";
        echo "</center></tr>";
        if ($name != $usr_N_R) {
            echo "<tr><td colspan='2' id='top3'>";
            echo "<center><a href='index.php?page=game&gamepage=sendmail&totext=" . $name . "'><b>Click here to send some mail to " . $name . "!</b></a><br><a href='index.php?page=game&gamepage=wh&id=" . $id . "'><b><span style='color:#0000ff'>Click here to go to " . $name . "'s Warehouse!</span></b></a><br><a href='index.php?page=game&gamepage=attack&attackID=" . $id . "'><b><span style='color:#ff0000'>Click here to attack " . $name . "!</span></b></a></center>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        //End Write of Page
        
        
        break;
    case 'game':
        //Check cookie for login...
        if (empty($_COOKIE['va_users']) == true) {
            echo "You are not logged in!<br><a href='index.php?page=login'>Click here to log in!</a>";
        } else {
            $query  = "SELECT * FROM va_users";
            $result = mysql_query($query);
            $num1   = mysql_numrows($result);
            
            for ($i = 0; $i < $num1; $i++) {
                if (mysql_result($result, $i, "user") == $_COOKIE["va_users"]) {
                    $Uid    = mysql_result($result, $i, "id");
                    $access = mysql_result($result, $i, "access");
                    $banTU  = mysql_result($result, $i, "user");
                }
            }
            
            $query  = "SELECT * FROM va_bans";
            $result = mysql_query($query);
            $num1   = mysql_numrows($result);
            
            $banTime = date('ymdHi');
            $banTime = floor($banTime);
            
            for ($i = 0; $i < $num1; $i++) {
                if (mysql_result($result, $i, "playerid") == $Uid) {
                    //User is banned.
                    $banLen = mysql_result($result, $i, "length");
                    $banID  = mysql_result($result, $i, "id");
                }
            }
            $minutes = $banLen - $banTime;
            
            if ($minutes >= 0) {
                $isbanned = true;
                if ($minutes == 0) {
                    $minutesT = "less then a minute.";
                } elseif ($minutes == 1) {
                    $minutesT = $minutes . " minute.";
                } else {
                    $minutesT = $minutes . " minutes.";
                }
                
            } else {
                $query = "DELETE FROM va_bans WHERE id = '" . $banID . "'";
                mysql_query($query) or die($query);
            }
            
            if ($isbanned == true) {
                echo "You have been banned for " . $minutesT . "";
            }
            
            if ($isbanned != true) {
                //RANDOM CREDIT ADDITION!
                
                srand((double) microtime() * 1000000);
                $r1 = rand(0, 100);
                srand((double) microtime() * 1000000);
                $r2 = rand(0, 10);
                srand((double) microtime() * 1000000);
                $r3 = rand(0, 25);
                srand((double) microtime() * 1000000);
                $r4 = rand(0, 8);
                srand((double) microtime() * 1000000);
                $r5 = rand(1, 6);
                
                srand((double) microtime() * 1000000);
                $randomI = rand(1, 30);
                
                srand((double) microtime() * 1000000);
                $credits = rand(1, 8);
                
                srand((double) microtime() * 1000000);
                $nameNumber = rand(1, 10);
                
                srand((double) microtime() * 1000000);
                $itemDrop = rand(1, 4);
                
                
                if ($itemDrop == 2) {
                    
                    $query  = "SELECT * FROM va_items";
                    $result = mysql_query($query);
                    $num1   = mysql_numrows($result);
                    
                    $numI = 0;
                    for ($i = 0; $i < $num1; $i++) {
                        if (mysql_result($result, $i, "id") != "") {
                            $RitemID[$numI]   = mysql_result($result, $i, "id");
                            $RitemN[$numI]    = mysql_result($result, $i, "name");
                            $Rm3Im[$numI]     = mysql_result($result, $i, "image");
                            $RitemRare[$numI] = mysql_result($result, $i, "rareRoll");
                            
                            $numI++;
                            $mNum++;
                        }
                    }
                    
                    $mNum -= 1;
                    srand((double) microtime() * 1000000);
                    $itemRoll = rand(1, $mNum);
                    
                    $itemN2  = $RitemN[$itemRoll];
                    $itemID2 = $RitemID[$itemRoll];
                    $m3Im2   = $Rm3Im[$itemRoll];
                    $rareR2  = $RitemRare[$itemRoll];
                    
                    srand((double) microtime() * 1000000);
                    $RareRoll = rand(1, $rareR2);
                    
                    if ($RareRoll == 1) {
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $id = mysql_result($result, $i, "id");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result, $i, "inv" . $i2) == 0) {
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $itemID2 . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item.");
                                        $message3 = "Wow you found a " . $itemN2 . " off the ground!";
                                        break;
                                    }
                                }
                            }
                        }
                        
                    }
                    
                }
                
                
                switch ($r2) {
                    case 0:
                        $message = "Congratulations! You just found an unclaimed wallet with " . $credits . " Credits in it!";
                        break;
                    case 1:
                        $message = "Wow! A celebrity just walked by and gave you " . $credits . " Credits!";
                        break;
                    case 2:
                        $message = "Hey! You're in luck! You just found a bag with " . $credits . " Credits contained in it!";
                        break;
                    case 3:
                        $message = "Cool! You found " . $credits . " Credits off the ground!";
                        break;
                    case 4:
                        $message = "No way! Someone must've put " . $credits . " Credits in your pocket when you weren't looking!";
                        break;
                    case 5:
                        $message = "You got a tax refund of " . $credits . " Credits... Do you even pay taxes?";
                        break;
                    case 6:
                        $message = "Your friend gave you " . $credits . " Credits for free! You must be proud to have a friend like him....";
                        break;
                    case 7:
                        $message = "How wierd... A beggar came up to you and gave you " . $credits . " Credits... Why would he do that?";
                        break;
                    case 8:
                        $message = "It's your lucky day! You won the lottery and got " . $credits . " Credits!";
                        break;
                    case 9:
                        $message = "Sweet! You found " . $credits . " just floating away in the wind.";
                        break;
                    case 10:
                        $message = "How Strange. A guy named Raygoe came up and gave up " . $credits . " Credits for playing this game. How nice of him!";
                        break;
                }
                
                switch ($nameNumber) {
                    case 1:
                        $name = "Bob";
                        break;
                    case 2:
                        $name = "Jim";
                        break;
                    case 3:
                        $name = "Tyler";
                        break;
                    case 4:
                        $name = "Brandon";
                        break;
                    case 5:
                        $name = "Vader";
                        break;
                    case 6:
                        $name = "Kitty";
                        break;
                    case 7:
                        $name = "Elvis";
                        break;
                    case 8:
                        if ($r2 == 8) {
                            $name = "KyleD";
                        } else {
                            $name = "Dragon";
                        }
                        break;
                    case 9:
                        $name = "John";
                        break;
                    case 10:
                        $name = "Zepheria";
                        break;
                }
                
                switch ($r4) {
                    case 0:
                        $message2 = "ai" . $name . " has challanged you to a duel! I wonder what you did to the poor chap.";
                        break;
                    case 1:
                        $message2 = "Whoa, A duel from ai" . $name . "! Hm, I could've sworn that was the person you bumped into while taking a stroll around the park...";
                        break;
                    case 2:
                        $message2 = "I guess you really made the celebrity, ai" . $name . ", angry.  He must've got mad when you started a rumor about him...";
                        break;
                    case 3:
                        $message2 = "Hey, for some reason ai" . $name . " is calling you a complete coward, you arn't going to take that... Are you?";
                        break;
                    case 4:
                        $message2 = "You may not have much of a choice but even though ai" . $name . " says he hates you, you might be able to get away, he seems a bit... Out of it...";
                        break;
                    case 5:
                        $message2 = "Was it just me, or did that beggar, ai" . $name . ", just tell you he wanted a duel...";
                        break;
                    case 6:
                        $message2 = "That guy, ai " . $name . ", just called you an Idiot! You should teach him a lesson.";
                        break;
                    case 7:
                        $message2 = "ai " . $name . " wants to duel! He seems a bit drunk...";
                        break;
                    case 8:
                        $message2 = "Your friend gotten beat up by ai " . $name . ". You should teach him a lessona about bullying around your friends.";
                        break;
                }
                
                if ($r1 == 8) {
                    $query  = "SELECT * FROM va_users";
                    $result = mysql_query($query);
                    $num1   = mysql_numrows($result);
                    
                    for ($i = 0; $i < $num1; $i++) {
                        if (mysql_result($result, $i, "user") == $_COOKIE["va_users"]) {
                            $CC = mysql_result($result, $i, "currency");
                            $id = mysql_result($result, $i, "id");
                        }
                    }
                    
                    
                    $UpC = $CC + $credits;
                    
                    $query = "UPDATE va_users SET currency='" . $UpC . "' WHERE id='" . $id . "'";
                    mysql_query($query) or die("Unable to update Credits!");
                    
                    echo "<table border=2 cellpadding=0 cellspacing=0 width=300>";
                    echo "<tr>";
                    echo "<td><center><strong>Obtained:  " . $credits . " Credits</strong></center></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td id='top'>";
                    echo "<img src='images/items/credits.png' alt='Credits' style='float:left'>";
                    echo $message;
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                
                if ($r3 == 7) {
                    echo "<table border=2 cellpadding=0 cellspacing=0 width=300>";
                    echo "<tr>";
                    echo "<td><center><strong>A Challange from ai" . $name . "!</strong></center></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td id='top'>";
                    echo "<img src='images/crossedFB.png' alt='Battle!' style='float:left'>";
                    echo $message2;
                    echo "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td id='top'>";
                    echo "<center><a href='index.php?page=game&gamepage=battle&skill=" . $r5 . "&name=" . $name . "'>Click here to battle!</a></center>";
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                
                if (isset($message3)) {
                    echo "<table border=2 cellpadding=0 cellspacing=0 width=300>";
                    echo "<tr>";
                    echo "<td><center><strong>Item Drop!</strong></center></td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td id='top'>";
                    echo "<img src='" . $m3Im2 . "' alt='Item!' style='float:left'>";
                    echo $message3;
                    echo "</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                
                //END RANDOM!
            }
            if ($isbanned != true) {
?>

<center>
<br>
<table border=2 cellpadding="0" cellspacing="0" id="gamet" width=100% bgcolor="#2b2b2b">
<tr>
<td><center>
<?
                writegamemenu();
?>
</center></td>
</tr>
</table>
<?
                $credits = 0;
                $query   = "SELECT * FROM va_users";
                $result  = mysql_query($query);
                $num1    = mysql_numrows($result);
                for ($i = 0; $i < $num1; $i++) {
                    if (mysql_result($result, $i, "user") == $usr_N_R) {
                        $credits = mysql_result($result, $i, "currency");
                    }
                }
                echo "Credits: " . $credits . " <img src='images/credit.png' alt='Credits'><br>";
            }
            if ($isbanned != true) {
                switch ($_GET['gamepage']) {
                    case 'changepass':
                        echo "<center><hr>Change Password:<hr>";
?>
<form action="changepass.php" method="post" name="cp">
Current Password: <input type="password" name="cPass"><br><br>
New Password: <input type="password" name="nPass"><br><br>
<button type="submit">Change Password</button>
</form>
<?
                        break;
                    case 'cpS':
                        echo "<hr><center>Change of Password succeeded!";
                        break;
                    case 'cpF':
                        echo "<hr><center>Change of Password failed!";
                        break;
                    case 'excha':
                        echo "<br>Welcome to the Exchange!";
                        break;
                    case 'stats':
                        echo "<br><a href='index.php?page=game&gamepage=utick'>Click Here to send a User Ticket!</a><br />";
                        echo "<hr>Your stats<hr>";
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $level  = mysql_result($result, $i, "level");
                                $PvP    = mysql_result($result, $i, "pvp");
                                $pk     = mysql_result($result, $i, "pk");
                                $str    = mysql_result($result, $i, "str");
                                $def    = mysql_result($result, $i, "def");
                                $AC     = mysql_result($result, $i, "AC");
                                $points = mysql_result($result, $i, "points");
                            }
                        }
                        
                        $PKtime = date('ymdH');
                        
                        
                        
                        $class   = "";
                        $classID = 0;
                        $query   = "SELECT * FROM va_users";
                        $result  = mysql_query($query);
                        $num1    = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $classID = mysql_result($result, $i, "class");
                            }
                        }
                        $query  = "SELECT * FROM va_classes";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $classID) {
                                $class = mysql_result($result, $i, "name");
                            }
                        }
                        
                        //START ADDITION -- COPY
                        
                        $q1         = "SELECT * FROM va_galaxy";
                        $q2         = "SELECT * FROM va_users";
                        $q3         = "SELECT * FROM va_users WHERE user = '" . $usr_N_R . "'";
                        $result1    = mysql_query($q1);
                        $result2    = mysql_query($q2);
                        $result3    = mysql_query($q3);
                        $num1       = mysql_numrows($result1);
                        $num2       = mysql_numrows($result2);
                        $userID     = 0;
                        $location   = "";
                        $locationID = 0;
                        $owner      = "";
                        $ownerID    = 0;
                        $request    = "";
                        for ($i = 0; $i < $num2; $i++) {
                            $request = mysql_result($result2, $i, "user");
                            if ($request == $usr_N_R) {
                                $userID     = mysql_result($result2, $i, "id");
                                $gID        = mysql_result($result2, $i, "guild");
                                $locationID = mysql_result($result2, $i, "galaxy");
                                $weaponID   = mysql_result($result2, $i, "eqWeapon");
                                $helmID     = mysql_result($result2, $i, "eqHelmet");
                                $armorID    = mysql_result($result2, $i, "eqArmor");
                                $bootsID    = mysql_result($result2, $i, "eqBoots");
                                $glovesID   = mysql_result($result2, $i, "eqGloves");
                                $shieldID   = mysql_result($result2, $i, "eqShield");
                                $mHP        = mysql_result($result2, $i, "maxHP");
                                $mMP        = mysql_result($result2, $i, "maxMP");
                                $xp         = mysql_result($result2, $i, "currentXP");
                                $level      = mysql_result($result2, $i, "level");
                                $batwon     = mysql_result($result2, $i, "BattlesWon");
                                $batlost    = mysql_result($result2, $i, "BattlesLost");
                            }
                        }
                        
                        for ($i = 0; $i < $num1; $i++) {
                            $request = mysql_result($result1, $i, "id");
                            if ($request == $locationID) {
                                $location = mysql_result($result1, $i, "name");
                                $ownerID  = mysql_result($result1, $i, "ownerid");
                            }
                        }
                        
                        for ($i = 0; $i < $num2; $i++) {
                            $request = mysql_result($result2, $i, "id");
                            if ($request == $ownerID) {
                                $owner = mysql_result($result2, $i, "user");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_buffs";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NB     = 0;
                        $NNB    = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "playerid") == $userID) {
                                //Buff Found!
                                $buffD[$NB] = mysql_result($result, $i, "id");
                                $buffN[$NB] = mysql_result($result, $i, "name");
                                $buffI[$NB] = mysql_result($result, $i, "image");
                                $strPlus += mysql_result($result, $i, "str");
                                if (mysql_result($result, $i, "str") < 0) {
                                    $neg[$NB] = true;
                                }
                                $defPlus += mysql_result($result, $i, "def");
                                if (mysql_result($result, $i, "def") < 0) {
                                    $neg[$NB] = true;
                                }
                                $hpPlus += mysql_result($result, $i, "maxHP");
                                if (mysql_result($result, $i, "maxHP") < 0) {
                                    $neg[$NB] = true;
                                }
                                $mpPlus += mysql_result($result, $i, "maxMP");
                                if (mysql_result($result, $i, "maxMP") < 0) {
                                    $neg[$NB] = true;
                                }
                                
                                if ($neg[$NB] == true) {
                                    $NbuffD[$NNB] = mysql_result($result, $i, "id");
                                    $NbuffN[$NNB] = mysql_result($result, $i, "name");
                                    $NbuffI[$NNB] = mysql_result($result, $i, "image");
                                    $NnumberBuffs++;
                                }
                                
                                $NB++;
                                $NNB++;
                                $numberBuffs++;
                            }
                        }
                        
                        $nLvl = $level + 1;
                        $tnl  = ((pow($nLvl, 2)) * 200);
                        $tnl2 = $tnl - $xp;
                        
                        echo "<br />You are level: " . $level . ".<br />";
                        echo "You need " . $tnl2 . " more XP to get to level " . $nLvl . ".<br /><br />";
                        
                        if ($strPlus > 0) {
                            $strText = "(+" . $strPlus . ")";
                        } elseif ($strPlus < 0) {
                            $strText = "(" . $strPlus . ")";
                        }
                        if ($defPlus > 0) {
                            $defText = "(+" . $defPlus . ")";
                        } elseif ($defPlus < 0) {
                            $defText = "(" . $defPlus . ")";
                        }
                        if ($hpPlus > 0) {
                            $hpText = "(+" . $hpPlus . ")";
                        } elseif ($hpPlus < 0) {
                            $hpText = "(" . $hpPlus . ")";
                        }
                        if ($mpPlus > 0) {
                            $mpText = "(+" . $mpPlus . ")";
                        } elseif ($mpPlus < 0) {
                            $mpText = "(" . $mpPlus . ")";
                        }
                        $luck = $_GET['luckV'];
                        if (!isset($_GET['luckV'])) {
                            $luck = 0;
                        }
                        $luckT = abs($luck);
                        
                        if ($points > 0) {
                            echo "Points Left: " . $points . "<br><br>";
                            echo "Strength: " . $str . " " . $strText . ". <a href='index.php?page=game&gamepage=addstat&stat=1'>[+]</a><br>";
                            echo "Defense: " . $def . " " . $defText . ". <a href='index.php?page=game&gamepage=addstat&stat=2'>[+]</a><br>";
                            echo "Max HP: " . $mHP . " " . $hpText . ". <a href='index.php?page=game&gamepage=addstat&stat=3'>[+]</a><br>";
                            echo "Max MP: " . $mMP . " " . $mpText . ". <a href='index.php?page=game&gamepage=addstat&stat=4'>[+]</a><br>";
                            if ($luck < 0) {
                                echo "<center>Luck: <font color='#ff0000'>" . $luckT . "</font></center>";
                            } elseif ($luck > 0) {
                                echo "<center>Luck: <font color='#00ff00'>" . $luckT . "</font></center>";
                            } else {
                                echo "<center>Luck: <font color='#ffffff'>" . $luckT . "</font></center>";
                            }
                            echo "Armor Class: " . $AC . "<br>";
                            echo "Class: " . $class . "<br>";
                        } else {
                            echo "Strength: " . $str . " " . $strText . "<br>";
                            echo "Defense: " . $def . " " . $defText . "<br>";
                            echo "Max HP: " . $mHP . " " . $hpText . "<br>";
                            echo "Max MP: " . $mMP . " " . $mpText . "<br>";
                            if ($luck < 0) {
                                echo "<center>Luck: <font color='#ff0000'>" . $luckT . "</font></center>";
                            } elseif ($luck > 0) {
                                echo "<center>Luck: <font color='#00ff00'>" . $luckT . "</font></center>";
                            } else {
                                echo "<center>Luck: <font color='#ffffff'>" . $luckT . "</font></center>";
                            }
                            echo "Armor Class: " . $AC . "<br>";
                            echo "Class: " . $class . "<br>";
                        }
                        echo "<br>You live in the galaxy: \"" . $location . "\". Which is owned by the user " . $owner . ".<br><br>";
                        
                        if ($numberBuffs > 0) {
                            echo "<hr>Buffs<hr>";
                            for ($i = 0; $i < $numberBuffs; $i++) {
                                if ($buffD[$i] != 0) {
                                    if ($neg[$i] != true) {
                                        if ($bwriterow > 2) {
                                            echo "<table id='inv'>";
                                            echo "<tr>";
                                            echo "<td>";
                                            echo "<a href='index.php?page=game&gamepage=buffinfo&id=" . $buffD[$i] . "'><img src='" . $buffI[$i] . "' border=0></a><br>Buff: " . $buffN[$i];
                                            echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";
                                            echo "<br>";
                                            $hasitems  = true;
                                            $bwriterow = 0;
                                        } else {
                                            echo "<table id='inv'>";
                                            echo "<tr>";
                                            echo "<td>";
                                            echo "<a href='index.php?page=game&gamepage=buffinfo&id=" . $buffD[$i] . "'><img src='" . $buffI[$i] . "' border=0></a><br>Buff: " . $buffN[$i];
                                            echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";
                                            $bwriterow++;
                                        }
                                    }
                                }
                                $wr++;
                            }
                        }
                        
                        if ($NnumberBuffs > 0) {
                            if ($neg == true) {
                                echo "<hr>De-Buffs<hr>";
                                for ($i = 0; $i < $NnumberBuffs; $i++) {
                                    if ($NbuffD[$i] != 0) {
                                        if ($Nbwriterow > 2) {
                                            echo "<table id='inv'>";
                                            echo "<tr>";
                                            echo "<td>";
                                            echo "<a href='index.php?page=game&gamepage=Dbuffinfo&id=" . $NbuffD[$i] . "'><img src='" . $NbuffI[$i] . "' border=0></a><br>De-Buff: " . $NbuffN[$i];
                                            echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";
                                            echo "<br>";
                                            $Nbwriterow = 0;
                                        } else {
                                            echo "<table id='inv'>";
                                            echo "<tr>";
                                            echo "<td>";
                                            echo "<a href='index.php?page=game&gamepage=Dbuffinfo&id=" . $NbuffD[$i] . "'><img src='" . $NbuffI[$i] . "' border=0></a><br>De-Buff: " . $NbuffN[$i];
                                            echo "</td>";
                                            echo "</tr>";
                                            echo "</table>";
                                            $Nbwriterow++;
                                        }
                                    }
                                    $Nwr++;
                                }
                            }
                        }
                        echo "<hr>Battles<hr>";
                        echo "Your Battles Won: " . $batwon . ".<br />";
                        echo "Your Battles Lost: " . $batlost . ".<br /><br />";
                        echo "<hr>Guild<hr>";
                        
                        if ($gID != 0) {
                            echo "<br><a href='index.php?page=game&gamepage=gquitC'>Click Here to Leave your guild!</a>";
                            echo "<br><a href='index.php?page=game&gamepage=guild'>Click Here to go to your Guild Page!</a><br />";
                        } else {
                            echo "<br><a href='index.php?page=game&gamepage=gjoin'>Click Here to join a guild!</a><br /><a href='index.php?page=game&gamepage=addguild'>Click Here to create a guild!</a><br />";
                        }
                        
                        echo "<hr>PvP<hr>";
                        echo "Your PVP Score: " . $PvP . ".<br />";
                        
                        
                        
                        //END ADDITION -- COPY
                        
                        echo "<br><hr>";
                        echo "Inventory<hr><a href='index.php?page=game&gamepage=orgInv'>[Multi-Item Organization]</a><br>";
                        //Wow... I am thinking this is going to be HUGE.
                        
                        /*Ok the way this will work:
                        Ok there will be a loop...
                        like this:
                        mid($inventoryIDs,$itemnum*4,4)
                        ^^ visual basic
                        
                        So itemnum will be a while loop.
                        Er...
                        so it'd be like this...
                        while ($itemnum*4 < strlen($inventoryIDs)
                        */
                        //Introducing Variables
                        $itemnum      = 0;
                        $itemnum2     = 0;
                        $iwriterow    = 0;
                        $itemnuml     = 0;
                        $maxitems     = 240;
                        $iit          = 0;
                        $w            = 0;
                        $image        = "VOID NOT USED!";
                        $desctemp     = "I am not used!";
                        $inventoryIDs = "";
                        $itemid       = array(
                            'set' => 1
                        );
                        $itempic      = array(
                            'set' => 1
                        );
                        $itemname     = array(
                            'set' => 1
                        );
                        $itemdesc     = array(
                            'set' => 1
                        );
                        //MySQL Data...
                        $query        = "SELECT * FROM va_users";
                        $result       = mysql_query($query);
                        $num1         = mysql_numrows($result);
                        
                        //I edited the old code out... I am now using 20 mysql fields...
                        $itemsIn = 0;
                        
                        for ($i = 0; $i < $num1; $i++) {
                            //I2 is the table inv(NUMBER) code
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $itemid[$i2] = mysql_result($result, $i, "inv" . $i2);
                                    
                                    if ($itemid[$i2] != 0) {
                                        $itemsIn++;
                                    }
                                    
                                }
                            }
                            
                        }
                        echo "You have (" . $itemsIn . "/30) Items in your Inventory.<br>";
                        //All ID's collected
                        //Now time to get those ID's info...
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i4 = 0; $i4 < $num1; $i4++) {
                            for ($i5 = 0; $i5 < 30; $i5++) {
                                if (mysql_result($result, $i4, "id") == $itemid[$i5]) {
                                    
                                    $itempic[$i5]  = mysql_result($result, $i4, "image");
                                    $itemname[$i5] = mysql_result($result, $i4, "name");
                                    $itemdesc[$i5] = mysql_result($result, $i4, "description");
                                    $itemAP[$i5]   = mysql_result($result, $i4, "AP");
                                    if (mysql_result($result, $i4, "type") == 1) {
                                        $itemdesc[$i5] .= "<br>Base Damage: " . $itemAP[$i5];
                                    }
                                    if (mysql_result($result, $i4, "type") == 2 or mysql_result($result, $i4, "type") == 3 or mysql_result($result, $i4, "type") == 4 or mysql_result($result, $i4, "type") == 5 or mysql_result($result, $i4, "type") == 10) {
                                        $itemdesc[$i5] .= "<br>Armor Class: " . $itemAP[$i5];
                                    }
                                }
                            }
                            if (mysql_result($result, $i4, "id") == $weaponID) {
                                $eqiW  = mysql_result($result, $i4, "image");
                                $eqW   = mysql_result($result, $i4, "name");
                                $eqWap = mysql_result($result, $i4, "AP");
                            }
                            if (mysql_result($result, $i4, "id") == $shieldID) {
                                $eqiS  = mysql_result($result, $i4, "image");
                                $eqS   = mysql_result($result, $i4, "name");
                                $eqSap = mysql_result($result, $i4, "AP");
                            }
                            if (mysql_result($result, $i4, "id") == $bootsID) {
                                $eqiB  = mysql_result($result, $i4, "image");
                                $eqB   = mysql_result($result, $i4, "name");
                                $eqBap = mysql_result($result, $i4, "AP");
                            }
                            if (mysql_result($result, $i4, "id") == $glovesID) {
                                $eqiG  = mysql_result($result, $i4, "image");
                                $eqG   = mysql_result($result, $i4, "name");
                                $eqGap = mysql_result($result, $i4, "AP");
                            }
                            if (mysql_result($result, $i4, "id") == $helmID) {
                                $eqiH  = mysql_result($result, $i4, "image");
                                $eqH   = mysql_result($result, $i4, "name");
                                $eqHap = mysql_result($result, $i4, "AP");
                            }
                            if (mysql_result($result, $i4, "id") == $armorID) {
                                $eqiA  = mysql_result($result, $i4, "image");
                                $eqA   = mysql_result($result, $i4, "name");
                                $eqAap = mysql_result($result, $i4, "AP");
                            }
                        }
                        
                        for ($i3 = 0; $i3 < 30; $i3++) {
                            
                            
                            
                            //I am now testing the functions of the Write...
                            if ($itemid[$w] != 0) {
                                if ($iwriterow > 2) {
                                    echo "<table id='inv'>";
                                    echo "<tr>";
                                    echo "<td width='358'>";
                                    echo "<a href='index.php?page=game&gamepage=iteminfo&id=" . $w . "'><img src='" . $itempic[$w] . "' border=0></a><br>Item: " . $itemname[$w] . "<br>Description: " . $itemdesc[$w];
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                    echo "<br>";
                                    $hasitems  = true;
                                    $iwriterow = 0;
                                } else {
                                    echo "<table id='inv'>";
                                    echo "<tr>";
                                    echo "<td width='358'>";
                                    echo "<a href='index.php?page=game&gamepage=iteminfo&id=" . $w . "'><img src='" . $itempic[$w] . "' border=0></a><br>Item: " . $itemname[$w] . "<br>Description: " . $itemdesc[$w];
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                    $hasitems = true;
                                    $iwriterow++;
                                }
                            }
                            /*
                            else
                            {
                            echo $itemid[$w] . " = inv" . $w . "<br>";
                            }
                            */
                            $w++;
                            $itemnum++;
                            $itemnuml++;
                        }
                        if ($hasitems != true) {
                            echo "There are no items in your inventory!";
                        }
                        
                        echo "<br><hr>Equipped<hr>";
                        
                        if ($weaponID != 0) {
                            echo "<table id='inv'>";
                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='index.php?page=game&gamepage=unequip&id=0'><img src='" . $eqiW . "' border=0></a><br>Item: " . $eqW . "<br>Base Damage: " . $eqWap . "<br>Equipped: Weapon";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                            $equiped = true;
                        }
                        
                        if ($helmID != 0) {
                            echo "<table id='inv'>";
                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='index.php?page=game&gamepage=unequip&id=1'><img src='" . $eqiH . "' border=0></a><br>Item: " . $eqH . "<br>Armor Class: " . $eqHap . "<br>Equipped: Helmet";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                            $equiped = true;
                        }
                        
                        if ($bootsID != 0) {
                            echo "<table id='inv'>";
                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='index.php?page=game&gamepage=unequip&id=2'><img src='" . $eqiB . "' border=0></a><br>Item: " . $eqB . "<br>Armor Class: " . $eqBap . "<br>Equipped: Boots";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                            $equiped = true;
                        }
                        
                        if ($glovesID != 0) {
                            echo "<table id='inv'>";
                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='index.php?page=game&gamepage=unequip&id=3'><img src='" . $eqiG . "' border=0></a><br>Item: " . $eqG . "<br>Armor Class: " . $eqGap . "<br>Equipped: Gloves";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                            $equiped = true;
                        }
                        
                        if ($armorID != 0) {
                            echo "<table id='inv'>";
                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='index.php?page=game&gamepage=unequip&id=4'><img src='" . $eqiA . "' border=0></a><br>Item: " . $eqA . "<br>Armor Class: " . $eqAap . "<br>Equipped: Armor";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                            $equiped = true;
                        }
                        
                        if ($shieldID != 0) {
                            echo "<table id='inv'>";
                            echo "<tr>";
                            echo "<td>";
                            echo "<a href='index.php?page=game&gamepage=unequip&id=5'><img src='" . $eqiS . "' border=0></a><br>Item: " . $eqS . "<br>Armor Class: " . $eqSap . "<br>Equipped: Shield";
                            echo "</td>";
                            echo "</tr>";
                            echo "</table>";
                            $equiped = true;
                        }
                        
                        if ($equiped != true) {
                            echo "You have nothing equipped.";
                        }
                        
                        echo "<br><hr>";
                        echo "Spells<hr>";
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            //I2 is the table inv(NUMBER) code
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $spell[$i2] = mysql_result($result, $i, "spell" . $i2);
                                    if ($spell[$i2] != "") {
                                        $spellsIn++;
                                    }
                                }
                            }
                        }
                        
                        echo "You have (" . $spellsIn . "/30) Spells in your Spell Book.<br>";
                        
                        $iwriterow = 0;
                        
                        for ($i2 = 0; $i2 < 30; $i2++) {
                            if ($spell[$i2] != "") {
                                $power = spellpower($spell[$i2]);
                                
                                $powerTY = spelltype($spell[$i2]);
                                
                                $powerTYt = spelltypeT($power, $powerTY);
                                
                                $powerD = powerDesc($spell[$i2]);
                                
                                $AP = floor($power * 0.1);
                                
                                if ($AP < 5) {
                                    $AP = $power;
                                }
                                
                                //I am now testing the functions of the Write...
                                if ($iwriterow > 2) {
                                    echo "<table id='inv'>";
                                    echo "<tr>";
                                    echo "<td width='358'>";
                                    if ($powerTY != 0) {
                                        echo "Spell: <a href='index.php?page=game&gamepage=spell&id=" . $i2 . "'>" . $spell[$i2] . "</a><br>Damage: " . $AP;
                                    } else {
                                        echo "Spell: <a href='index.php?page=game&gamepage=spell&id=" . $i2 . "'>" . $spell[$i2] . "</a><br>Healing: " . $AP;
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                    echo "<br>";
                                    $hasspells = true;
                                    $iwriterow = 0;
                                } else {
                                    echo "<table id='inv'>";
                                    echo "<tr>";
                                    echo "<td width='358'>";
                                    if ($powerTY != 0) {
                                        echo "Spell: <a href='index.php?page=game&gamepage=spell&id=" . $i2 . "'>" . $spell[$i2] . "</a><br>Damage: " . $AP;
                                    } else {
                                        echo "Spell: <a href='index.php?page=game&gamepage=spell&id=" . $i2 . "'>" . $spell[$i2] . "</a><br>Healing: " . $AP;
                                    }
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                    $hasspells = true;
                                    $iwriterow++;
                                }
                            }
                            
                            /*
                            else
                            {
                            echo $itemid[$w] . " = inv" . $w . "<br>";
                            }
                            */
                            $itemnum++;
                            $itemnuml++;
                        }
                        if ($hasspells != true) {
                            echo "You dont know any spells!";
                        }
                        
                        break;
                    case 'itemSearch':
?>
<hr>
Item Search
<form action="Isearch.php" method="post">
<input type="text" name="name" /><br />
<button type="submit">Search</button>
</form>
<hr>
<?
                        break;
                    case 'iSearch':
                        if (isset($_GET['name'])) {
                            if (substr_count($_GET['name'], '\'') == 0) {
                                $query  = "SELECT * FROM va_items WHERE name LIKE '" . $_GET['name'] . "%'";
                                $result = mysql_query($query);
                                $num1   = mysql_numrows($result);
                                
                                $NOI = 0;
                                for ($i = 0; $i < $num1; $i++) {
                                    if (mysql_result($result, $i, "name") != "") {
                                        $itemID[$NOI]   = mysql_result($result, $i, "id");
                                        $itemName[$NOI] = mysql_result($result, $i, "name");
                                        $NOI++;
                                        $MI++;
                                    }
                                }
                                
                                srand((double) microtime() * 1000000);
                                $RI = rand(0, ($NOI - 1));
                                
                                $query  = "SELECT * FROM va_shops";
                                $result = mysql_query($query);
                                $num1   = mysql_numrows($result);
                                $w      = 0;
                                
                                for ($i = 0; $i < $num1; $i++) {
                                    for ($i2 = 0; $i2 < 30; $i2++) {
                                        if (mysql_result($result, $i, "inv" . $i2) == $itemID[$RI]) {
                                            $ownerID[$w] = mysql_result($result, $i, "ownerid");
                                            $price[$w]   = mysql_result($result, $i, "price" . $i2);
                                            $w++;
                                            $maxShop++;
                                        }
                                    }
                                }
                                for ($i = 0; $i < $maxShop; $i++) {
                                    if ($ownerID != "") {
                                        if ($price[$i] == 1) {
                                            $list .= "<a href='index.php?page=game&gamepage=wh&id=" . $ownerID[$i] . "'>" . $itemName[$RI] . " for " . $price[$i] . " Credit</a><br>";
                                        } else {
                                            $list .= "<a href='index.php?page=game&gamepage=wh&id=" . $ownerID[$i] . "'>" . $itemName[$RI] . " for " . $price[$i] . " Credits</a><br>";
                                        }
                                    }
                                }
                                if (isset($list)) {
                                    echo "<br>Items<hr>" . $list . "<hr>";
                                } else {
                                    echo "<br>Items<hr>No items found Under the Name: \"" . $itemName[$RI] . "\".<br>Try Again?<hr>";
                                    //echo "<br>Items<hr>No items found Under the Name: \"" .$itemName[$RI]  . "\". (" . $RI . ")<br>Try Again?<hr>";
                                }
                                
                            } else {
                                echo "<hr>SQL INJECTION ALERT!<hr>";
                            }
                            
                        } else {
                            echo "<hr>You need to enter a name!<hr>";
                        }
                        break;
                    case 'pSearch':
                        if (isset($_GET['name'])) {
                            if (substr_count($_GET['name'], '\'') == 0) {
                                $query  = "SELECT * FROM va_users WHERE user LIKE '" . $_GET['name'] . "%'";
                                $result = mysql_query($query);
                                $num1   = mysql_numrows($result);
                                
                                for ($i = 0; $i < $num1; $i++) {
                                    if (mysql_result($result, $i, "user") != "") {
                                        $list .= "<a href='index.php?page=player&id=" . mysql_result($result, $i, "id") . "'>" . mysql_result($result, $i, "user") . "</a><br>";
                                    }
                                }
                                if (isset($list)) {
                                    echo "<br>Users<hr>" . $list . "<hr>";
                                } else {
                                    echo "<br>Users<hr>No users found.<hr>";
                                }
                                
                            } else {
                                echo "<hr>SQL INJECTION ALERT!<hr>";
                            }
                            
                        } else {
                            echo "<hr>You need to enter a name!<hr>";
                        }
                        break;
                    case 'buffinfo':
                        $query  = "SELECT * FROM va_buffs";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NB     = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $_GET['id']) {
                                //Buff Found!
                                $buffD    = mysql_result($result, $i, "id");
                                $buffN    = mysql_result($result, $i, "name");
                                $buffI    = mysql_result($result, $i, "image");
                                $playerID = mysql_result($result, $i, "playerid");
                                $strPlus += mysql_result($result, $i, "str");
                                $defPlus += mysql_result($result, $i, "def");
                                $hpPlus += mysql_result($result, $i, "maxHP");
                                $mpPlus += mysql_result($result, $i, "maxMP");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NB     = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $uID = mysql_result($result, $i, "id");
                            }
                        }
                        
                        if ($uID == $playerID) {
                            echo "<hr><img src='" . $buffI . "'>";
                            echo "<br>Name: " . $buffN;
                            if ($strPlus > 0) {
                                echo "<br>Strength Plus: " . $strPlus;
                            }
                            if ($defPlus > 0) {
                                echo "<br>Defense Plus: " . $defPlus;
                            }
                            if ($hpPlus > 0) {
                                echo "<br>HP Plus: " . $hpPlus;
                            }
                            if ($mpPlus > 0) {
                                echo "<br>MP Plus: " . $mpPlus;
                            }
                            echo "<hr>";
                        } else {
                            echo "<hr>That isn't your buff!<hr>";
                        }
                        
                        break;
                    case 'Dbuffinfo':
                        $query  = "SELECT * FROM va_buffs";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NB     = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $_GET['id']) {
                                //Buff Found!
                                $buffD    = mysql_result($result, $i, "id");
                                $buffN    = mysql_result($result, $i, "name");
                                $buffI    = mysql_result($result, $i, "image");
                                $playerID = mysql_result($result, $i, "playerid");
                                $strPlus += mysql_result($result, $i, "str");
                                $defPlus += mysql_result($result, $i, "def");
                                $hpPlus += mysql_result($result, $i, "maxHP");
                                $mpPlus += mysql_result($result, $i, "maxMP");
                            }
                        }
                        
                        $strPlusABS = abs($strPlus);
                        $defPlusABS = abs($defPlus);
                        $hpPlusABS  = abs($hpPlus);
                        $mpPlusABS  = abs($mpPlus);
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NB     = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $uID = mysql_result($result, $i, "id");
                            }
                        }
                        
                        if ($uID == $playerID) {
                            echo "<hr><img src='" . $buffI . "'>";
                            echo "<br>Name: " . $buffN;
                            if ($strPlus < 0) {
                                echo "<br>Strength Drop: " . $strPlusABS;
                            }
                            if ($defPlus < 0) {
                                echo "<br>Defense Drop: " . $defPlusABS;
                            }
                            if ($hpPlus < 0) {
                                echo "<br>HP Drop: " . $hpPlusABS;
                            }
                            if ($mpPlus < 0) {
                                echo "<br>MP Drop: " . $mpPlusABS;
                            }
                            echo "<hr>";
                        } else {
                            echo "<hr>That isn't your buff!<hr>";
                        }
                        
                        break;
                    case 'spell':
                        $spellID = $_GET['id'];
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $sName = mysql_result($result, $i, "spell" . $spellID);
                                $uID   = mysql_result($result, $i, "id");
                            }
                        }
                        
                        $power = spellpower($sName);
                        
                        $powerTY = spelltype($sName);
                        
                        $powerTYt = spelltypeT($power, $powerTY);
                        
                        $powerD = powerDesc($sName);
                        
                        $AP = floor($power * 0.1);
                        
                        if ($AP < 5) {
                            $AP = $power;
                        }
                        
                        echo "<hr>Spell Actions<hr><strong>" . $sName . "</strong><br><br><a href='index.php?page=game&gamepage=unlearn&id=" . $spellID . "'>Unlearn Spell</a><br><a href='index.php?page=game&gamepage=spellinfo&name=" . $sName . "'>Spell Info</a>";
                        
                        if ($powerTY == 0) {
                            echo "<br><a href='index.php?page=game&gamepage=usespell&id=" . $spellID . "'>Use Spell</a>";
                        }
                        
                        break;
                    case 'guild':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $gID    = mysql_result($result, $i, "guild");
                                $uID    = mysql_result($result, $i, "id");
                                $access = mysql_result($result, $i, "access");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_guilds";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $gID) {
                                $gName   = mysql_result($result, $i, "name");
                                $gPage   = nl2br(mysql_result($result, $i, "page"));
                                $ownerID = mysql_result($result, $i, "ownerid");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "guild") == $gID) {
                                if (mysql_result($result, $i, "id") == $ownerID) {
                                    $members .= "<a href='index.php?page=player&id=" . mysql_result($result, $i, "id") . "'>[" . mysql_result($result, $i, "user") . "] (Guild Owner)</a> ";
                                } else {
                                    $members .= "<a href='index.php?page=player&id=" . mysql_result($result, $i, "id") . "'>[" . mysql_result($result, $i, "user") . "]</a> ";
                                }
                            }
                        }
                        
                        if (isset($_GET['act'])) {
                            switch ($_GET['act']) {
                                case 'view':
                                    if ($access > 1) {
                                        
                                        $query  = "SELECT * FROM va_guilds";
                                        $result = mysql_query($query);
                                        $num1   = mysql_numrows($result);
                                        
                                        for ($i = 0; $i < $num1; $i++) {
                                            if (mysql_result($result, $i, "id") == $_GET['id']) {
                                                $gNameV   = mysql_result($result, $i, "name");
                                                $gPageV   = nl2br(mysql_result($result, $i, "page"));
                                                $ownerIDV = mysql_result($result, $i, "ownerid");
                                            }
                                        }
                                        
                                        $query  = "SELECT * FROM va_users";
                                        $result = mysql_query($query);
                                        $num1   = mysql_numrows($result);
                                        
                                        for ($i = 0; $i < $num1; $i++) {
                                            if (mysql_result($result, $i, "guild") == $_GET['id']) {
                                                if (mysql_result($result, $i, "id") == $ownerIDV) {
                                                    $membersV .= "<a href='index.php?page=player&id=" . mysql_result($result, $i, "id") . "'>[" . mysql_result($result, $i, "user") . "] (Guild Owner)</a> ";
                                                } else {
                                                    $membersV .= "<a href='index.php?page=player&id=" . mysql_result($result, $i, "id") . "'>[" . mysql_result($result, $i, "user") . "]</a> ";
                                                }
                                            }
                                        }
                                        
                                        echo "<hr>Welcome to " . $gNameV . "'s Guild Page!";
                                        if ($uID == $ownerIDV) {
                                            echo "<br>Controls: <a href='index.php?page=game&gamepage=guild&act=changeD'>Change Guild Description</a> &#8226; <a href='index.php?page=game&gamepage=guild&act=changeP'>Change Guild Page Text</a><hr>";
                                            echo "Guild Page<hr>" . $gPageV;
                                        } else {
                                            echo "<hr>Guild Page<hr>" . $gPageV;
                                        }
                                        echo "<hr>Members<hr>" . $membersV;
                                        
                                    }
                                    break;
                                case 'changeD':
?>
Change Guild Description:
<form action="changeGd.php" method="post">
<hr>Description<hr>
<textarea cols="45" rows="15" name="descript"></textarea>
<hr>
<br><button type="submit">Change Guild Description</button>
</form>
<?
                                    break;
                                case 'changeP':
?>
Change Guild Page:
<form action="changeGp.php" method="post">
<hr>Description<hr>
<textarea cols="50" rows="30" name="pageT"></textarea>
<hr>
<br><button type="submit">Change Guild Page</button>
</form>
<?
                                    break;
                            }
                            
                        } else {
                            echo "<hr>Welcome to " . $gName . "'s Guild Page!";
                            if ($uID == $ownerID) {
                                echo "<br>Controls: <a href='index.php?page=game&gamepage=guild&act=changeD'>Change Guild Description</a> &#8226; <a href='index.php?page=game&gamepage=guild&act=changeP'>Change Guild Page Text</a><hr>";
                                echo "Guild Page<hr>" . $gPage;
                            } else {
                                echo "<hr>Guild Page<hr>" . $gPage;
                            }
                            echo "<hr>Members<hr>" . $members;
                        }
                        break;
                    case 'gquitC':
                        echo "<hr>Are you sure you want to leave your guild?<br /><a href='index.php?page=game&gamepage=gquit'>Yes</a> | <a href='index.php?page=game&gamepage=stats'>No</a><hr>";
                        break;
                    case 'usespell':
                        $spellID = $_GET['id'];
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $sName = mysql_result($result, $i, "spell" . $spellID);
                                $uID   = mysql_result($result, $i, "id");
                                $cHP   = mysql_result($result, $i, "currentHP");
                                $cMP   = mysql_result($result, $i, "currentMP");
                            }
                        }
                        
                        $power = spellpower($sName);
                        
                        $powerTY = spelltype($sName);
                        
                        $powerTYt = spelltypeT($power, $powerTY);
                        
                        $powerD = powerDesc($sName);
                        
                        $AP = floor($power * 0.1);
                        
                        if ($AP < 5) {
                            $AP = $power;
                        }
                        
                        $manaREQ = floor($power / 8);
                        
                        if ($manaREQ < 5) {
                            $manaREQ = 5;
                        }
                        if ($power < 1) {
                            $manaREQ = 0;
                        }
                        
                        if ($powerTY == 0) {
                            if ($cMP >= $manaREQ) {
                                $updatedHP = $cHP + $AP;
                                $updatedMP = $cMP - $manaREQ;
                                $query     = "UPDATE va_users SET currentHP='" . $updatedHP . "' WHERE id='" . $uID . "'";
                                mysql_query($query) or die("Unable to use spell!");
                                $query = "UPDATE va_users SET currentMP='" . $updatedMP . "' WHERE id='" . $uID . "'";
                                mysql_query($query) or die("Unable to subtract mana!");
                                echo "<hr>Successfully healed for " . $AP . " Health Points!";
                                echo "<br>Used " . $manaREQ . " Mana Points!<hr>";
                            }
                        } else {
                            echo "<hr>You cannot use this spell!</hr>";
                        }
                        
                        break;
                    case 'unlearn':
                        $spellID = $_GET['id'];
                        echo "<hr>Are you sure you want to unlearn this spell?<br>[<a href='index.php?page=game&gamepage=unlearnC&id=" . $spellID . "'>Yes</a> | <a href='index.php?page=game&gamepage=stats'>No</a>]<hr>";
                        break;
                    case 'unlearnC':
                        $spellID = $_GET['id'];
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $sName = mysql_result($result, $i, "spell" . $spellID);
                                $uID   = mysql_result($result, $i, "id");
                            }
                        }
                        
                        $query = "UPDATE va_users SET spell" . $spellID . "='' WHERE id='" . $uID . "'";
                        mysql_query($query) or die("Unable to unlearn spell!");
                        echo "<hr>Succesfully forgot spell: " . $sName . "!<hr>";
                        break;
                    case 'gquit':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $id    = mysql_result($result, $i, "id");
                                $guild = mysql_result($result, $i, "guild");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_guilds";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $guild) {
                                $ownerID = mysql_result($result, $i, "ownerid");
                            }
                        }
                        
                        if ($id == $ownerID) {
                            $query = "DELETE FROM va_guilds WHERE id = '" . $guild . "'";
                            mysql_query($query) or die("!!!");
                        }
                        
                        $query = "UPDATE va_users SET guild='0' WHERE id='" . $id . "'";
                        mysql_query($query) or die("Unable to change guild!");
                        
                        echo "<hr>You left your guild.<hr>";
                        
                        break;
                    case 'unequip':
                        $EQid   = $_GET['id'];
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_items";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $id       = mysql_result($result, $i, "id");
                                $AC       = mysql_result($result, $i, "AC");
                                $weaponID = mysql_result($result, $i, "eqWeapon");
                                $helmID   = mysql_result($result, $i, "eqHelmet");
                                $armorID  = mysql_result($result, $i, "eqArmor");
                                $bootsID  = mysql_result($result, $i, "eqBoots");
                                $glovesID = mysql_result($result, $i, "eqGloves");
                                $shieldID = mysql_result($result, $i, "eqShield");
                                
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $invID[$i2] = mysql_result($result, $i, "inv" . $i2);
                                }
                                
                            }
                        }
                        
                        switch ($EQid) {
                            case 0:
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if ($invID[$i2] == 0) {
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $weaponID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET eqWeapon='0' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract equipID!");
                                        echo "<hr>Successfully Unequipped Weapon.";
                                        $suc = true;
                                        break;
                                    }
                                }
                                break;
                            case 1:
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if ($invID[$i2] == 0) {
                                        
                                        for ($i3 = 0; $i3 < $num2; $i3++) {
                                            if (mysql_result($result2, $i3, "id") == $helmID) {
                                                $AP = mysql_result($result2, $i3, "AP");
                                            }
                                        }
                                        
                                        $updatedAC = $AC - $AP;
                                        
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $helmID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET eqHelmet='0' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract equipID!");
                                        
                                        $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to edit AC!");
                                        echo "<hr>Successfully Unequipped Helmet.";
                                        $suc = true;
                                        break;
                                    }
                                }
                                break;
                            case 2:
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if ($invID[$i2] == 0) {
                                        
                                        for ($i3 = 0; $i3 < $num2; $i3++) {
                                            if (mysql_result($result2, $i3, "id") == $bootsID) {
                                                $AP = mysql_result($result2, $i3, "AP");
                                            }
                                        }
                                        
                                        $updatedAC = $AC - $AP;
                                        
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $bootsID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET eqBoots='0' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract equipID!");
                                        
                                        $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to edit AC!");
                                        echo "<hr>Successfully Unequipped Boots.";
                                        $suc = true;
                                        break;
                                    }
                                }
                                break;
                            case 3:
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if ($invID[$i2] == 0) {
                                        
                                        for ($i3 = 0; $i3 < $num2; $i3++) {
                                            if (mysql_result($result2, $i3, "id") == $glovesID) {
                                                $AP = mysql_result($result2, $i3, "AP");
                                            }
                                        }
                                        
                                        $updatedAC = $AC - $AP;
                                        
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $glovesID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET eqGloves='0' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract equipID!");
                                        
                                        $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to edit AC!");
                                        echo "<hr>Successfully Unequipped Gloves.";
                                        $suc = true;
                                        break;
                                    }
                                }
                                break;
                            case 4:
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if ($invID[$i2] == 0) {
                                        
                                        for ($i3 = 0; $i3 < $num2; $i3++) {
                                            if (mysql_result($result2, $i3, "id") == $armorID) {
                                                $AP = mysql_result($result2, $i3, "AP");
                                            }
                                        }
                                        
                                        $updatedAC = $AC - $AP;
                                        
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $armorID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET eqArmor='0' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract equipID!");
                                        
                                        $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to edit AC!");
                                        echo "<hr>Successfully Unequipped Armor.";
                                        $suc = true;
                                        break;
                                    }
                                }
                                break;
                            case 5:
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if ($invID[$i2] == 0) {
                                        
                                        for ($i3 = 0; $i3 < $num2; $i3++) {
                                            if (mysql_result($result2, $i3, "id") == $shieldID) {
                                                $AP = mysql_result($result2, $i3, "AP");
                                            }
                                        }
                                        
                                        $updatedAC = $AC - $AP;
                                        
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $shieldID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET eqShield='0' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract equipID!");
                                        
                                        $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to edit AC!");
                                        echo "<hr>Successfully Unequipped Shield.";
                                        $suc = true;
                                        break;
                                    }
                                }
                                break;
                            default:
                                echo "<hr>Failure in finding an equipping item.";
                                break;
                        }
                        
                        if ($suc != true) {
                            echo "<hr>Failure in unequipping item.";
                        }
                        
                        break;
                    case 'addstat':
                        if (isset($_GET['stat'])) {
                            $query  = "SELECT * FROM va_users";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "user") == $usr_N_R) {
                                    $points = mysql_result($result, $i, "points");
                                    $str    = mysql_result($result, $i, "str");
                                    $def    = mysql_result($result, $i, "def");
                                    $id     = mysql_result($result, $i, "id");
                                    $mHP    = mysql_result($result, $i, "maxHP");
                                    $mMP    = mysql_result($result, $i, "maxMP");
                                }
                            }
                            
                            switch ($_GET['stat']) {
                                case 1:
                                    $updatedP = $str + 1;
                                    $p2       = $points - 1;
                                    
                                    if ($points > 0) {
                                        $query = "UPDATE va_users SET str='" . $updatedP . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add Points!");
                                        
                                        $query = "UPDATE va_users SET points='" . $p2 . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract Points!");
                                        echo "<hr>Added Point to Strength.";
                                    } else {
                                        echo "Not enough Points.";
                                    }
                                    break;
                                case 2:
                                    $updatedP = $def + 1;
                                    $p2       = $points - 1;
                                    
                                    if ($points > 0) {
                                        $query = "UPDATE va_users SET def='" . $updatedP . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add Points!");
                                        
                                        $query = "UPDATE va_users SET points='" . $p2 . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract Points!");
                                        echo "<hr>Added Point to Defense.";
                                    } else {
                                        echo "Not enough Points.";
                                    }
                                    break;
                                case 3:
                                    $updatedP = $mHP + 1;
                                    $p2       = $points - 1;
                                    
                                    if ($points > 0) {
                                        $query = "UPDATE va_users SET maxHP='" . $updatedP . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add Points!");
                                        
                                        $query = "UPDATE va_users SET points='" . $p2 . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract Points!");
                                        echo "<hr>Added Point to Max Health Points.";
                                    } else {
                                        echo "Not enough Points.";
                                    }
                                    break;
                                case 4:
                                    $updatedP = $mMP + 1;
                                    $p2       = $points - 1;
                                    
                                    if ($points > 0) {
                                        $query = "UPDATE va_users SET maxMP='" . $updatedP . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add Points!");
                                        
                                        $query = "UPDATE va_users SET points='" . $p2 . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract Points!");
                                        echo "<hr>Added Point to Max Magic Points.";
                                    } else {
                                        echo "Not enough Points.";
                                    }
                                    break;
                                default:
                                    echo "Unknown Stat.";
                                    break;
                            }
                        } else {
                            echo "Unknown Stat.";
                        }
                        break;
                    case 'orgInv':
                        $query  = "SELECT * FROM va_shops";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $query3  = "SELECT * FROM va_items";
                        $result3 = mysql_query($query3);
                        $num3    = mysql_numrows($result3);
                        
                        
                        $w = 0;
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result2, $i, "inv" . $i2) != 0) {
                                        $itemID[$w] = mysql_result($result2, $i, "inv" . $i2);
                                        $invID[$w]  = $i2;
                                        $w++;
                                        $maxI++;
                                    }
                                }
                            }
                        }
                        
                        for ($i = 0; $i < $num3; $i++) {
                            for ($i2 = 0; $i2 < $maxI; $i2++) {
                                
                                if (mysql_result($result3, $i, "id") == $itemID[$i2]) {
                                    $itemN[$i2] = mysql_result($result3, $i, "name");
                                    $itemI[$i2] = mysql_result($result3, $i, "image");
                                    $itemT[$i2] = mysql_result($result3, $i, "type");
                                }
                                
                            }
                        }
?>
<hr>
Organize Inventory
<hr>
<form action="edituserINV.php" method="post">
<table border="0" width="100%">
<tr>
<td width="25%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Image</font></small></td>
<td width="25%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Name</font></small></td>
<td width="50%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">What to do</font></small></td>
</tr>
<?
                        for ($i = 0; $i < $maxI; $i++) {
                            if ($color == "#636363") {
                                $color = "#808080";
                            } else {
                                $color = "#636363";
                            }
                            echo "<tr><td bgcolor=\"$color\"><center><small>";
                            echo "<font face=\"Verdana\"><img src='" . $itemI[$i] . "' border=2></font></small></center></td>";
                            echo "<td bgcolor=\"$color\"><center><small>";
                            echo "<font face=\"Verdana\">" . $itemN[$i] . "</font></small></center></td>";
                            echo "<td bgcolor=\"$color\"><center><small>";
                            echo "<font face=\"Verdana\">";
                            echo "<input type='hidden' name='invID" . $i . "' value='" . $invID[$i] . "'>";
                            echo "<input type='hidden' name='itemID" . $i . "' value='" . $itemID[$i] . "'>";
                            echo "<input type='hidden' name='iT" . $i . "' value='" . $itemT[$i] . "'>";
                            echo "<input type='radio' name='wtd" . $i . "' value='toss'> Throw Away";
                            echo "<input type='radio' name='wtd" . $i . "' value='factory'> Add to Factory";
                            echo "<input type='radio' name='wtd" . $i . "' value='bank'> Add to Bank";
                            if ($itemT[$i] < 1 or $itemT[$i] > 5) {
                                if ($itemT[$i] != 9) {
                                    echo "<input type='radio' name='wtd" . $i . "' value='use'> Use";
                                } else {
                                    echo "<input type='radio' name='wtd" . $i . "' value='Use' disabled> Use";
                                }
                            } else {
                                echo "<input type='radio' name='wtd" . $i . "' value='Use' disabled> Use";
                            }
                            echo "<input type='radio' name='wtd" . $i . "' value='nothing' checked> Do Nothing";
                            echo "</font></small></center></td></tr>";
                        }
                        if ($color == "#636363") {
                            $color = "#808080";
                        } else {
                            $color = "#636363";
                        }
                        echo "</tr><tr><td colspan=\"3\" bgcolor=\"$color\">";
?><center>
<button type="submit">Edit Inventory</button>
</center>
</td></tr>
</table>
</form>

<?
                        break;
                    case 'spellinfo':
                        $sName = $_GET['name'];
                        
                        $power   = 0;
                        $powerTY = 0;
                        
                        $power = spellpower($sName);
                        
                        $powerTY = spelltype($sName);
                        
                        $powerTYt = spelltypeT($power, $powerTY);
                        
                        $powerD = powerDesc($power);
                        
                        $manaREQ = floor($power / 8);
                        
                        if ($manaREQ < 5) {
                            $manaREQ = 5;
                        }
                        if ($power < 1) {
                            $manaREQ = 0;
                        }
                        
                        $creditsREQ = pow(($power * 6), 2);
                        
                        echo "<hr>You utter the magical word, " . $sName . ", and it is " . $powerD . " (Power: " . $power . "). It's purpose is " . $powerTYt;
                        echo "<br>To learn this spell it would require: " . $creditsREQ . " Credits to buy.<br>Also though you need " . $manaREQ . " mana to cast it.";
                        echo "<br><a href='index.php?page=game&gamepage=buyspell&spellname=" . $sName . "'>Click here to learn this spell!</a>";
                        if ($powerTY == 5) {
                            echo "<br>This also requires knowledge from the \"Tome of the Undead\" to use!";
                        }
                        
                        break;
                    case 'buyspell':
                        $sName = $_GET['spellname'];
                        
                        $power   = 0;
                        $powerTY = 0;
                        
                        $power = spellpower($sName);
                        
                        $powerTY = spelltype($sName);
                        
                        $powerTYt = spelltypeT($power, $powerTY);
                        
                        $powerD = powerDesc($power);
                        
                        if ($manaREQ < 5) {
                            $manaREQ = 5;
                        }
                        if ($power < 1) {
                            $manaREQ = 0;
                        }
                        
                        $creditsREQ = pow(($power * 6), 2);
                        
                        //User Stat Check.
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        //I edited the old code out... I am now using 20 mysql fields...
                        
                        for ($i = 0; $i < $num1; $i++) {
                            //I2 is the table inv(NUMBER) code
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $uID     = mysql_result($result, $i, "id");
                                $credits = mysql_result($result, $i, "currency");
                                $mana    = mysql_result($result, $i, "maxMP");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $spell[$i2] = mysql_result($result, $i, "spell" . $i2);
                                }
                            }
                        }
                        
                        for ($i2 = 0; $i2 < 30; $i2++) {
                            if ($spell[$i2] == "") {
                                $spellID = $i2;
                                $a1      = true;
                                break;
                            }
                        }
                        
                        if ($credits >= $creditsREQ) {
                            $a2 = true;
                        }
                        
                        if ($mana >= $manaREQ) {
                            $a3 = true;
                        }
                        
                        if ($powerTY != 5) {
                            $a4 = true;
                        }
                        
                        if ($a1 == true and $a2 == true and $a3 == true and $a4 == true) {
                            $able = true;
                        }
                        
                        if ($able == true) {
                            $updatedC = $credits - $creditsREQ;
                            $query    = "UPDATE va_users SET spell" . $spellID . "='" . $sName . "' WHERE id='" . $uID . "'";
                            mysql_query($query) or die("Unable to learn spell!");
                            $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $uID . "'";
                            mysql_query($query) or die("Unable to learn spell!");
                            echo "<hr>You have successfully learned this spell!<hr>";
                        } else {
                            echo "<hr>You cannot learn this spell!<hr>";
                        }
                        
                        break;
                    case 'utick':
?>
<hr />
<form action="userticket.php" method="post">
Text<hr><textarea name="text" cols=60 rows=15></textarea><hr>
<br />
<button type="submit">Submit User Ticket</button>
</form>
<hr />
<?
                        break;
                    case 'crea':
                        echo "<hr>The ticket has been created, we will respond A.S.A.P.<hr>";
                        break;
                    case 'guS':
                        echo "<hr>Creation of guild was a success!<hr>";
                        break;
                    case 'guF':
                        echo "<hr>Creation of guild failed!<br />
If you have 1000 credits and bought a guild, then it may be a bug. Check your guild page.<hr>";
                        break;
                    case 'gjoin':
                        
?>
<hr />
<form action="joinguild.php" method="post">
Guild Name: <input type='text' name='name' /><br />
Guild Pass: <input type='password' name='pass' /><br />
<button type="submit">Join Guild</button>
</form>
<hr />
<?
                        
                        break;
                    case 'joinF':
                        echo "<hr>Joining that guild failed.<hr>";
                        break;
                        break;
                    case 'joinS':
                        echo "<hr>Successfully joined the guild!<hr>";
                        break;
                    case 'addguild':
                        
?>
<hr />
Creation of a guild costs 1,000 Credits.<br />
<br />
<form action="addguild.php" method="post">
Guild Name: <input type='text' name='name' /><br />
Guild Pass: <input type='password' name='pass' /><br />
<br />
Guild Description<hr><textarea name="describe" cols="65" rows="15"></textarea><hr>
<br />
<br />
<button type="submit">Create Guild</button>
</form>
<hr />
<?
                        
                        break;
                    case 'library':
                        if (isset($_GET['wtd'])) {
                            switch ($_GET['wtd']) {
                                case 'read':
                                    $Bid = $_GET['id'];
                                    
                                    $query  = "SELECT * FROM va_books";
                                    $result = mysql_query($query);
                                    $num1   = mysql_numrows($result);
                                    
                                    for ($i = 0; $i < $num1; $i++) {
                                        if (mysql_result($result, $i, "id") == $Bid) {
                                            $name        = mysql_result($result, $i, "name");
                                            $author      = mysql_result($result, $i, "author");
                                            $description = mysql_result($result, $i, "description");
                                            $text        = mysql_result($result, $i, "text");
                                        }
                                    }
?>
<table border="0" width="100%">
<tr>
<td colspan="2" bgcolor="#a16410"><p align="center"><strong><font face="Verdana">
Name
</font></strong></td>
</tr>
<tr>
<td colspan="2" bgcolor="#636363"><p align="center"><small><font face="Verdana">
<?
                                    echo $name;
?>
</font></small></td>
</tr>
<tr>
<td colspan="1" width="30%" bgcolor="#a16410"><p align="center"><strong><font face="Verdana">
Description
</font></strong></td>
<td colspan="1" width="70%" bgcolor="#a16410"><p align="center"><strong><font face="Verdana">
Text
</font></strong></td>
</tr>
<tr>
<td colspan="1" width="30%" bgcolor="#636363"><p align="center"><small><font face="Verdana">
<?
                                    echo $description;
?>
</font></small></td>
<td colspan="1" width="70%" bgcolor="#636363"><p align="center"><small><font face="Verdana">
<?
                                    echo $text;
?>
</font></small></td>
</tr>
</table>
<?
                                    break;
                                case 1:
                                    $query   = "SELECT * FROM va_books";
                                    $result  = mysql_query($query);
                                    $num1    = mysql_numrows($result);
                                    $query2  = "SELECT level FROM va_users WHERE user='" . $usr_N_R . "'";
                                    $result2 = mysql_query($query2);
                                    $Ulevel  = mysql_result($result2, "level");
                                    
                                    
                                    for ($i = 0; $i < $num1; $i++) {
                                        if ($color == "#636363") {
                                            $color = "#000000";
                                        } else {
                                            $color = "#636363";
                                        }
                                        $Bid         = mysql_result($result, $i, "id");
                                        $name        = mysql_result($result, $i, "name");
                                        $author      = mysql_result($result, $i, "author");
                                        $description = mysql_result($result, $i, "description");
                                        $text        = mysql_result($result, $i, "text");
                                        $Blevel      = mysql_result($result, $i, "level");
                                        $textW .= "<tr><td bgcolor=\"$color\"><center><small>";
                                        $textW .= "<font face=\"Verdana\"><a href='index.php?page=game&gamepage=library&wtd=read&id=" . $Bid . "'>" . $name . "</a></font></small></center></td>";
                                        $textW .= "<td bgcolor=\"$color\"><center><small>";
                                        if ($Ulevel >= $Blevel) {
                                            $textW .= "<font face=\"Verdana\"><a href='index.php?page=game&gamepage=library&wtd=read&id=" . $Bid . "'>" . $name . "</a></font></small></center></td>";
                                        } else {
                                            $textW .= "<font face=\"Verdana\">Book Unavailable</font></small></center></td>";
                                        }
                                        $textW .= "<td bgcolor=\"$color\"><center><small>";
                                        $textW .= "<font face=\"Verdana\">" . $description . "</font></small></center></td></tr>";
                                        
                                        $hasbooks = true;
                                    }
                                    if ($hasbooks != true) {
                                        echo "<hr>Unfortunatly there are no books yet...";
                                    } else {
?>
<hr>
List of Books
<hr>
<table border="0" width="100%">
<tr>
<td width="25%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Book Name</font></small></td>
<td width="15%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Author</font></small></td>
<td width="60%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Description</font></small></td>
</tr>
<?
                                        echo $textW;
                                        
?>
</tr></table>
<?
                                    }
                                    break;
                                case 2:
                                    echo "<hr>Enter Spell Below:<br>";
?>
<form action="spellinfo.php" method="post">
<input type="text" name="spellname"><br><br><button type=submit>Get Spell Info</button>
</form>
<?
                                    echo "<hr>";
                                    break;
                            }
                        } else {
                            echo "<hr>Welcome to The Galactic Library!<br>Please, what are you looking for?<br><a href='index.php?page=game&gamepage=library&wtd=1'>Books</a><br><a href='index.php?page=game&gamepage=library&wtd=2'>Learn Spells</a><hr>";
                        }
                        break;
                    case 'donitem':
?>
<hr />
<form action="donitem.php" method="post">
User to Donate: <input type="text" name="user" /><br />
<input type="hidden" name="id" <?
                        echo "value='" . $_GET['id'] . "'";
?> /><br />
Message<hr />
<textarea cols="65" rows="15" name="message"></textarea>
<hr />
<button type="submit">Donate Item</button>
</form>
<hr />
<?
                        break;
                    case 'npcShops':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "usertype") == 1) {
                                $shopid = mysql_result($result, $i, "id");
                                $user   = mysql_result($result, $i, "user");
                                $numberOS++;
                                
                                $shopECHO .= "<br><a href='index.php?page=game&gamepage=wh&id=" . $shopid . "'>" . $user . "</a>";
                            }
                        }
                        echo "<hr><strong><center>NPC Shops</center></strong>";
                        if ($numberOS > 0) {
                            echo $shopECHO . "<hr>";
                        } else {
                            echo "<br>There are no NPC Shops...<hr>";
                        }
                        
                        break;
                    case 'count':
                        $startNum = $_GET['start'];
                        $toNum    = $_GET['to'];
                        $changer  = 0;
                        
                        for ($x = $startNum; $x <= $toNum; $x++) {
                            echo $x . " ";
                            
                            
                            if ($changer == 12) {
                                echo "<br>";
                                $changer = 0;
                            }
                            
                            
                            $changer++;
                        }
                        
                        break;
                    case 'useitem':
                        $invID = $_GET['id'];
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $id        = mysql_result($result, $i, "id");
                                $AC        = mysql_result($result, $i, "AC");
                                $itemID    = mysql_result($result, $i, "inv" . $invID);
                                $currentHP = mysql_result($result, $i, "currentHP");
                                $currentMP = mysql_result($result, $i, "currentMP");
                                $weaponID  = mysql_result($result, $i, "eqWeapon");
                                $shieldID  = mysql_result($result, $i, "eqShield");
                                $helmetID  = mysql_result($result, $i, "eqHelmet");
                                $armorID   = mysql_result($result, $i, "eqArmor");
                                $glovesID  = mysql_result($result, $i, "eqGloves");
                                $bootsID   = mysql_result($result, $i, "eqBoots");
                            }
                        }
                        
                        if ($itemID == 0) {
                            die("<hr>Unable to find item...");
                        }
                        
                        $query  = "SELECT * FROM va_buffs";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $NB = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "playerid") == $id) {
                                //Buff Found!
                                $buffD[$NB] = mysql_result($result, $i, "id");
                                $buffN[$NB] = mysql_result($result, $i, "name");
                                
                                $NB++;
                                $NoB++;
                            }
                        }
                        
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $itemID) {
                                $itemname = mysql_result($result, $i, "name");
                                $type     = mysql_result($result, $i, "type");
                                $AP       = mysql_result($result, $i, "AP");
                                $isbuffed = mysql_result($result, $i, "isbuffed");
                                $bImage   = mysql_result($result, $i, "buffimage");
                                $bName    = mysql_result($result, $i, "buffname");
                                $battlesL = mysql_result($result, $i, "battlesleft");
                                $strPlus  = mysql_result($result, $i, "str");
                                $defPlus  = mysql_result($result, $i, "def");
                                $hpPlus   = mysql_result($result, $i, "maxHP");
                                $mpPlus   = mysql_result($result, $i, "maxMP");
                            }
                        }
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $weaponID) {
                                $wName = mysql_result($result, $i, "name");
                            }
                            if (mysql_result($result, $i, "id") == $shieldID) {
                                $sName = mysql_result($result, $i, "name");
                            }
                            if (mysql_result($result, $i, "id") == $helmetID) {
                                $hName = mysql_result($result, $i, "name");
                            }
                            if (mysql_result($result, $i, "id") == $armorID) {
                                $aName = mysql_result($result, $i, "name");
                            }
                            if (mysql_result($result, $i, "id") == $glovesID) {
                                $gName = mysql_result($result, $i, "name");
                            }
                            if (mysql_result($result, $i, "id") == $bootsID) {
                                $bName = mysql_result($result, $i, "name");
                            }
                        }
                        
                        switch ($type) {
                            case 0:
                                //HP Potion
                                $query = "UPDATE va_users SET currentHP='" . ($currentHP + $AP) . "' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to use item.");
                                
                                $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to delete item.");
                                echo "<hr>You gain " . $AP . " HP!";
                                break;
                            case 1:
                                //Weapon
                                if ($weaponID == 0) {
                                    $query = "UPDATE va_users SET eqWeapon='" . $itemID . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to use item.");
                                    
                                    $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to delete item.");
                                    echo "<hr>You wield a " . $itemname . "!";
                                } else {
                                    echo "<hr>You already wield a " . $wName . "!";
                                }
                                break;
                            case 2:
                                //Shield
                                if ($shieldID == 0) {
                                    $updatedAC = $AC + $AP;
                                    
                                    $query = "UPDATE va_users SET eqShield='" . $itemID . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to use item.");
                                    
                                    $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to delete item.");
                                    
                                    $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to update AC.");
                                    echo "<hr>You wield a " . $itemname . "!";
                                } else {
                                    echo "<hr>You are already wielding a " . $sName . "!";
                                }
                                break;
                            case 3:
                                //Helmet
                                if ($helmetID == 0) {
                                    $updatedAC = $AC + $AP;
                                    
                                    $query = "UPDATE va_users SET eqHelmet='" . $itemID . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to use item.");
                                    
                                    $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to delete item.");
                                    
                                    $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to update AC.");
                                    echo "<hr>You wear a " . $itemname . "!";
                                } else {
                                    echo "<hr>You are already wearing a " . $hName . "!";
                                }
                                break;
                            case 4:
                                //Armor
                                if ($armorID == 0) {
                                    $updatedAC = $AC + $AP;
                                    
                                    $query = "UPDATE va_users SET eqArmor='" . $itemID . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to use item.");
                                    
                                    $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to delete item.");
                                    
                                    $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to update AC.");
                                    echo "<hr>You wear a " . $itemname . "!";
                                } else {
                                    echo "<hr>You are already wearing a " . $aName . "!";
                                }
                                break;
                            case 5:
                                //Gloves
                                if ($glovesID == 0) {
                                    $updatedAC = $AC + $AP;
                                    
                                    $query = "UPDATE va_users SET eqGloves='" . $itemID . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to use item.");
                                    
                                    $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to delete item.");
                                    
                                    $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to update AC.");
                                    echo "<hr>You wear a " . $itemname . "!";
                                } else {
                                    echo "<hr>You are already wearing a " . $gName . "!";
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
                                $query = "UPDATE va_users SET currentMP='" . ($currentMP + $AP) . "' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to use item.");
                                
                                $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to delete item.");
                                echo "<hr>You gain " . $AP . " MP!";
                                break;
                            case 8:
                                //Buff Item
                                echo "<hr>";
                                $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to delete item.");
                                break;
                            case 9:
                                //Null item
                                echo "<hr>You cannot use this item!<hr>";
                                break;
                            case 10:
                                //Boots
                                if ($bootsID == 0) {
                                    $updatedAC = $AC + $AP;
                                    
                                    $query = "UPDATE va_users SET eqBoots='" . $itemID . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to use item.");
                                    
                                    $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to delete item.");
                                    
                                    $query = "UPDATE va_users SET AC='" . $updatedAC . "' WHERE id='" . $id . "'";
                                    mysql_query($query) or die("Unable to update AC.");
                                    echo "<hr>You wear a " . $itemname . "!";
                                } else {
                                    echo "<hr>You are already wearing a " . $bName . "!";
                                }
                                break;
                            default:
                                echo "<hr>Error... Unknown Item...<hr>";
                                break;
                        }
                        
                        for ($i = 0; $i < $NoB; $i++) {
                            if ($buffN[$i] == $bName) {
                                $cantBuff = true;
                            }
                        }
                        
                        if ($isbuffed == 1 and $cantBuff != true) {
                            //Add Buff
                            $query = "INSERT INTO va_buffs VALUES ('','" . $bName . "','" . $bImage . "','" . $id . "','" . $battlesL . "','" . $strPlus . "','" . $defPlus . "','" . $hpPlus . "','" . $mpPlus . "')";
                            mysql_query($query) or die("Unable to create buff!");
                            
                            echo "<br>The Buff " . $bName . " was aquired, it has " . $battlesL . " battles until it expires!<hr>";
                        }
                        
                        break;
                    case 'dCalc':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $level    = mysql_result($result, $i, "level");
                                $str      = mysql_result($result, $i, "str");
                                $eqWeapon = mysql_result($result, $i, "eqWeapon");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $eqWeapon) {
                                $bwd = mysql_result($result, $i, "AP");
                            }
                        }
                        $damage = damageCalc($level, $str, $bwd);
                        
                        echo "<hr />Damage: " . $damage . ".";
                        break;
                    case 'writebook':
?>
<form action="addbook.php" method="post">
Book Name: <input type="text" name="bName"><br>
Book Author: <input type="text" name="bAuthor"><br>
Book Level:<input type="text" name="blevel"><br>
Book Description: <input type="text" name="bDesc"><br>
<br />
Book Text<hr><textarea name="bText" cols="65" rows="15"></textarea><hr>
<br />
Auth. Pass: <input type="password" name="bPass"><br><br>
<button type="submit">Write Book</button>
</form>
<?
                        break;
                    case 'GFAW':
                        $itemID = $_GET['id'];
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $itemID) {
                                $itemN = mysql_result($result, $i, "name");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $id = mysql_result($result, $i, "id");
                                
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result, $i, "inv" . $i2) == 0) {
                                        $query = "UPDATE va_users SET inv" . $i2 . "='" . $itemID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item.");
                                        $statusM = "Gave " . $usr_N_R . " (id: " . $id . ") a(n) " . $itemN . " (id: " . $itemID . ").";
                                        break;
                                    }
                                }
                                
                            }
                        }
                        if (isset($statusM) == false) {
                            $statusM = "Could not give " . $usr_N_R . " (id: " . $id . ") a(n) " . $itemN . " (id: " . $itemID . ").";
                            echo $statusM;
                        } else {
                            echo $statusM;
                        }
                        break;
                    case 'gCP':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $access = mysql_result($result, $i, "access");
                                $uID    = mysql_result($result, $i, "id");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_galaxy";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "ownerid") == $uID) {
                                $gName = mysql_result($result, $i, "name");
                                $gOwns = true;
                                $gID   = mysql_result($result, $i, "id");
                                $tog   = mysql_result($result, $i, "gov");
                                $gReg  = mysql_result($result, $i, "register");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "galaxy") == $gID) {
                                $gMembers .= "<a href='index.php?page=player&id=" . mysql_result($result, $i, "id") . "'>[" . mysql_result($result, $i, "user") . "]</a> ";
                                $gNum++;
                                
                            }
                        }
                        
                        switch ($tog) {
                            case 0:
                                $togT = "Empire";
                                break;
                            case 1:
                                $togT = "Democratic-Republic";
                                break;
                            case 2:
                                $togT = "Republic";
                                break;
                            case 3:
                                $togT = "Theocracy";
                                break;
                            case 4:
                                $togT = "Monarchy";
                                break;
                            case 5:
                                $togT = "Communism";
                                break;
                            case 6:
                                $togT = "Socialism";
                                break;
                            case 7:
                                $togT = "Dictatorship";
                                break;
                            case 8:
                                $togT = "Aristocracy";
                                break;
                            case 9:
                                $togT = "Plutocracy";
                                break;
                            case 10:
                                $togT = "Stratocracy";
                                break;
                            case 11:
                                $togT = "Stratocracy";
                                break;
                            default:
                                $togT = "Anarchy";
                                break;
                        }
                        switch ($_GET['act']) {
                            case 'setEnterT':
?>
<form action="galaxyCP.php" method="post">
New Amount: <input type="text" name="AP" /><br />
<input type="hidden" name="todo" value="1"/><br />
<button type="submit">Edit Entry Toll</button>
</form>
<?
                                break;
                            case 'setExitT':
?>
<form action="galaxyCP.php" method="post">
New Amount: <input type="text" name="AP" /><br />
<input type="hidden" name="todo" value="2"/><br />
<button type="submit">Edit Exit Toll</button>
</form>
<?
                                break;
                            case 'extract':
                                echo "<hr>Withdraw Failed!<hr>";
                                break;
                            default:
                                if ($gOwns == true) {
                                    echo "<hr>" . $gName . " CP.<hr>";
                                    echo "Galaxy Name: " . $gName . ".<br>";
                                    echo "Galaxy Government: " . $togT . ".<br>";
                                    echo "Galaxy Members: " . $gNum . ".<br>";
                                    //echo "Galaxy Register: " . $gReg . ".<br>";
                                    echo "<hr>List of Members<hr>";
                                    echo $gMembers;
                                    echo "<hr>Controls<hr>";
                                    echo "<a href='index.php?page=game&gamepage=gCP&act=setEnterT'>[Set Entry Toll]</a> ";
                                    echo "<a href='index.php?page=game&gamepage=gCP&act=setExitT'>[Set Exit Toll]</a> ";
                                    //echo "<a href='index.php?page=game&gamepage=gCP&act=extract'>[Withdraw From Galaxy Register]</a>";
                                } else {
                                    echo "<hr>You dont own a galaxy!<hr>";
                                }
                                break;
                        }
                        break;
                    case 'listitems':
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if ($color == "#636363") {
                                $color = "#000000";
                            } else {
                                $color = "#636363";
                            }
                            $Iid          = mysql_result($result, $i, "id");
                            $Iimage       = mysql_result($result, $i, "image");
                            $Iname        = mysql_result($result, $i, "name");
                            $Itype        = mysql_result($result, $i, "type");
                            $Idescription = mysql_result($result, $i, "description");
                            $AP           = mysql_result($result, $i, "AP");
                            
                            switch ($Itype) {
                                case 0:
                                    $typeT = "Health Potion";
                                    break;
                                case 1:
                                    $typeT = "Weapon";
                                    break;
                                case 2:
                                    $typeT = "Shield";
                                    break;
                                case 3:
                                    $typeT = "Helmet";
                                    break;
                                case 4:
                                    $typeT = "Armor";
                                    break;
                                case 5:
                                    $typeT = "Gloves";
                                    break;
                                case 6:
                                    $typeT = "Scripted Item";
                                    break;
                                case 7:
                                    $typeT = "Mana Potion";
                                    break;
                                case 8:
                                    $typeT = "Buff Item";
                                    break;
                                case 9:
                                    $typeT = "Nulled Item";
                                    break;
                                case 10:
                                    $typeT = "Boots";
                                    break;
                            }
                            
                            $textI .= "<tr><td bgcolor=\"$color\"><center><small>";
                            $textI .= "<font face=\"Verdana\">" . $Iid . "</font></small></center></td>";
                            $textI .= "<td bgcolor=\"$color\"><center><small>";
                            $textI .= "<font face=\"Verdana\"><img src=\"" . $Iimage . "\" border=\"2\"></font></small></center></td>";
                            $textI .= "<td bgcolor=\"$color\"><center><small>";
                            $textI .= "<font face=\"Verdana\">" . $Iname . "</font></small></center></td>";
                            $textI .= "<td bgcolor=\"$color\"><center><small>";
                            $textI .= "<font face=\"Verdana\">" . $typeT . "</font></small></center></td>";
                            $textI .= "<td bgcolor=\"$color\"><center><small>";
                            $textI .= "<font face=\"Verdana\">" . $AP . "</font></small></center></td>";
                            $textI .= "<td bgcolor=\"$color\"><center><small>";
                            $textI .= "<font face=\"Verdana\">" . $Idescription . "</font></small></center></td></tr>";
                        }
?>
<hr>
List of Items
<hr>
<table border="0" width="100%">
<tr>
<td width="5%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item ID</font></small></td>
<td width="15%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item Image</font></small></td>
<td width="10%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item Name</font></small></td>
<td width="5%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Item Type</font></small></td>
<td width="5%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">AP</font></small></td>
<td width="60%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Description</font></small></td>
</tr>
<?
                        echo $textI;
                        
?>
</tr></table>
<?
                        break;
                    case 'attack':
                        if (isset($_GET['attackID'])) {
                            $Did = $_GET['attackID'];
                            
                            $query  = "SELECT * FROM va_users";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            $query2  = "SELECT * FROM va_items";
                            $result2 = mysql_query($query2);
                            $num2    = mysql_numrows($result2);
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "user") == $usr_N_R) {
                                    $Aid       = mysql_result($result, $i, "id");
                                    $Aname     = mysql_result($result, $i, "user");
                                    $AweaponID = mysql_result($result, $i, "eqWeapon");
                                    $AshieldID = mysql_result($result, $i, "eqShield");
                                    $Astr      = mysql_result($result, $i, "str");
                                    $Adef      = mysql_result($result, $i, "def");
                                    $AAC       = mysql_result($result, $i, "AC");
                                    $AmHP      = mysql_result($result, $i, "maxHP");
                                    $AmMP      = mysql_result($result, $i, "maxMP");
                                    $Alvl      = mysql_result($result, $i, "level");
                                    $AcHP      = mysql_result($result, $i, "currentHP");
                                    $AcMP      = mysql_result($result, $i, "currentMP");
                                    $AMBid     = mysql_result($result, $i, "mailbox");
                                    $APVP      = mysql_result($result, $i, "pvp");
                                    $Apoints   = mysql_result($result, $i, "points");
                                    
                                    $Acredits = mysql_result($result, $i, "currency");
                                    $AXP      = mysql_result($result, $i, "currentXP");
                                }
                                
                                if (mysql_result($result, $i, "id") == $Did) {
                                    $Dname     = mysql_result($result, $i, "user");
                                    $DweaponID = mysql_result($result, $i, "eqWeapon");
                                    $DshieldID = mysql_result($result, $i, "eqShield");
                                    $Dstr      = mysql_result($result, $i, "str");
                                    $Ddef      = mysql_result($result, $i, "def");
                                    $DAC       = mysql_result($result, $i, "AC");
                                    $DmHP      = mysql_result($result, $i, "maxHP");
                                    $DmMP      = mysql_result($result, $i, "maxMP");
                                    $Dlvl      = mysql_result($result, $i, "level");
                                    $DcHP      = mysql_result($result, $i, "currentHP");
                                    $DcMP      = mysql_result($result, $i, "currentMP");
                                    $DMBid     = mysql_result($result, $i, "mailbox");
                                    $DPVP      = mysql_result($result, $i, "pvp");
                                    $Dpoints   = mysql_result($result, $i, "points");
                                    $Abatwon   = mysql_result($result, $i, "BattlesWon");
                                    $Abatlost  = mysql_result($result, $i, "BattlesLost");
                                    
                                    $Dcredits = mysql_result($result, $i, "currency");
                                    $DXP      = mysql_result($result, $i, "currentXP");
                                }
                            }
                            
                            $query  = "SELECT * FROM va_bounty";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            $bountynum = 0;
                            $dBounty   = 0;
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "reciveID") == $Did) {
                                    $hasBounty = true;
                                    $dBounty += mysql_result($result, $i, "bounty");
                                    $bID[$bountynum] = mysql_result($result, $i, "id");
                                    $bountynum++;
                                }
                            }
                            
                            $query  = "SELECT * FROM va_buffs";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            $ANB    = 0;
                            $DNB    = 0;
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "playerid") == $Aid) {
                                    //Buff Found!
                                    $AbuffD[$ANB] = mysql_result($result, $i, "id");
                                    $AbuffN[$ANB] = mysql_result($result, $i, "name");
                                    $AbuffI[$ANB] = mysql_result($result, $i, "image");
                                    $Astr += mysql_result($result, $i, "str");
                                    $Adef += mysql_result($result, $i, "def");
                                    $AmHP += mysql_result($result, $i, "maxHP");
                                    $AmMP += mysql_result($result, $i, "maxMP");
                                    $AbuffBattles[$ANB] = mysql_result($result, $i, "battlesleft");
                                    
                                    $AhasBuff = true;
                                    $ANB++;
                                    $AnumberBuffs++;
                                }
                                if (mysql_result($result, $i, "playerid") == $Did) {
                                    //Buff Found!
                                    $DbuffD[$DNB] = mysql_result($result, $i, "id");
                                    $DbuffN[$DNB] = mysql_result($result, $i, "name");
                                    $DbuffI[$DNB] = mysql_result($result, $i, "image");
                                    $Dstr += mysql_result($result, $i, "str");
                                    $Ddef += mysql_result($result, $i, "def");
                                    $DmHP += mysql_result($result, $i, "maxHP");
                                    $DmMP += mysql_result($result, $i, "maxMP");
                                    $DbuffBattles[$DNB] = mysql_result($result, $i, "battlesleft");
                                    
                                    $DhasBuff = true;
                                    $DNB++;
                                    $DnumberBuffs++;
                                }
                            }
                            
                            if (isset($Dname) and $Aid != $Did) {
                                
                                if ($DcHP != 0) {
                                    
                                    if ($AcHP != 0) {
                                        
                                        if ($Alvl < 5) {
                                            die("<center><hr>You cannot battle in PVP if you are less then level 5!");
                                        }
                                        if ($Dlvl < 5) {
                                            die("<center><hr>You cannot battle a user while his/her level is less then 5!");
                                        }
                                        
                                        $AttackHP = $AcHP;
                                        $DefendHP = $DcHP;
                                        $attacklog .= "<center><hr>Here today we have 2 combatints: " . $Aname . " with " . $AttackHP . " HP and " . $Dname . " with " . $DefendHP . " HP!<br>Now... Attack!<br>";
                                        
                                        //Get Defense Weapon
                                        if ($DweaponID != 0) {
                                            for ($i = 0; $i < $num2; $i++) {
                                                if (mysql_result($result2, $i, "id") == $DweaponID) {
                                                    $Dwn = mysql_result($result2, $i, "name");
                                                    $Dwd = mysql_result($result2, $i, "AP");
                                                }
                                            }
                                        } else {
                                            $Dwn = "Fist";
                                            $Dwd = 1;
                                        }
                                        
                                        //Get Attaker Weapon
                                        if ($AweaponID != 0) {
                                            for ($i = 0; $i < $num2; $i++) {
                                                if (mysql_result($result2, $i, "id") == $AweaponID) {
                                                    $Awn = mysql_result($result2, $i, "name");
                                                    $Awd = mysql_result($result2, $i, "AP");
                                                }
                                            }
                                        } else {
                                            $Awn = "Fist";
                                            $Awd = 1;
                                        }
                                        
                                        $AmD = floor(($Astr + $Awd) / ($Ddef + $DAC));
                                        $AMD = floor((($Astr * 2) + ($Awd * 1.5)) / ($Ddef + $DAC) + $Astr);
                                        
                                        $DmD = floor(($Dstr + $Dwd) / ($Adef + $AAC));
                                        $DMD = floor((($Dstr * 2) + ($Dwd * 1.5)) / ($Adef + $AAC) + $Dstr);
                                        
                                        $PKtime = date('ym');
                                        if (date('H') + 5 > 23) {
                                            $PKtime .= date('d') + 1;
                                            $PKtime .= ((date('H') + 5) - 23);
                                        } else {
                                            $PKtime .= date('d');
                                            $PKtime .= date('H') + 5;
                                        }
                                        
                                        $query = "UPDATE va_users SET pk='" . $PKtime . "' WHERE id='" . $Aid . "'";
                                        mysql_query($query) or die("Unable to update PK status!");
                                        
                                        $attacklog .= $Aname . " is now marked as a Player Killer!";
                                        /*
                                        Before
                                        while ($AttackHP > 0 and $DefendHP > 0) {
                                        srand ((double) microtime( )*1000000);
                                        $Adamage = rand($AmD,$AMD);
                                        srand ((double) microtime( )*1000000);
                                        $Ddamage = rand($DmD,$DMD);
                                        */
                                        
                                        //After
                                        
                                        while ($AttackHP > 0 and $DefendHP > 0) {
                                            
                                            $Adamage = floor(damageCalc($Alvl, $Astr, $Awd) - ($Ddef + $DAC));
                                            if ($Adamage < 1) {
                                                $Adamage = 0;
                                            }
                                            $Ddamage = floor(damageCalc($Dlvl, $Dstr, $Dwd) - ($Adef + $AAC));
                                            if ($Ddamage < 1) {
                                                $Ddamage = 0;
                                            }
                                            
                                            if ($AttackHP <= $Ddamage) {
                                                //Attacker is dead...
                                                $attacklog .= "<hr>" . $Dname . " hits " . $Aname . " with his/her " . $Dwn . " and does " . $Ddamage . " damage. " . $Aname . " is dead. (0/" . $AmHP . ")";
                                                $winner   = 0;
                                                $AttackHP = 0;
                                                break;
                                            } else {
                                                $AttackHP -= $Ddamage;
                                                $attacklog .= "<hr>" . $Dname . " hits " . $Aname . " with his/her " . $Dwn . " and does " . $Ddamage . " damage. " . $Aname . "s HP: (" . $AttackHP . "/" . $AmHP . ")";
                                            }
                                            
                                            if ($DefendHP <= $Adamage) {
                                                //Attacker is dead...
                                                $attacklog .= "<hr>" . $Aname . " hits " . $Dname . " with his/her " . $Awn . " and does " . $Adamage . " damage. " . $Dname . " is dead. (0/" . $DmHP . ")";
                                                $winner   = 1;
                                                $DefendHP = 0;
                                                break;
                                            } else {
                                                $DefendHP -= $Adamage;
                                                $attacklog .= "<hr>" . $Aname . " hits " . $Dname . " with his/her " . $Awn . " and does " . $Adamage . " damage. " . $Dname . "s HP: (" . $DefendHP . "/" . $DmHP . ")";
                                            }
                                            
                                        }
                                        
                                        if ($winner == 0) {
                                            echo $attacklog . "<br>The Battle is over!<br>" . $Dname . " Wins!";
                                            
                                            $updatedC   = $Dcredits + floor($Acredits / 50);
                                            $updatedXP  = $DXP + floor($Alvl * $AmHP);
                                            $updatedPVP = $DPVP + 1;
                                            
                                            $Dmessage = "You beat " . $Aname . " in a battle.<br>You gained: " . floor($Acredits / 50) . " Credits and " . floor($Alvl * $AmHP) . " XP!<br>Log:<br>" . $attacklog;
                                            $Amessage = "You lost to " . $Dname . " in a battle.<br>Log:<br>" . $attacklog;
                                            //$batl = ($Abatlost + 1);
                                            
                                            //$query = "UPDATE va_users SET BattlesLost='" . $batl . "' WHERE id='" . $Aid . "'";
                                            //mysql_query($query) or die("Unable to update battles lost!");
                                            
                                            $lev = ($Dlvl + 1);
                                            
                                            $need = ((pow($lev, 2)) * 200);
                                            
                                            if ($updatedXP >= $need) {
                                                $query = "UPDATE va_users SET level='" . $lev . "' WHERE id='" . $Did . "'";
                                                mysql_query($query) or die("Unable to update level!");
                                                
                                                $query = "UPDATE va_users SET points='" . ($Dpoints + 3) . "' WHERE id='" . $Did . "'";
                                                mysql_query($query) or die("Unable to update points!");
                                            }
                                            
                                            $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $Did . "'";
                                            mysql_query($query) or die("Unable to update Credits!");
                                            
                                            $query = "UPDATE va_users SET currentXP='" . $updatedXP . "' WHERE id='" . $Did . "'";
                                            mysql_query($query) or die("Unable to update XP!");
                                            
                                            $query = "UPDATE va_users SET pvp='" . $updatedPVP . "' WHERE id='" . $Did . "'";
                                            mysql_query($query) or die("Unable to update XP!");
                                            //ADD XP and Credits
                                        } else {
                                            echo $attacklog . "<br>The Battle is over!<br>" . $Aname . " Wins!<br>You gained: " . floor($Dcredits / 50) . " Credits and " . floor($Dlvl * $DmHP) . " XP!</center>";
                                            
                                            $updatedC   = $Acredits + floor($Dcredits / 50);
                                            $updatedXP  = $AXP + floor($Dlvl * $DmHP);
                                            $updatedPVP = $APVP + 1;
                                            
                                            $Amessage = "You beat " . $Dname . " in a battle.<br>You gained: " . floor($Dcredits / 50) . " Credits and " . floor($Dlvl * $DmHP) . " XP!<br>Log:<br>" . $attacklog;
                                            $Dmessage = "You lost to " . $Aname . " in a battle.<br>Log:<br>" . $attacklog;
                                            
                                            if ($hasBounty == true) {
                                                $updatedC += $dBounty;
                                                echo $Dname . " had a bounty! You collected: " . $dBounty . " credits.<br />";
                                                for ($i = 0; $i < $bountynum; $i++) {
                                                    $query = "DELETE FROM va_bounty WHERE id = '" . $bID[$i] . "'";
                                                    mysql_query($query) or die($query);
                                                }
                                                
                                            }
                                            
                                            $lev  = ($Alvl + 1);
                                            $batw = ($Abatwon + 1);
                                            
                                            $query = "UPDATE va_users SET BattlesWon='" . $batw . "' WHERE id='" . $Aid . "'";
                                            mysql_query($query) or die("Unable to update battles won!");
                                            
                                            $need = ((pow($lev, 2)) * 200);
                                            
                                            if ($updatedXP >= $need) {
                                                $query = "UPDATE va_users SET level='" . $lev . "' WHERE id='" . $Aid . "'";
                                                mysql_query($query) or die("Unable to update level!");
                                                
                                                $query = "UPDATE va_users SET points='" . ($Apoints + 3) . "' WHERE id='" . $Aid . "'";
                                                mysql_query($query) or die("Unable to update points!");
                                            }
                                            
                                            $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $Aid . "'";
                                            mysql_query($query) or die("Unable to update Credits!");
                                            
                                            $query = "UPDATE va_users SET currentXP='" . $updatedXP . "' WHERE id='" . $Aid . "'";
                                            mysql_query($query) or die("Unable to update XP!");
                                            
                                            $query = "UPDATE va_users SET pvp='" . $updatedPVP . "' WHERE id='" . $Aid . "'";
                                            mysql_query($query) or die("Unable to update XP!");
                                            //ADD XP and Credits
                                        }
                                        
                                        $query = "UPDATE va_users SET currentHP='" . $DefendHP . "' WHERE id='" . $Did . "'";
                                        mysql_query($query) or die("Unable to update Health!");
                                        
                                        $query = "UPDATE va_users SET currentHP='" . $AttackHP . "' WHERE id='" . $Aid . "'";
                                        mysql_query($query) or die("Unable to update Health!");
                                        
                                        
                                        
                                        //Update Buffs
                                        if ($AhasBuff == true) {
                                            for ($i = 0; $i < $AnumberBuffs; $i++) {
                                                //Update buff Battles Left
                                                if ($AbuffBattles[$i] <= 1) {
                                                    //Remove it.
                                                    
                                                    $query = "DELETE FROM va_buffs WHERE id = '" . $AbuffD[$i] . "'";
                                                    mysql_query($query) or die($query);
                                                    echo "The buff \"" . $AbuffN[$i] . "\" has expired!<br />";
                                                } else {
                                                    //Take one battle off!
                                                    $updatedBattlesA = $AbuffBattles[$i] - 1;
                                                    
                                                    $query = "UPDATE va_buffs SET battlesleft='" . $updatedBattlesA . "' WHERE id='" . $AbuffD[$i] . "'";
                                                    mysql_query($query) or die("Unable to edit Battles Left (Attacker)!");
                                                    
                                                    echo "The buff \"" . $AbuffN[$i] . "\" has " . $updatedBattlesA . " battles left until it expires!<br />";
                                                }
                                                
                                            }
                                        }
                                        //I have removed this part, defense should not lose buffs for combat they didn't have a say in...
                                        /*
                                        //Update Buffs
                                        if ($DhasBuff == true) {
                                        for ($i = 0; $i < $DnumberBuffs; $i++) {
                                        //Update buff Battles Left
                                        if ($DbuffBattles[$i] <= 1) {
                                        //Remove it.
                                        
                                        $query = "DELETE FROM va_buffs WHERE id = '" . $DbuffD[$i] . "'";
                                        mysql_query($query) or die($query);
                                        echo "The buff \"" . $DbuffN[$i] . "\" has expired!";
                                        } else {
                                        //Take one battle off!
                                        $updatedBattlesD = $DbuffBattles[$i] - 1;
                                        
                                        $query = "UPDDTE va_buffs SET battlesleft='" . $updatedBattlesD . "' WHERE id='" . $DbuffD[$i] . "'";
                                        mysql_query($query) or die("Unable to edit Battles Left (Defender)!");
                                        
                                        echo "The buff \"" . $DbuffN[$i] . "\" has " . $updatedBattlesD . " battles left until it expires!";
                                        }
                                        
                                        }
                                        }
                                        */
                                        
                                        //Send the Mail to both people.
                                        $query  = "SELECT * FROM va_mailbox";
                                        $result = mysql_query($query);
                                        $num1   = mysql_numrows($result);
                                        
                                        //START OF MESSAGE CREATION
                                        
                                        $Dsubject = "You got attacked!";
                                        
                                        $Asubject = "Your attacking Information.";
                                        
                                        //END OF MESSAGE CREATION
                                        
                                        
                                        //SEND MESSAGE TO DEFENDER
                                        for ($i2 = 0; $i2 < $num1; $i2++) {
                                            if (mysql_result($result, $i2, "id") == $DMBid) {
                                                
                                                for ($i = 0; $i < 30; $i++) {
                                                    if (mysql_result($result, $i2, "mbS" . $i) == "") {
                                                        $notfull = true;
                                                        $query   = "UPDATE va_mailbox SET mbS" . $i . "='" . $Dsubject . "' WHERE id='" . $DMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- Subject.");
                                                        
                                                        $query = "UPDATE va_mailbox SET mbM" . $i . "='" . $Dmessage . "' WHERE id='" . $DMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- Message.");
                                                        
                                                        $query = "UPDATE va_mailbox SET mbF" . $i . "='Arena Officer' WHERE id='" . $DMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- From.");
                                                        
                                                        $query = "UPDATE va_mailbox SET mbT" . $i . "='Unread' WHERE id='" . $DMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- Status");
                                                        break;
                                                    }
                                                }
                                                
                                            }
                                        }
                                        
                                        //SEND MESSAGE TO ATTACKER
                                        for ($i2 = 0; $i2 < $num1; $i2++) {
                                            if (mysql_result($result, $i2, "id") == $AMBid) {
                                                
                                                for ($i = 0; $i < 30; $i++) {
                                                    if (mysql_result($result, $i2, "mbS" . $i) == "") {
                                                        $notfull = true;
                                                        $query   = "UPDATE va_mailbox SET mbS" . $i . "='" . $Asubject . "' WHERE id='" . $AMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- Subject.");
                                                        
                                                        $query = "UPDATE va_mailbox SET mbM" . $i . "='" . $Amessage . "' WHERE id='" . $AMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- Message.");
                                                        
                                                        $query = "UPDATE va_mailbox SET mbF" . $i . "='Arena Officer' WHERE id='" . $AMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- From.");
                                                        
                                                        $query = "UPDATE va_mailbox SET mbT" . $i . "='Unread' WHERE id='" . $AMBid . "'";
                                                        mysql_query($query) or die("Unable to send -- Status");
                                                        break;
                                                    }
                                                }
                                                
                                            }
                                        }
                                        
                                    } else {
                                        echo "<a href='index.php?page=game&gamepage=healer'>You have no HP! Click here to visit the Healer!</a>";
                                    }
                                    
                                } else {
                                    echo "<center><hr>You cant attack a user who has 0 HP!<hr></center>";
                                }
                                
                            } else {
                                echo "<center><hr>That user does not exist.<hr></center>";
                            }
                        } else {
                            echo "<center><hr>That user does not exist.<hr></center>";
                        }
                        break;
                    case 'healer':
                        
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $id       = mysql_result($result, $i, "id");
                                $currency = mysql_result($result, $i, "currency");
                                $maxHP    = mysql_result($result, $i, "maxHP");
                                $maxMP    = mysql_result($result, $i, "maxMP");
                                $pk       = mysql_result($result, $i, "pk");
                            }
                        }
                        
                        $cost = floor($currency / 14);
                        
                        $PKtime = date('ymdH');
                        
                        if (isset($_GET['healme'])) {
                            if ($pk < $PKtime or $pk == 0) {
                                $UpC = $currency -= $cost;
                                
                                $query = "UPDATE va_users SET currency='" . $UpC . "' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to update Credits!");
                                
                                $query = "UPDATE va_users SET currentHP='" . $maxHP . "' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to update HP!");
                                
                                $query = "UPDATE va_users SET currentMP='" . $maxMP . "' WHERE id='" . $id . "'";
                                mysql_query($query) or die("Unable to update MP!");
                                
                                echo "<center><hr>You have been fully healed!<hr></center>";
                            } else {
                                echo "<center><hr>How dare you walk in here, get out! Player Killers are despised here, out! OUT!<hr></center>";
                            }
                            
                        } else {
                            if ($pk < $PKtime or $pk == 0) {
                                echo "<center><hr>Would you like to get your Health Points and Magic Points fully restored for " . $cost . " Credits?<br><br>[<a href='index.php?page=game&gamepage=healer&healme=1'>Yes</a> | <a href='index.php?page=game'>No</a>]<hr></center>";
                            } else {
                                echo "<center><hr>How dare you walk in here, get out! Player Killers are despised here, out. out! OUT!<hr></center>";
                            }
                            
                        }
                        break;
                    case 'addF':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $Uid   = mysql_result($result, $i, "id");
                                $invID = mysql_result($result, $i, "inv" . $_GET['id']);
                            }
                        }
                        
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $invID) {
                                $itemID = mysql_result($result, $i, "id");
                            }
                        }
                        
                        if (isset($_GET['id'])) {
                            $query = "INSERT INTO va_worktable VALUES ('','" . $Uid . "','" . $itemID . "')";
                            mysql_query($query) or die("Unable to create ticket!");
                            
                            $query = "UPDATE va_users SET inv" . $_GET['id'] . " = '0' WHERE id='" . $Uid . "'";
                            mysql_query($query) or die($query);
                        }
                        
                        echo "<hr>Added Item to Factory!<hr>";
                        break;
                    case 'removef':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $Uid = mysql_result($result, $i, "id");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result, $i, "inv" . $i2) == 0) {
                                        $entryID3 = $i2;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        $query  = "SELECT * FROM va_worktable";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "itemid") == $_GET['id'] and mysql_result($result, $i, "playerid") == $Uid) {
                                $itemID = mysql_result($result, $i, "itemid");
                                $wtID   = mysql_result($result, $i, "id");
                            }
                        }
                        
                        if (isset($_GET['id'])) {
                            if (isset($entryID3)) {
                                $query = "DELETE FROM va_worktable WHERE id = '" . $wtID . "'";
                                mysql_query($query) or die($query);
                                
                                $query = "UPDATE va_users SET inv" . $entryID3 . " = '" . $itemID . "' WHERE id='" . $Uid . "'";
                                mysql_query($query) or die($query);
                                echo "<hr>Item was removed and was added back into your inventory.<hr>";
                            } else {
                                echo "<hr>You do not have enough space in your inventory to remove this item!<hr>";
                            }
                        }
                        break;
                    case 'factory':
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $userID = mysql_result($result, $i, "id");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_worktable";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NWT    = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "playerid") == $userID) {
                                $itemID[$NWT] = mysql_result($result, $i, "itemid");
                                $WTid[$NWT]   = mysql_result($result, $i, "id");
                                $NWT++;
                                $nIt++;
                            }
                        }
                        $maxI   = $nIt + 1;
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NM     = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            for ($i2 = 0; $i2 < $nIt; $i2++) {
                                if (mysql_result($result, $i, "id") == $itemID[$i2]) {
                                    //Buff Found!
                                    $itemIDs[$NM] = mysql_result($result, $i, "id");
                                    $itemN[$NM]   = mysql_result($result, $i, "name");
                                    $itemIM[$NM]  = mysql_result($result, $i, "image");
                                    $NM++;
                                    $nIts++;
                                }
                            }
                        }
                        
                        $type = $_GET['act'];
                        for ($i = 0; $i < $nIt; $i++) {
                            $recipe += floor($itemID[$i] * 1.5);
                        }
                        
                        $query  = "SELECT * FROM va_recipe";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "creationType") == $type and $recipe == mysql_result($result, $i, "creationNum")) {
                                $created  = true;
                                $createdI = mysql_result($result, $i, "createdID");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $createdI) {
                                $createdN = mysql_result($result, $i, "name");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $Uid = mysql_result($result, $i, "id");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result, $i, "inv" . $i2) == 0) {
                                        $entryID3 = $i2;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        switch ($_GET['act']) {
                            case '1':
                                //Chemistry Lab
                                if ($created == true) {
                                    if (isset($entryID3)) {
                                        for ($i = 0; $i < $nIt; $i++) {
                                            $query = "DELETE FROM va_worktable WHERE playerid = '" . $Uid . "'";
                                            mysql_query($query) or die($query);
                                        }
                                        
                                        $query = "UPDATE va_users SET inv" . $entryID3 . " = '" . $createdI . "' WHERE id='" . $Uid . "'";
                                        mysql_query($query) or die($query);
                                        
                                        echo "<hr>Successfully Created: " . $createdN . "<hr>";
                                    } else {
                                        echo "<hr>Clear some space in your inventory first.<hr>";
                                    }
                                } else {
                                    echo "<hr>Mixture Failed!<hr>";
                                }
                                break;
                            case '2':
                                //Forge
                                if ($created == true) {
                                    if (isset($entryID3)) {
                                        for ($i = 0; $i < $nIt; $i++) {
                                            $query = "DELETE FROM va_worktable WHERE playerid = '" . $Uid . "'";
                                            mysql_query($query) or die($query);
                                        }
                                        
                                        $query = "UPDATE va_users SET inv" . $entryID3 . " = '" . $createdI . "' WHERE id='" . $Uid . "'";
                                        mysql_query($query) or die($query);
                                        
                                        echo "<hr>Successfully Created: " . $createdN . "<hr>";
                                    } else {
                                        echo "<hr>Clear some space in your inventory first.<hr>";
                                    }
                                } else {
                                    echo "<hr>Mixture Failed!<hr>";
                                }
                                break;
                            case '2006':
                                echo $recipe;
                                break;
                            case '3':
                                //Oven
                                if ($created == true) {
                                    if (isset($entryID3)) {
                                        for ($i = 0; $i < $nIt; $i++) {
                                            $query = "DELETE FROM va_worktable WHERE playerid = '" . $Uid . "'";
                                            mysql_query($query) or die($query);
                                        }
                                        
                                        $query = "UPDATE va_users SET inv" . $entryID3 . " = '" . $createdI . "' WHERE id='" . $Uid . "'";
                                        mysql_query($query) or die($query);
                                        
                                        echo "<hr>Successfully Created: " . $createdN . "<hr>";
                                    } else {
                                        echo "<hr>Clear some space in your inventory first.<hr>";
                                    }
                                } else {
                                    echo "<hr>Mixture Failed!<hr>";
                                }
                                break;
                            case '4':
                                //Stir
                                if ($created == true) {
                                    if (isset($entryID3)) {
                                        for ($i = 0; $i < $nIt; $i++) {
                                            $query = "DELETE FROM va_worktable WHERE playerid = '" . $Uid . "'";
                                            mysql_query($query) or die($query);
                                        }
                                        
                                        $query = "UPDATE va_users SET inv" . $entryID3 . " = '" . $createdI . "' WHERE id='" . $Uid . "'";
                                        mysql_query($query) or die($query);
                                        
                                        echo "<hr>Successfully Created: " . $createdN . "<hr>";
                                    } else {
                                        echo "<hr>Clear some space in your inventory first.<hr>";
                                    }
                                } else {
                                    echo "<hr>Mixture Failed!<hr>";
                                }
                                break;
                            default:
                                //Items+Actions
                                echo "<hr>Items Inside Factory<hr>";
                                if ($NWT > 0) {
                                    for ($i = 0; $i < $nIt; $i++) {
                                        echo "<a href='index.php?page=game&gamepage=removef&id=" . $itemIDs[$i] . "'><img src='" . $itemIM[$i] . "' border=0></a><br>Name: " . $itemN[$i] . "<hr>";
                                    }
                                } else {
                                    echo "There are no items in your factory!";
                                }
                                echo "<br>";
                                //List Items
                                echo "<hr>Actions<hr>";
                                echo "<a href='index.php?page=game&gamepage=factory&act=1'>Brew in Chemistry Lab</a> &#8226; <a href='index.php?page=game&gamepage=factory&act=2'>Metal Work in the Forge</a> &#8226; <a href='index.php?page=game&gamepage=factory&act=3'>Bake in Oven</a> &#8226; <a href='index.php?page=game&gamepage=factory&act=4'>Mix and Stir</a><hr>";
                                break;
                        }
                        break;
                    case 'addB':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $Uid   = mysql_result($result, $i, "id");
                                $invID = mysql_result($result, $i, "inv" . $_GET['id']);
                            }
                        }
                        
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == $invID) {
                                $itemID = mysql_result($result, $i, "id");
                            }
                        }
                        
                        if (isset($_GET['id'])) {
                            $query = "INSERT INTO va_bank VALUES ('','" . $Uid . "','" . $itemID . "')";
                            mysql_query($query) or die("Unable to create ticket!");
                            
                            $query = "UPDATE va_users SET inv" . $_GET['id'] . " = '0' WHERE id='" . $Uid . "'";
                            mysql_query($query) or die($query);
                        }
                        
                        echo "<hr>Added Item to Bank!<hr>";
                        break;
                    case 'removeb':
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $Uid = mysql_result($result, $i, "id");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result, $i, "inv" . $i2) == 0) {
                                        $entryID3 = $i2;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        $query  = "SELECT * FROM va_bank";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "itemid") == $_GET['id'] and mysql_result($result, $i, "playerid") == $Uid) {
                                $itemID = mysql_result($result, $i, "itemid");
                                $wtID   = mysql_result($result, $i, "id");
                            }
                        }
                        
                        if (isset($_GET['id'])) {
                            if (isset($entryID3)) {
                                $query = "DELETE FROM va_bank WHERE id = '" . $wtID . "'";
                                mysql_query($query) or die($query);
                                
                                $query = "UPDATE va_users SET inv" . $entryID3 . " = '" . $itemID . "' WHERE id='" . $Uid . "'";
                                mysql_query($query) or die($query);
                                echo "<hr>Item was removed and was added back into your inventory.<hr>";
                            } else {
                                echo "<hr>You do not have enough space in your inventory to remove this item!<hr>";
                            }
                        }
                        break;
                    case 'bank':
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $userID = mysql_result($result, $i, "id");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_bank";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NWT    = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "playerid") == $userID) {
                                $itemID[$NWT] = mysql_result($result, $i, "itemid");
                                $WTid[$NWT]   = mysql_result($result, $i, "id");
                                $NWT++;
                                $nIt++;
                            }
                        }
                        $maxI   = $nIt + 1;
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $NM     = 0;
                        for ($i = 0; $i < $num1; $i++) {
                            for ($i2 = 0; $i2 < $nIt; $i2++) {
                                if (mysql_result($result, $i, "id") == $itemID[$i2]) {
                                    //Buff Found!
                                    $itemIDs[$NM] = mysql_result($result, $i, "id");
                                    $itemN[$NM]   = mysql_result($result, $i, "name");
                                    $itemIM[$NM]  = mysql_result($result, $i, "image");
                                    $NM++;
                                    $nIts++;
                                }
                            }
                        }
                        
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $usr_N_R) {
                                $Uid = mysql_result($result, $i, "id");
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    if (mysql_result($result, $i, "inv" . $i2) == 0) {
                                        $entryID3 = $i2;
                                        break;
                                    }
                                }
                            }
                        }
                        //Items+Actions
                        echo "<hr>Items Inside Bank<hr>";
                        if ($NWT > 0) {
                            for ($i = 0; $i < $nIt; $i++) {
                                echo "<a href='index.php?page=game&gamepage=removeb&id=" . $itemIDs[$i] . "'><img src='" . $itemIM[$i] . "' border=0></a><br>Name: " . $itemN[$i] . "<hr>";
                            }
                        } else {
                            echo "There are no items in your bank!";
                            echo "<hr>";
                        }
                        
                        break;
                    case 'calc1':
                        $x = $_GET['level'];
                        $y = 0;
                        
                        $y = ((pow($x, 2)) * 200);
                        
                        echo $y;
                        break;
                    case 'battle':
                        if (isset($_GET['skill'])) {
                            
                            switch ($_GET['skill']) {
                                case 1:
                                    $skill = 0.25;
                                    $sk    = 1;
                                    $skWID = 1002;
                                    break;
                                case 2:
                                    $skill = 0.5;
                                    $sk    = 2;
                                    $skWID = 1007;
                                    break;
                                case 3:
                                    $skill = 1;
                                    $sk    = 3;
                                    $skWID = 31;
                                    break;
                                case 4:
                                    $skill = 1.5;
                                    $sk    = 4;
                                    $skWID = 1004;
                                    break;
                                case 5:
                                    $skill = 2;
                                    $sk    = 5;
                                    $skWID = 16;
                                    break;
                                case 6:
                                    $skill = 4;
                                    $sk    = 6;
                                    $skWID = 17;
                                    break;
                                default:
                                    $skill = 1;
                                    $sk    = 1;
                                    $skWID = 1002;
                                    break;
                            }
                            if (isset($_GET['name'])) {
                                $randomN = $_GET['name'];
                            } else {
                                srand((double) microtime() * 1000000);
                                $randomNum = rand(1, 10);
                                $r2        = rand(1, 10);
                                switch ($randomNum) {
                                    case 1:
                                        $randomN = "Bob";
                                        break;
                                    case 2:
                                        $randomN = "Jim";
                                        break;
                                    case 3:
                                        $randomN = "Tyler";
                                        break;
                                    case 4:
                                        $randomN = "Brandon";
                                        break;
                                    case 5:
                                        $randomN = "Vader";
                                        break;
                                    case 6:
                                        $randomN = "Kitty";
                                        break;
                                    case 7:
                                        $randomN = "Elvis";
                                        break;
                                    case 8:
                                        if ($r2 == 8) {
                                            $randomN = "KyleD";
                                        } else {
                                            $randomN = "Dragon";
                                        }
                                        break;
                                    case 9:
                                        $randomN = "John";
                                        break;
                                    case 10:
                                        $randomN = "Starsmith";
                                        break;
                                }
                            }
                            
                            $query  = "SELECT * FROM va_users";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            $query2  = "SELECT * FROM va_items";
                            $result2 = mysql_query($query2);
                            $num2    = mysql_numrows($result2);
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "user") == $usr_N_R) {
                                    $Aid       = mysql_result($result, $i, "id");
                                    $Aname     = mysql_result($result, $i, "user");
                                    $AwID      = mysql_result($result, $i, "eqWeapon");
                                    $AshieldID = mysql_result($result, $i, "eqShield");
                                    $Astr      = mysql_result($result, $i, "str");
                                    $Adef      = mysql_result($result, $i, "def");
                                    $AAC       = mysql_result($result, $i, "AC");
                                    $AmHP      = mysql_result($result, $i, "maxHP");
                                    $AmMP      = mysql_result($result, $i, "maxMP");
                                    $Alvl      = mysql_result($result, $i, "level");
                                    $AcHP      = mysql_result($result, $i, "currentHP");
                                    $AcMP      = mysql_result($result, $i, "currentMP");
                                    $AMBid     = mysql_result($result, $i, "mailbox");
                                    $APVP      = mysql_result($result, $i, "pvp");
                                    $Apoints   = mysql_result($result, $i, "points");
                                    
                                    $Acredits = mysql_result($result, $i, "currency");
                                    $AXP      = mysql_result($result, $i, "currentXP");
                                    
                                    $AMS = 0;
                                    
                                    for ($i2 = 0; $i2 < 30; $i2++) {
                                        if (mysql_result($result, $i, "spell" . $i2) != "") {
                                            $AspellN[$AMS] = mysql_result($result, $i, "spell" . $i2);
                                            //echo $AspellN[$AMS] . " Cast: " . $AMS . "<br>";
                                            $ASpower[$AMS] = spellpower($AspellN[$AMS]);
                                            
                                            $ASpowerTY[$AMS] = spelltype($AspellN[$AMS]);
                                            
                                            $ASpowerTYt[$AMS] = spelltypeT($ASpower[$AMS], $ASpowerTY[$AMS]);
                                            
                                            $ASpowerD[$AMS] = powerDesc($AspellN[$AMS]);
                                            
                                            $aSAP[$AMS] = floor($ASpower[$AMS] * 0.1);
                                            
                                            if ($AP[$AMS] < 5) {
                                                $aSAP[$AMS] = $ASpower[$AMS];
                                            }
                                            
                                            $ASmanaREQ[$AMS] = floor($ASpower[$AMS] / 8);
                                            
                                            if ($ASmanaREQ[$AMS] < 5) {
                                                $ASmanaREQ[$AMS] = 5;
                                            }
                                            if ($ASpower[$AMS] < 1) {
                                                $ASmanaREQ[$AMS] = 0;
                                            }
                                            
                                            $AMS++;
                                        }
                                    }
                                    
                                }
                            }
                            $AMS -= 1;
                            
                            $Dname     = "ai" . $randomN;
                            $DwID      = $skWID;
                            $DshieldID = 0;
                            $Dstr      = floor($Astr * $skill);
                            $Ddef      = floor($Adef * $skill);
                            $DAC       = floor($AAC * $skill);
                            $DmHP      = floor($AmHP * $skill);
                            $DmMP      = floor($AmMP * $skill);
                            $Dlvl      = floor($Alvl + $skill);
                            $DcHP      = floor($AmHP * $skill);
                            $DcMP      = floor($AmMP * $skill);
                            $Dcredits  = floor(100 * $sk);
                            
                            
                            $query  = "SELECT * FROM va_buffs";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            $ANB    = 0;
                            $DNB    = 0;
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "playerid") == $Aid) {
                                    //Buff Found!
                                    $AbuffD[$ANB] = mysql_result($result, $i, "id");
                                    $AbuffN[$ANB] = mysql_result($result, $i, "name");
                                    $AbuffI[$ANB] = mysql_result($result, $i, "image");
                                    $Astr += mysql_result($result, $i, "str");
                                    $Adef += mysql_result($result, $i, "def");
                                    $AmHP += mysql_result($result, $i, "maxHP");
                                    $AmMP += mysql_result($result, $i, "maxMP");
                                    $AbuffBattles[$ANB] = mysql_result($result, $i, "battlesleft");
                                    
                                    $ANB++;
                                    $AnumberBuffs++;
                                }
                            }
                            
                            //Get Defense Weapon
                            if ($DwID != 0) {
                                
                                for ($i2 = 0; $i2 < $num2; $i2++) {
                                    if (mysql_result($result2, $i2, "id") == $DwID) {
                                        $Dwn = mysql_result($result2, $i2, "name");
                                        $Dwd = mysql_result($result2, $i2, "AP");
                                    }
                                }
                                
                            } else {
                                $Dwn = "Fist";
                                $Dwd = 1;
                            }
                            
                            //Get Attaker Weapon
                            if ($AwID != 0) {
                                
                                for ($i2 = 0; $i2 < $num2; $i2++) {
                                    if (mysql_result($result2, $i2, "id") == $AwID) {
                                        $Awn = mysql_result($result2, $i2, "name");
                                        $Awd = mysql_result($result2, $i2, "AP");
                                    }
                                }
                                
                            } else {
                                $Awn = "Fist";
                                $Awd = 1;
                            }
                            
                            $AttackHP = $AcHP;
                            $DefendHP = $DcHP;
                            $second   = ($Ddef + $DAC);
                            if ($second == 0) {
                                $second = 1;
                            }
                            
                            srand((double) microtime() * 1000000);
                            $fN = rand(1, 5);
                            
                            if ($fN == 4) {
                                $fear = true;
                            } else {
                                $fear = false;
                            }
                            
                            
                            if ($AcHP != 0) {
                                
                                if ($fear != true) {
                                    /*
                                    OLD DAMAGE FORMULA.
                                    
                                    $AmD = floor(($Astr + $Awd)/($second));
                                    $AMD = floor((($Astr*2) + ($Awd*1.5))/($second) + $Astr);
                                    
                                    $DmD = floor(($Dstr + $Dwd)/($Adef + $AAC));
                                    $DMD = floor((($Dstr*2) + ($Dwd*1.5))/($Adef + $AAC) + $Dstr);
                                    
                                    
                                    
                                    while ($AttackHP > 0 and $DefendHP > 0) {
                                    srand ((double) microtime( )*1000000);
                                    $Adamage = rand($AmD,$AMD);
                                    srand ((double) microtime( )*1000000);
                                    $Ddamage = rand($DmD,$DMD);
                                    */
                                    while ($AttackHP > 0 and $DefendHP > 0) {
                                        
                                        $Adamage = floor(damageCalc($Alvl, $Astr, $Awd) - ($Ddef + $DAC));
                                        if ($Adamage < 1) {
                                            $Adamage = 0;
                                        }
                                        $Ddamage = floor(damageCalc($Dlvl, $Dstr, $Dwd) - ($Adef + $AAC));
                                        if ($Ddamage < 1) {
                                            $Ddamage = 0;
                                        }
                                        
                                        $spellA = 1;
                                        $spellD = 2;
                                        if ($AMS > 0) {
                                            if ($spellA == 1) {
                                                //Attacker Casts a Spell
                                                srand((double) microtime() * 1000000);
                                                $aCast = rand(0, $AMS);
                                                if ($AcMP >= $ASmanaREQ[$aCast]) {
                                                    //echo "Cast: " . $aCast;
                                                    if ($ASpowerTY[$aCast] == 0) {
                                                        $AttackHP += $aSAP[$aCast];
                                                        $attacklog .= "<hr>" . $Aname . " mutters a spell, " . $AspellN[$aCast] . ", and uses " . $ASmanaREQ[$aCast] . " MP to heal for " . $aSAP[$aCast] . " HP! " . $Aname . "s HP: (" . $AttackHP . "/" . $AmHP . ")";
                                                    } else {
                                                        if ($DefendHP <= $aSAP[$aCast]) {
                                                            //Attacker is dead...
                                                            $attacklog .= "<hr>" . $Aname . " mutters a spell, " . $AspellN[$aCast] . ", and it hits " . $Dname . " and does " . $aSAP[$aCast] . " damage. " . $Dname . " is dead. (0/" . $DmHP . ")";
                                                            $winner   = 1;
                                                            $DefendHP = 0;
                                                            break;
                                                        } else {
                                                            $DefendHP -= $aSAP[$aCast];
                                                            $attacklog .= "<hr>" . $Aname . " mutters a spell, " . $AspellN[$aCast] . ", and it hits " . $Dname . " and does " . $aSAP[$aCast] . " damage. " . $Dname . "s HP: (" . $DefendHP . "/" . $DmHP . ")";
                                                        }
                                                    }
                                                    $AcMP -= $ASmanaREQ[$aCast];
                                                } else {
                                                    $attacklog .= "<hr>" . $Aname . " tries to cast a spell but is out of Mana!";
                                                }
                                                $spellA = 0;
                                            }
                                            
                                            if ($DefendHP <= $Adamage) {
                                                //Attacker is dead...
                                                $attacklog .= "<hr>" . $Aname . " hits " . $Dname . " with his/her " . $Awn . " and does " . $Adamage . " damage. " . $Dname . " is dead. (0/" . $DmHP . ")";
                                                $winner   = 1;
                                                $DefendHP = 0;
                                                break;
                                            } else {
                                                $DefendHP -= $Adamage;
                                                $attacklog .= "<hr>" . $Aname . " hits " . $Dname . " with his/her " . $Awn . " and does " . $Adamage . " damage. " . $Dname . "s HP: (" . $DefendHP . "/" . $DmHP . ")";
                                                $spellA = 1;
                                            }
                                        } else {
                                            if ($DefendHP <= $Adamage) {
                                                //Attacker is dead...
                                                $attacklog .= "<hr>" . $Aname . " hits " . $Dname . " with his/her " . $Awn . " and does " . $Adamage . " damage. " . $Dname . " is dead. (0/" . $DmHP . ")";
                                                $winner   = 1;
                                                $DefendHP = 0;
                                                break;
                                            } else {
                                                $DefendHP -= $Adamage;
                                                $attacklog .= "<hr>" . $Aname . " hits " . $Dname . " with his/her " . $Awn . " and does " . $Adamage . " damage. " . $Dname . "s HP: (" . $DefendHP . "/" . $DmHP . ")";
                                            }
                                        }
                                        
                                        if ($spellD == 1) {
                                            //Defender Casts a Spell
                                            srand((double) microtime() * 1000000);
                                            $cast = rand(1, 15);
                                            if ($cast == 2) {
                                                if ($DcMP >= 5) {
                                                    $DefendHP += 4;
                                                    $DcMP -= 5;
                                                    $attacklog .= "<hr>" . $Dname . " mutters a word and uses 5MP to heal for 4 HP!";
                                                } else {
                                                    $attacklog .= "<hr>" . $Dname . " tries to cast a spell but fails!";
                                                }
                                            } else {
                                                $attacklog .= "<hr>" . $Dname . " tries to cast a spell but is out of Mana!";
                                            }
                                            $spellD = 0;
                                        } else {
                                            if ($AttackHP <= $Ddamage) {
                                                //Attacker is dead...
                                                $attacklog .= "<hr>" . $Dname . " hits " . $Aname . " with his/her " . $Dwn . " and does " . $Ddamage . " damage. " . $Aname . " is dead. (0/" . $AmHP . ")";
                                                $winner   = 0;
                                                $AttackHP = 0;
                                                break;
                                            } else {
                                                $AttackHP -= $Ddamage;
                                                $attacklog .= "<hr>" . $Dname . " hits " . $Aname . " with his/her " . $Dwn . " and does " . $Ddamage . " damage. " . $Aname . "s HP: (" . $AttackHP . "/" . $AmHP . ")";
                                                $spellD = 2;
                                            }
                                        }
                                        
                                    }
                                    
                                    if ($winner == 0) {
                                        echo $attacklog . "<br>The Battle is over!<hr>" . $Dname . " Wins!";
                                        
                                        $Amessage = "You lost to " . $Dname . " in a battle.<br>Log:<br>" . $attacklog;
                                        
                                        //ADD XP and Credits
                                    } else {
                                        echo $attacklog . "<br>The Battle is over!<hr>" . $Aname . " Wins!<br>You gained: " . $Dcredits . " Credits and " . floor($sk * 25) . " XP!";
                                        
                                        $updatedC  = $Acredits + floor($Dcredits);
                                        $updatedXP = $AXP + floor($sk * 25);
                                        
                                        $Amessage = "You beat " . $Dname . " in a battle.<br>You gained: " . $Dcredits . " Credits and " . floor($sk * 25) . " XP!<br>Log:<br>" . $attacklog;
                                        
                                        $lev = ($Alvl + 1);
                                        
                                        $need = ((pow($lev, 2)) * 200);
                                        
                                        $tnl = $need - $updatedXP;
                                        
                                        if ($tnl < 1) {
                                            echo "<br />You leveled up!";
                                        } else {
                                            echo "<br />You need " . $tnl . " more XP to get to level " . $lev . "!";
                                        }
                                        
                                        if ($updatedXP >= $need) {
                                            $query = "UPDATE va_users SET level='" . $lev . "' WHERE id='" . $Aid . "'";
                                            mysql_query($query) or die("Unable to update level!");
                                            
                                            $query = "UPDATE va_users SET points='" . ($Apoints + 3) . "' WHERE id='" . $Aid . "'";
                                            mysql_query($query) or die("Unable to update points!");
                                        }
                                        
                                        $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $Aid . "'";
                                        mysql_query($query) or die("Unable to update Credits!");
                                        
                                        $query = "UPDATE va_users SET currentXP='" . $updatedXP . "' WHERE id='" . $Aid . "'";
                                        mysql_query($query) or die("Unable to update XP!");
                                        //ADD XP and Credits
                                    }
                                    
                                    $query = "UPDATE va_users SET currentHP='" . $AttackHP . "' WHERE id='" . $Aid . "'";
                                    mysql_query($query) or die("Unable to update Health!");
                                    
                                    $query = "UPDATE va_users SET currentMP='" . $AcMP . "' WHERE id='" . $Aid . "'";
                                    mysql_query($query) or die("Unable to update Health!");
                                    
                                    //Update Buffs
                                    if ($AnumberBuffs > 0) {
                                        for ($i = 0; $i < $AnumberBuffs; $i++) {
                                            //Update buff Battles Left
                                            if ($AbuffBattles[$i] < 2) {
                                                //Remove it.
                                                
                                                $query = "DELETE FROM va_buffs WHERE id = '" . $AbuffD[$i] . "'";
                                                mysql_query($query) or die($query);
                                                echo "<br>The buff \"" . $AbuffN[$i] . "\" has expired!";
                                            } else {
                                                //Take one battle off!
                                                $updatedBattles = $AbuffBattles[$i] - 1;
                                                
                                                $query = "UPDATE va_buffs SET battlesleft='" . $updatedBattles . "' WHERE id='" . $AbuffD[$i] . "'";
                                                mysql_query($query) or die("Unable to edit password!");
                                                
                                                echo "<br>The buff \"" . $AbuffN[$i] . "\" has " . $updatedBattles . " battles left until it expires!";
                                            }
                                            
                                        }
                                    }
                                    
                                    
                                    
                                    /*
                                    //Send the Mail to both people.
                                    $query = "SELECT * FROM va_mailbox";
                                    $result = mysql_query($query);
                                    $num1 = mysql_numrows($result);
                                    
                                    //START OF MESSAGE CREATION
                                    
                                    $Asubject = "Your attacking Information.";
                                    
                                    //END OF MESSAGE CREATION
                                    
                                    //SEND MESSAGE TO ATTACKER
                                    for ($i2 = 0; $i2 < $num1; $i2++) {
                                    if (mysql_result($result,$i2,"id") == $AMBid) {
                                    
                                    for ($i = 0; $i < 30; $i++) {
                                    if (mysql_result($result,$i2,"mbS" . $i) == "") {
                                    $notfull = true;
                                    $query = "UPDATE va_mailbox SET mbS" . $i . "='" . $Asubject . "' WHERE id='" . $AMBid . "'";
                                    mysql_query($query) or die("Unable to send -- Subject.");
                                    
                                    $query = "UPDATE va_mailbox SET mbM" . $i . "='" . $Amessage . "' WHERE id='" . $AMBid . "'";
                                    mysql_query($query) or die("Unable to send -- Message.");
                                    
                                    $query = "UPDATE va_mailbox SET mbF" . $i . "='Arena Officer' WHERE id='" . $AMBid . "'";
                                    mysql_query($query) or die("Unable to send -- From.");
                                    
                                    $query = "UPDATE va_mailbox SET mbT" . $i . "='Unread' WHERE id='" . $AMBid . "'";
                                    mysql_query($query) or die("Unable to send -- Status");
                                    break;
                                    }
                                    }
                                    
                                    }
                                    }
                                    */
                                    
                                } else {
                                    echo $Dname . " was too scared to fight you!";
                                }
                                
                            } else {
                                echo "<a href='index.php?page=game&gamepage=healer'>You have no HP! Click here to visit the Healer!</a>";
                            }
                        } else {
                            echo "<hr>Please select a skill level:<br>";
                            echo "<a href='index.php?page=game&gamepage=battle&skill=1'>Very easy</a><br>";
                            echo "<a href='index.php?page=game&gamepage=battle&skill=2'>Easy</a><br>";
                            echo "<a href='index.php?page=game&gamepage=battle&skill=3'>Average</a><br>";
                            echo "<a href='index.php?page=game&gamepage=battle&skill=4'>Hard</a><br>";
                            echo "<a href='index.php?page=game&gamepage=battle&skill=5'>Very Hard</a><br>";
                            echo "<a href='index.php?page=game&gamepage=battle&skill=6'>Near Impossible</a><br>";
                            echo "<hr>";
                        }
                        break;
                    case 'travel':
                        if (isset($_GET['areaID'])) {
                            $query  = "SELECT * FROM va_users";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "user") == $usr_N_R) {
                                    $galaxyID = mysql_result($result, $i, "Cgalaxy");
                                }
                            }
                            switch ($galaxyID) {
                                case 0:
                                    
                                    break;
                                case 1:
                                    switch ($_GET['areaID']) {
                                        case 1:
                                            echo "<IMG NAME='a210' SRC='images/solar/1-1.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#1-1'>";
                                            
                                            break;
                                        case 2:
                                            echo "<IMG NAME='a220' SRC='images/solar/1-2.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#1-2'>";
                                            
                                            break;
                                        case 3:
                                            echo "<IMG NAME='a220' SRC='images/solar/1-3.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#1-3'>";
                                            
                                            break;
                                    }
                                    break;
                                case 2:
                                    switch ($_GET['areaID']) {
                                        case 1:
                                            echo "<IMG NAME='a210' SRC='images/solar/2-1.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#2-1'>";
                                            
                                            break;
                                        case 2:
                                            echo "<IMG NAME='a220' SRC='images/solar/2-2.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#2-2'>";
                                            
                                            break;
                                    }
                                    break;
                                case 3:
                                    switch ($_GET['areaID']) {
                                        case 1:
                                            echo "<IMG NAME='a210' SRC='images/solar/3-1.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#3-1'>";
                                            
                                            break;
                                        case 2:
                                            echo "<IMG NAME='a220' SRC='images/solar/3-2.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#3-2'>";
                                            
                                            break;
                                    }
                                    break;
                                case 4:
                                    switch ($_GET['areaID']) {
                                        case 1:
                                            echo "<IMG NAME='a210' SRC='images/solar/4-1.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#4-1'>";
                                            
                                            break;
                                        case 2:
                                            echo "<IMG NAME='a220' SRC='images/solar/4-2.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#4-2'>";
                                            
                                            break;
                                    }
                                    break;
                                case 5:
                                    switch ($_GET['areaID']) {
                                        case 1:
                                            echo "<IMG SRC='images/solar/NP.png'>";
                                            
                                            break;
                                        case 2:
                                            echo "<IMG NAME='a220' SRC='images/solar/5-1.png' WIDTH='400' HEIGHT='400' BORDER='0' USEMAP='#5-1'>";
                                            
                                            break;
                                    }
                                    break;
                                case 6:
                                    
                                    break;
                                case 7:
                                    
                                    break;
                                case 8:
                                    
                                    break;
                                case 9:
                                    
                                    break;
                                case 10:
                                    
                                    break;
                                case 11:
                                    
                                    break;
                                case 12:
                                    switch ($_GET['areaID']) {
                                        case 1:
                                            echo "<IMG NAME='a210' SRC='images/solar/12-1.png' WIDTH='415' HEIGHT='350' BORDER='0' USEMAP='#12-1'>";
                                            
                                            echo "<MAP NAME='12-1'>";
                                            echo "<AREA SHAPE='rect' COORDS='290,74,341,116' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=6' ALT='Saturn'>";
                                            echo "<AREA SHAPE='rect' COORDS='160,149,181,166' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=2' ALT='Venus'>";
                                            echo "<AREA SHAPE='rect' COORDS='225,142,248,164' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=1' ALT='Mercury'>";
                                            echo "<AREA SHAPE='rect' COORDS='251,119,284,152' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=3' ALT='Earth'>";
                                            echo "<AREA SHAPE='rect' COORDS='257,180,278,200' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=4' ALT='Mars'>";
                                            echo "<AREA SHAPE='rect' COORDS='97,194,134,230' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=5' ALT='Jupider'>";
                                            echo "<AREA SHAPE='rect' COORDS='64,233,93,259' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=7' ALT='Uranus'>";
                                            echo "<AREA SHAPE='rect' COORDS='160,278,193,306' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=8' ALT='Neptune'>";
                                            echo "<AREA SHAPE='rect' COORDS='330,288,351,309' HREF='index.php?page=game&gamepage=travel&ssID=1&ss=9' ALT='Pluto'>";
                                            echo "</MAP>";
                                            break;
                                        case 2:
                                            echo "<IMG NAME='a220' SRC='images/solar/12-2.png' WIDTH='315' HEIGHT='250' BORDER='0' USEMAP='#12-2'>";
                                            
                                            echo "<MAP NAME='12-2'>";
                                            echo "<AREA SHAPE='rect' COORDS='242,156,293,202' HREF='index.php?page=game&gamepage=travel&ssID=2&ss=3' ALT='Vorlovan III'>";
                                            echo "<AREA SHAPE='rect' COORDS='202,60,257,96' HREF='index.php?page=game&gamepage=travel&ssID=2&ss=1' ALT='Vorlovan I'>";
                                            echo "<AREA SHAPE='rect' COORDS='45,180,95,221' HREF='index.php?page=game&gamepage=travel&ssID=2&ss=2' ALT='Vorlovan II'>";
                                            echo "</MAP>";
                                            break;
                                        case 3:
                                            echo "<IMG NAME='a230' SRC='images/solar/12-3.png' WIDTH='315' HEIGHT='250' BORDER='0' USEMAP='#12-3'>";
                                            
                                            echo "<MAP NAME='12-3'>";
                                            echo "<AREA SHAPE='rect' COORDS='228,69,271,104' HREF='index.php?page=game&gamepage=travel&ssID=3&ss=3' ALT='Gladius III'>";
                                            echo "<AREA SHAPE='rect' COORDS='224,171,280,229' HREF='index.php?page=game&gamepage=travel&ssID=3&ss=2' ALT='Gladius II'>";
                                            echo "<AREA SHAPE='rect' COORDS='52,80,89,115' HREF='index.php?page=game&gamepage=travel&ssID=3&ss=1' ALT='Gladius Prime'>";
                                            echo "</MAP>";
                                            
                                            break;
                                    }
                                    break;
                            }
                            
                        } elseif (isset($_GET['ssID'])) {
                            $query  = "SELECT * FROM va_users";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "user") == $usr_N_R) {
                                    $galaxyID = mysql_result($result, $i, "Cgalaxy");
                                }
                            }
                            switch ($galaxyID) {
                                case 0:
                                    
                                    break;
                                case 1:
                                    
                                    break;
                                case 2:
                                    
                                    break;
                                case 3:
                                    
                                    break;
                                case 4:
                                    
                                    break;
                                case 5:
                                    
                                    break;
                                case 6:
                                    
                                    break;
                                case 7:
                                    
                                    break;
                                case 8:
                                    
                                    break;
                                case 9:
                                    
                                    break;
                                case 10:
                                    
                                    break;
                                case 11:
                                    
                                    break;
                                case 12:
                                    switch ($_GET['ssID']) {
                                        case 1:
                                            switch ($_GET['ss']) {
                                                case 1:
                                                    echo "Welcome to Mercury!";
                                                    break;
                                                case 2:
                                                    echo "Welcome to Venus!";
                                                    break;
                                                case 3:
                                                    echo "Welcome to Earth!";
                                                    break;
                                                case 4:
                                                    echo "Welcome to Mars!";
                                                    break;
                                                case 5:
                                                    echo "Welcome to Jupider!";
                                                    break;
                                                case 6:
                                                    echo "Welcome to Saturn!";
                                                    break;
                                                case 7:
                                                    echo "Welcome to Uranus!";
                                                    break;
                                                case 8:
                                                    echo "Welcome to Neptune!";
                                                    break;
                                                case 9:
                                                    echo "Welcome to Pluto!";
                                                    break;
                                            }
                                            break;
                                        case 2:
                                            switch ($_GET['ss']) {
                                                case 1:
                                                    echo "Welcome to Vorlovan Prime!";
                                                    break;
                                                case 2:
                                                    echo "Welcome to Vorlovan II!";
                                                    break;
                                                case 3:
                                                    echo "Welcome to Vorlovan III!";
                                                    break;
                                            }
                                            break;
                                        case 3:
                                            switch ($_GET['ss']) {
                                                case 1:
                                                    echo "Welcome to Gladius Prime!";
                                                    break;
                                                case 2:
                                                    echo "Welcome to Gladius II!";
                                                    break;
                                                case 3:
                                                    echo "Welcome to Gladius III!";
                                                    break;
                                            }
                                            break;
                                    }
                                    break;
                            }
                        } else {
                            $query  = "SELECT * FROM va_users";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "user") == $usr_N_R) {
                                    $hID      = mysql_result($result, $i, "galaxy");
                                    $galaxyID = mysql_result($result, $i, "Cgalaxy");
                                }
                            }
                            
                            if ($galaxyID == 0) {
                                $galaxyID = $hID;
                            }
                            
                            switch ($galaxyID) {
                                case 0:
                                    echo "Error...";
                                    break;
                                case 1:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/1a.png' WIDTH='253' HEIGHT='154' BORDER='0' USEMAP='#1a'>";
                                    
                                    echo "<MAP NAME='1a'>";
                                    echo "<AREA SHAPE='rect' COORDS='123,109,177,140' HREF='index.php?page=game&gamepage=travel&areaID=1' ALT='Frais'>";
                                    echo "<AREA SHAPE='rect' COORDS='177,29,215,71' HREF='index.php?page=game&gamepage=travel&areaID=2' ALT='Chaud'>";
                                    echo "<AREA SHAPE='rect' COORDS='79,91,102,114' HREF='index.php?page=game&gamepage=travel&areaID=3' ALT='Froid'>";
                                    echo "</MAP>";
                                    
                                    echo "<hr>";
                                    break;
                                case 2:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/3a.png' WIDTH='312' HEIGHT='208' BORDER='0' USEMAP='#3a'>";
                                    
                                    echo "<MAP NAME='3a'>";
                                    echo "<AREA SHAPE='rect' COORDS='220,145,251,174' HREF='index.php?page=game&gamepage=travel&areaID=1' ALT='Horidi'>";
                                    echo "<AREA SHAPE='rect' COORDS='160,96,198,130' HREF='index.php?page=game&gamepage=travel&areaID=2' ALT='Borok'>";
                                    echo "</MAP>";
                                    
                                    echo "<hr>";
                                    break;
                                case 3:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/5a.png' WIDTH='379' HEIGHT='230' BORDER='0' USEMAP='#5a'>";
                                    
                                    echo "<MAP NAME='5a'>";
                                    echo "<AREA SHAPE='rect' COORDS='151,30,186,59' HREF='index.php?page=game&gamepage=travel&areaID=1' ALT='Histar'>";
                                    echo "<AREA SHAPE='rect' COORDS='229,82,338,187' HREF='index.php?page=game&gamepage=travel&areaID=2' ALT='Hordar'>";
                                    echo "</MAP>";
                                    
                                    echo "<hr>";
                                    break;
                                case 4:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/6a.png' WIDTH='360' HEIGHT='265' BORDER='0' USEMAP='#6a'>";
                                    
                                    echo "<MAP NAME='6a'>";
                                    echo "<AREA SHAPE='rect' COORDS='71,56,132,119' HREF='index.php?page=game&gamepage=travel&areaID=1' ALT='Lardtaz'>";
                                    echo "<AREA SHAPE='rect' COORDS='199,162,300,239' HREF='index.php?page=game&gamepage=travel&areaID=2' ALT='Karotar'>";
                                    echo "</MAP>";
                                    
                                    echo "<hr>";
                                    break;
                                case 5:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/8a.png' WIDTH='315' HEIGHT='244' BORDER='0' USEMAP='#8a'>";
                                    
                                    echo "<MAP NAME='8a'>";
                                    echo "<AREA SHAPE='rect' COORDS='174,60,274,120' HREF='index.php?page=game&gamepage=travel&areaID=2' ALT='Ti-Dii'>";
                                    echo "<AREA SHAPE='rect' COORDS='77,131,138,180' HREF='index.php?page=game&gamepage=travel&areaID=1' ALT='Orvon'>";
                                    echo "</MAP>";
                                    
                                    echo "<hr>";
                                    break;
                                case 6:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/2a.png' WIDTH='478' HEIGHT='307' BORDER='0' USEMAP='#2a'>";
                                    
                                    
                                    
                                    echo "<hr>";
                                    break;
                                case 7:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/11a.png' WIDTH='130' HEIGHT='93' BORDER='0' USEMAP='#11a'>";
                                    
                                    
                                    
                                    echo "<hr>";
                                    break;
                                case 8:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/10a.png' WIDTH='130' HEIGHT='93' BORDER='0' USEMAP='#10a'>";
                                    
                                    
                                    
                                    echo "<hr>";
                                    break;
                                case 9:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/7a.png' WIDTH='331' HEIGHT='301' BORDER='0' USEMAP='#7a'>";
                                    
                                    
                                    
                                    echo "<hr>";
                                    break;
                                case 10:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/12a.png' WIDTH='312' HEIGHT='208' BORDER='0' USEMAP='#12a'>";
                                    
                                    
                                    echo "<hr>";
                                    break;
                                case 11:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/4a.png' WIDTH='346' HEIGHT='295' BORDER='0' USEMAP='#4a'>";
                                    
                                    
                                    echo "<hr>";
                                    break;
                                case 12:
                                    echo "<hr><b>Click on a large star in the galaxy below</b><hr>";
                                    echo "<IMG NAME='ab0' SRC='images/galaxy/9b.png' WIDTH='315' HEIGHT='244' BORDER='0' USEMAP='#9b'>";
                                    
                                    echo "<MAP NAME='9b'>";
                                    echo "<AREA SHAPE='rect' COORDS='148,38,213,98' HREF='index.php?page=game&gamepage=travel&areaID=1' ALT='Earth'>";
                                    echo "<AREA SHAPE='rect' COORDS='84,138,135,186' HREF='index.php?page=game&gamepage=travel&areaID=2' ALT='Vorlovan'>";
                                    echo "<AREA SHAPE='rect' COORDS='197,116,242,160' HREF='index.php?page=game&gamepage=travel&areaID=3' ALT='Gladius'>";
                                    echo "</MAP>";
                                    echo "<hr>";
                                    break;
                            }
                            
                        }
                        break;
                    case 'wh':
                        $id     = $_GET['id'];
                        //Note: This is going to get a bit tough...
                        //I will have to do 5 or more things in this... First lets get the ID.
                        //All querys needed will be listed: We need 3.
                        $query  = "SELECT * FROM va_shops";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $query3  = "SELECT * FROM va_items";
                        $result3 = mysql_query($query3);
                        $num3    = mysql_numrows($result3);
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "id") == $id) {
                                $WHid = mysql_result($result2, $i, "shopid");
                            }
                        }
                        
                        $shopid = $_GET['id'];
                        if (isset($shopid) and $WHid != 0) {
                            //Great there is an ID!
                            //Lets get the shop name and Description first...
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "ownerid") == $shopid) {
                                    //Shop found!
                                    //I will need a loop to get all the info, Like the inventory listed a few cases up...
                                    
                                    $shopname = mysql_result($result, $i, "name");
                                    $shopdesc = mysql_result($result, $i, "description");
                                    
                                    //Ok!  Now lets get the Inventory ID's and the Prices for them...
                                    //This was taken from inventory, though it should work...
                                    for ($i2 = 0; $i2 < 30; $i2++) {
                                        if (mysql_result($result, $i, "inv" . $i2) != 0) {
                                            $itemid[$i2] = mysql_result($result, $i, "inv" . $i2);
                                        }
                                    }
                                    
                                    for ($i2 = 0; $i2 < 30; $i2++) {
                                        if (mysql_result($result, $i, "price" . $i2) != 0) {
                                            $itemprice[$i2] = mysql_result($result, $i, "price" . $i2);
                                        }
                                    }
                                    //Great that was easy, ID's Collected!
                                    
                                    
                                }
                            }
                            
                            //Now to get the Item's Information...
                            for ($i3 = 0; $i3 < $num3; $i3++) {
                                for ($i5 = 0; $i5 < 30; $i5++) {
                                    if (mysql_result($result3, $i3, "id") == $itemid[$i5]) {
                                        $itempic[$i5]  = mysql_result($result3, $i3, "image");
                                        $itemname[$i5] = mysql_result($result3, $i3, "name");
                                        $itemdesc[$i5] = mysql_result($result3, $i3, "description");
                                    }
                                }
                            }
                            
                            //Item Info Collected...
                            //Ok, If I am not wrong everything is collected, lets build the page!
                            
                            //NAME, DESC:
                            echo "<center>";
                            echo "<br><b>" . $shopname . "</b><br>" . $shopdesc;
                            //HORIZONTAL RULE:
                            echo "<br><br>For Sale:<hr>";
                            //INVENTORY:
                            $w = 0;
                            for ($i3 = 0; $i3 < 30; $i3++) {
                                //I am now testing the functions of the Write...
                                if ($itemid[$w] != 0) {
                                    if ($iwriterow > 2) {
                                        echo "<table id='inv'>";
                                        echo "<tr>";
                                        echo "<td width='358'>";
                                        echo "<a href='index.php?page=game&gamepage=buyitem&shopid=" . $id . "&id=" . $w . "'><img src='" . $itempic[$w] . "' border=0></a><br>Item: " . $itemname[$w] . "<br>Description: " . $itemdesc[$w] . "<br>Price: " . $itemprice[$w];
                                        echo "</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                        echo "<br>";
                                        $iwriterow = 0;
                                    } else {
                                        echo "<table id='inv'>";
                                        echo "<tr>";
                                        echo "<td width='358'>";
                                        echo "<a href='index.php?page=game&gamepage=buyitem&shopid=" . $id . "&id=" . $w . "'><img src='" . $itempic[$w] . "' border=0></a><br>Item: " . $itemname[$w] . "<br>Description: " . $itemdesc[$w] . "<br>Price: " . $itemprice[$w];
                                        echo "</td>";
                                        echo "</tr>";
                                        echo "</table>";
                                        $iwriterow++;
                                    }
                                }
                                /*
                                else
                                {
                                echo $itemid[$w] . " = inv" . $w . "<br>";
                                }
                                */
                                $w++;
                                $itemnum++;
                                $itemnuml++;
                            }
                            
                        } else {
                            echo "This person does not have a warehouse!";
                        }
                        break;
                    case 'buyitem':
                        $shopID    = $_GET['shopid'];
                        $shopinvID = $_GET['id'];
                        
                        $query  = "SELECT * FROM va_shops";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $query3  = "SELECT * FROM va_items";
                        $result3 = mysql_query($query3);
                        $num3    = mysql_numrows($result3);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "ownerid") == $shopID) {
                                $RshopID     = mysql_result($result, $i, "id");
                                $itemID      = mysql_result($result, $i, "inv" . $shopinvID);
                                $itemPrice   = mysql_result($result, $i, "price" . $shopinvID);
                                $shopCredits = mysql_result($result, $i, "credits");
                            }
                        }
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                $id      = mysql_result($result2, $i, "id");
                                $credits = mysql_result($result2, $i, "currency");
                                
                                for ($i2 = 0; $i2 < 30; $i2++) {
                                    $invID[$i2] = mysql_result($result2, $i, "inv" . $i2);
                                }
                                
                            }
                            
                            if (mysql_result($result2, $i, "id") == $shopID) {
                                $usertype = mysql_result($result2, $i, "usertype");
                            }
                            
                        }
                        
                        for ($i = 0; $i < $num3; $i++) {
                            if (mysql_result($result3, $i, "id") == $itemID) {
                                $itemN = mysql_result($result3, $i, "name");
                                $itemD = mysql_result($result3, $i, "description");
                            }
                        }
                        
                        //All varibles gathered
                        
                        if ($itemPrice <= $credits) {
                            
                            if ($shopID != $id) {
                                for ($i = 0; $i < 30; $i++) {
                                    if ($invID[$i] == 0) {
                                        $spacefound  = true;
                                        $updatedC    = $credits - $itemPrice;
                                        $updatedTill = $shopCredits + $itemPrice;
                                        
                                        $query = "UPDATE va_users SET inv" . $i . "='" . $itemID . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to add item!");
                                        
                                        $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to subtract credits!");
                                        
                                        if ($usertype == 0) {
                                            $query = "UPDATE va_shops SET inv" . $shopinvID . "='0' WHERE id='" . $RshopID . "'";
                                            mysql_query($query) or die("Unable to delete item P1!");
                                            
                                            $query = "UPDATE va_shops SET price" . $shopinvID . "='0' WHERE id='" . $RshopID . "'";
                                            mysql_query($query) or die("Unable to delete item P2!");
                                        }
                                        
                                        $query = "UPDATE va_shops SET credits='" . $updatedTill . "' WHERE id='" . $RshopID . "'";
                                        mysql_query($query) or die("Unable to add credits!");
                                        
                                        echo "<hr>You bought: \"" . $itemN . "\" for " . $itemPrice . " credits!<hr>";
                                        break;
                                    }
                                }
                            } else {
                                echo "<hr>You cant buy your own item!<hr>";
                                $spacefound = true;
                            }
                            
                            if ($spacefound != true) {
                                echo "<hr>Your inventory is full!<hr>";
                            }
                            
                        } else {
                            echo "<hr>You do not have enough credits!<hr>";
                        }
                        
                        break;
                    case 'iteminfo':
                        $invID  = $_GET['id'];
                        $query  = "SELECT * FROM va_shops";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $query3  = "SELECT * FROM va_items";
                        $result3 = mysql_query($query3);
                        $num3    = mysql_numrows($result3);
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                $itemID = mysql_result($result2, $i, "inv" . $invID);
                                $shopID = mysql_result($result2, $i, "shopid");
                            }
                        }
                        
                        for ($i = 0; $i < $num3; $i++) {
                            if (mysql_result($result3, $i, "id") == $itemID) {
                                $itemI = mysql_result($result3, $i, "image");
                                $itemN = mysql_result($result3, $i, "name");
                                $itemD = mysql_result($result3, $i, "description");
                                $itemT = mysql_result($result3, $i, "type");
                                $itemA = mysql_result($result3, $i, "AP");
                            }
                        }
                        
                        if ($itemT == 1) {
                            $tellW = "<br>Base Damage: " . $itemA;
                        }
                        if ($itemT == 2 or $itemT == 3 or $itemT == 4 or $itemT == 5 or $itemT == 10) {
                            $tellW = "<br>Armor Class: " . $itemA;
                        }
                        
                        echo "<img src='" . $itemI . "'><br>" . $itemN . "<br>" . $itemD . $tellW . "<hr>";
                        if ($itemT == 6) {
                            echo "<a href='index.php?page=game&gamepage=useitem&id=" . $invID . "'>Read Book</a><br>";
                        } else {
                            echo "<a href='index.php?page=game&gamepage=useitem&id=" . $invID . "'>Use Item</a><br>";
                        }
                        if ($shopID != 0) {
                            echo "<a href='index.php?page=game&gamepage=sellitem&id=" . $invID . "'>Sell Item</a><br>";
                        }
                        echo "<a href='index.php?page=game&gamepage=throwaway&id=" . $invID . "'>Throw Item Away</a><br>";
                        
                        echo "<a href='index.php?page=game&gamepage=donitem&id=" . $invID . "'>Donate Item</a><br>";
                        
                        echo "<a href='index.php?page=game&gamepage=addF&id=" . $invID . "'>Add to Factory</a><br>";
                        
                        echo "<a href='index.php?page=game&gamepage=addB&id=" . $invID . "'>Add to Bank</a><br>";
                        echo "<hr>";
                        break;
                    case 'sellF':
                        echo "<hr>Selling item Failed!";
                        break;
                    case 'sellS':
                        echo "<hr>Selling item Successful!";
                        break;
                    case 'sellitem':
                        $query  = "SELECT * FROM va_shops";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $query3  = "SELECT * FROM va_items";
                        $result3 = mysql_query($query3);
                        $num3    = mysql_numrows($result3);
                        
                        $invID = $_GET['id'];
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                $itemID = mysql_result($result2, $i, "inv" . $invID);
                                $shopID = mysql_result($result2, $i, "shopid");
                            }
                        }
                        if ($shopID != 0) {
?>
<form action="sellitem.php" method="post">
Price: <input type="text" name="price"><br>
<input type="hidden" name="invID"
<?
                            print("value='" . $_GET['id'] . "'");
?>
>
<br>
<button type="submit">Sell</button>
</form>
<?
                        } else {
                            echo "You dont have a warehouse!";
                        }
                        break;
                    case 'throwaway':
                        $invID = $_GET['id'];
                        echo "<hr>Are you sure you want to throw this item away?<br>[<a href='index.php?page=game&gamepage=throwawayC&id=" . $invID . "'>Yes</a> | <a href='index.php?page=game&gamepage=stats'>No</a>]<hr>";
                        break;
                    case 'throwawayC':
                        $invID = $_GET['id'];
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                $id = mysql_result($result2, $i, "id");
                            }
                        }
                        
                        $query = "UPDATE va_users SET inv" . $invID . "='0' WHERE id='" . $id . "'";
                        mysql_query($query) or die("Unable to delete item!");
                        echo "<hr>Threw item away successfully!<hr>";
                        break;
                    case 'store':
                        $query  = "SELECT * FROM va_shops";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $query3  = "SELECT * FROM va_items";
                        $result3 = mysql_query($query3);
                        $num3    = mysql_numrows($result3);
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                $shopID  = mysql_result($result2, $i, "shopid");
                                $id      = mysql_result($result2, $i, "id");
                                $credits = mysql_result($result2, $i, "currency");
                                $name    = mysql_result($result2, $i, "user");
                            }
                        }
                        
                        
                        
                        if (isset($_GET['wtd'])) {
                            switch ($_GET['wtd']) {
                                case 'buyWH':
                                    if ($credits >= 2000) {
                                        
                                        for ($i = 1; $i < 9999999; $i++) {
                                            for ($i2 = 0; $i2 < $num1; $i2++) {
                                                if (mysql_result($result, $i2, "id") == $i) {
                                                    $istakenMB++;
                                                }
                                            }
                                            if ($istakenMB < 1) {
                                                $WHID     = $i;
                                                $updatedC = $credits - 2000;
                                                $query    = "INSERT INTO va_shops VALUES ('" . $WHID . "','" . $name . "s Shop','Welcome!','" . $id . "','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0')";
                                                mysql_query($query) or die("Unable to create shop.");
                                                
                                                $query = "UPDATE va_users SET shopid='" . $WHID . "' WHERE id='" . $id . "'";
                                                mysql_query($query) or die("Unable to Add Shop!");
                                                
                                                $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $id . "'";
                                                mysql_query($query) or die("Unable to edit credits!");
                                                echo "Shop Created!";
                                                break;
                                            } else {
                                                $istakenMB = 0;
                                            }
                                        }
                                    }
                                    break;
                                case 'withdraw':
                                    for ($i = 0; $i < $num1; $i++) {
                                        if (mysql_result($result, $i, "id") == $shopID) {
                                            $withdraw = mysql_result($result, $i, "credits");
                                        }
                                    }
                                    $gainC25  = floor($withdraw * 0.25);
                                    $gainC50  = floor($withdraw * 0.5);
                                    $gainC75  = floor($withdraw * 0.75);
                                    $gainC100 = floor($withdraw);
                                    
                                    echo "<hr>There are " . $withdraw . " credits in the Warehouse's Register.<br>Withdraw some?<br>";
                                    echo "<br><a href='index.php?page=game&gamepage=store&wtd=withD&amount=" . $gainC25 . "'>25% - (" . $gainC25 . ")</a>";
                                    echo "<br><a href='index.php?page=game&gamepage=store&wtd=withD&amount=" . $gainC50 . "'>50% - (" . $gainC50 . ")</a>";
                                    echo "<br><a href='index.php?page=game&gamepage=store&wtd=withD&amount=" . $gainC75 . "'>75% - (" . $gainC75 . ")</a>";
                                    echo "<br><a href='index.php?page=game&gamepage=store&wtd=withD&amount=" . $gainC100 . "'>100% - (" . $gainC100 . ")</a><hr>";
                                    break;
                                case 'withD':
                                    $amount = $_GET['amount'];
                                    for ($i = 0; $i < $num1; $i++) {
                                        if (mysql_result($result, $i, "id") == $shopID) {
                                            $withdraw = mysql_result($result, $i, "credits");
                                        }
                                    }
                                    if ($withdraw >= $amount) {
                                        $updatedC = $credits + $amount;
                                        $updatedT = $withdraw - $amount;
                                        
                                        $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $id . "'";
                                        mysql_query($query) or die("Unable to edit credits P1!");
                                        
                                        $query = "UPDATE va_shops SET credits='" . $updatedT . "' WHERE id='" . $shopID . "'";
                                        mysql_query($query) or die("Unable to edit credits P2!");
                                        
                                        echo "<hr>You withdrew " . $amount . " Credits from your Warehouse's Register!";
                                    } else {
                                        echo "<hr>There isn't that much in your warehouse's register!";
                                    }
                                    break;
                                case 'S':
                                    echo "<hr>Succeeded!";
                                    break;
                                case 'F':
                                    echo "<hr>Failed!";
                                    break;
                                case 'editINV':
                                    $w = 0;
                                    for ($i = 0; $i < $num1; $i++) {
                                        if (mysql_result($result, $i, "id") == $shopID) {
                                            for ($i2 = 0; $i2 < 30; $i2++) {
                                                if (mysql_result($result, $i, "inv" . $i2) != 0) {
                                                    $itemID[$w]  = mysql_result($result, $i, "inv" . $i2);
                                                    $price[$w]   = mysql_result($result, $i, "price" . $i2);
                                                    $invID2a[$w] = $i2;
                                                    $w++;
                                                    $maxI++;
                                                }
                                            }
                                        }
                                    }
                                    
                                    for ($i = 0; $i < $num3; $i++) {
                                        for ($i2 = 0; $i2 < $maxI; $i2++) {
                                            
                                            if (mysql_result($result3, $i, "id") == $itemID[$i2]) {
                                                $itemN[$i2] = mysql_result($result3, $i, "name");
                                                $itemI[$i2] = mysql_result($result3, $i, "image");
                                            }
                                            
                                        }
                                    }
?>
<hr>
Shop Inventory
<hr>
<form action="editshopINV.php" method="post">
<table border="0" width="100%">
<tr>
<td width="25%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Image</font></small></td>
<td width="25%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Name</font></small></td>
<td width="45%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Price</font></small></td>
<td width="45%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Remove</font></small></td>
</tr>
<?
                                    for ($i = 0; $i < $maxI; $i++) {
                                        if ($color == "#636363") {
                                            $color = "#808080";
                                        } else {
                                            $color = "#636363";
                                        }
                                        echo "<tr><td bgcolor=\"$color\"><center><small>";
                                        echo "<font face=\"Verdana\"><img src='" . $itemI[$i] . "' border=2></font></small></center></td>";
                                        echo "<td bgcolor=\"$color\"><center><small>";
                                        echo "<font face=\"Verdana\">" . $itemN[$i] . "</font></small></center></td>";
                                        echo "<td bgcolor=\"$color\"><center><small>";
                                        echo "<font face=\"Verdana\"><input type='hidden' name='itemID" . $i . "' value='" . $itemID[$i] . "' /><input type='hidden' name='invN" . $i . "' value='" . $invID2a[$i] . "' /><input type='text' name='inv" . $i . "' value='" . $price[$i] . "' /></font></small></center></td>";
                                        echo "<td bgcolor=\"$color\"><center><small>";
                                        echo "<font face=\"Verdana\"><input type='checkbox' name='invR" . $i . "'></font></small></center></td></tr>";
                                    }
                                    if ($color == "#636363") {
                                        $color = "#808080";
                                    } else {
                                        $color = "#636363";
                                    }
                                    echo "</tr><tr><td colspan=\"4\" bgcolor=\"$color\">";
?><center>
<button type="submit">Edit Inventory</button>
</center>
</td></tr>
</table>
</form>
<?
                                    
                                    
                                    break;
                                case 'editND':
                                    for ($i = 0; $i < $num1; $i++) {
                                        if (mysql_result($result, $i, "id") == $shopID) {
                                            $name = mysql_result($result, $i, "name");
                                            $desc = mysql_result($result, $i, "description");
                                        }
                                    }
?>
<hr>
<form action="editND.php" method="post">
Name: <input type="text" name="name"
<?
                                    print("value='" . $name . "'");
?>
><br />
Description<hr> <textarea cols="30" rows="15" name="desc">
<?
                                    print($desc);
?>
</textarea><hr>
<br />
<br />
<button type="submit">Edit Name & Desciption</button>
</form>
<hr>
<?
                                    break;
                            }
                        } else {
                            if ($shopID == 0) {
                                echo "<hr><a href='index.php?page=game&gamepage=store&wtd=buyWH'>Click here to buy a warehouse! (Price: 2000 Credits)</a><hr>";
                            } else {
                                echo "<hr><a href='index.php?page=game&gamepage=wh&id=" . $id . "'>Click here to visit your warehouse!</a><br><a href='index.php?page=game&gamepage=store&wtd=withdraw'>Click here to withdraw from your warehouse's register!</a><br><a href='index.php?page=game&gamepage=store&wtd=editND'>Edit Shop Name & Shop Description</a><br><a href='index.php?page=game&gamepage=store&wtd=editINV'>Edit Shop Inventory</a><hr>";
                            }
                        }
                        break;
                    
                    case 'mbFull':
                        echo "<hr>Sorry that person's mailbox is full!<hr>";
                        break;
                    case 'inbox':
                        if (isset($_GET['mail'])) {
                            $query  = "SELECT * FROM va_mailbox";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            $query2  = "SELECT * FROM va_users";
                            $result2 = mysql_query($query2);
                            $num2    = mysql_numrows($result2);
                            
                            $mailid = $_GET['mail'];
                            
                            for ($i = 0; $i < $num2; $i++) {
                                if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                    $MBid = mysql_result($result2, $i, "mailbox");
                                }
                            }
                            
                            for ($i = 0; $i < $num1; $i++) {
                                if (mysql_result($result, $i, "id") == $MBid) {
                                    //Mailbox Selected
                                    $MBSubject = mysql_result($result, $i, "mbS" . $mailid);
                                    $MBMessage = mysql_result($result, $i, "mbM" . $mailid);
                                    $MBSender  = mysql_result($result, $i, "mbF" . $mailid);
                                    $MBStatus  = mysql_result($result, $i, "mbT" . $mailid);
                                }
                            }
                            $MBMessage = str_replace("\n", "<br>", $MBMessage);
                            $MBMessage = str_replace("[b]", "<b>", $MBMessage);
                            $MBMessage = str_replace("[/b]", "</b>", $MBMessage);
                            $MBMessage = str_replace("[u]", "<u>", $MBMessage);
                            $MBMessage = str_replace("[/u]", "</u>", $MBMessage);
                            $MBMessage = str_replace("[i]", "<i>", $MBMessage);
                            $MBMessage = str_replace("[/i]", "</i>", $MBMessage);
                            $MBMessage = str_replace("[b]", "<b>", $MBMessage);
                            $MBMessage = str_replace("[font", "<font", $MBMessage);
                            $MBMessage = str_replace("[/font]", "</font>", $MBMessage);
                            $MBMessage = str_replace(":(", "<img src='images/smiles/icon_sad.gif'>", $MBMessage);
                            $MBMessage = str_replace(":)", "<img src='images/smiles/icon_smile.gif'>", $MBMessage);
                            $MBMessage = str_replace(":D", "<img src='images/smiles/icon_biggrin.gif'>", $MBMessage);
                            $MBMessage = str_replace(":oops:", "<img src='images/smiles/icon_redface.gif'>", $MBMessage);
                            $MBMessage = str_replace(":o", "<img src='images/smiles/icon_surprised.gif'>", $MBMessage);
                            $MBMessage = str_replace(":shock:", "<img src='images/smiles/icon_eek.gif'>", $MBMessage);
                            $MBMessage = str_replace(":?:", "<img src='images/smiles/icon_question.gif'>", $MBMessage);
                            $MBMessage = str_replace(":?", "<img src='images/smiles/icon_confused.gif'>", $MBMessage);
                            $MBMessage = str_replace("8)", "<img src='images/smiles/icon_cool.gif'>", $MBMessage);
                            $MBMessage = str_replace(":lol:", "<img src='images/smiles/icon_lol.gif'>", $MBMessage);
                            $MBMessage = str_replace(":x", "<img src='images/smiles/icon_mad.gif'>", $MBMessage);
                            $MBMessage = str_replace(":P", "<img src='images/smiles/icon_razz.gif'>", $MBMessage);
                            $MBMessage = str_replace(":cry:", "<img src='images/smiles/icon_cry.gif'>", $MBMessage);
                            $MBMessage = str_replace(":evil:", "<img src='images/smiles/icon_evil.gif'>", $MBMessage);
                            $MBMessage = str_replace(":twisted:", "<img src='images/smiles/icon_twisted.gif'>", $MBMessage);
                            $MBMessage = str_replace(":roll:", "<img src='images/smiles/icon_rolleyes.gif'>", $MBMessage);
                            $MBMessage = str_replace(":wink:", "<img src='images/smiles/icon_wink.gif'>", $MBMessage);
                            $MBMessage = str_replace(":!:", "<img src='images/smiles/icon_exclaim.gif'>", $MBMessage);
                            $MBMessage = str_replace(":?:", "<img src='images/smiles/icon_question.gif'>", $MBMessage);
                            
                            
                            //MUST BE LAST! vvv
                            $MBMessage = str_replace("]", ">", $MBMessage);
                            //MUST BE LAST! ^^^
                            
                            if ($MBStatus == "Unread") {
                                //Make Read...
                                $query = "UPDATE va_mailbox SET mbT" . $mailid . "='Read' WHERE id='" . $MBid . "'";
                                mysql_query($query) or die("Unable to mark as READ.");
                            }
                            
                            echo "<table border='2' cellpadding='0' cellspacing='0' width='95%'>";
                            echo "<tr>";
                            echo "<td id='top'><center>Subject: " . $MBSubject . "</center></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td id='top'><span style='color: #00ff00'>" . $MBMessage . "</span></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td id='top'><center>From: " . $MBSender . "</center></td>";
                            echo "</tr>";
                            echo "<tr>";
                            echo "<td id='top'><center>Options<br><a href='index.php?page=game&gamepage=inbox&del=" . $mailid . "'>Delete</a> &#8226; <a href='index.php?page=game&gamepage=sendmail&totext=" . $MBSender . "&subject=" . $MBSubject . "'>Reply</a> &#8226; <a href='index.php?page=game&gamepage=inbox&markau=" . $mailid . "'>Mark As Unread</a></center></td>";
                            echo "</tr>";
                            echo "</table>";
                            
                        } elseif (isset($_GET['del'])) {
                            
                            $query  = "SELECT * FROM va_mailbox";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            $query2  = "SELECT * FROM va_users";
                            $result2 = mysql_query($query2);
                            $num2    = mysql_numrows($result2);
                            
                            $mailid = $_GET['del'];
                            
                            for ($i = 0; $i < $num2; $i++) {
                                if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                    $MBid = mysql_result($result2, $i, "mailbox");
                                }
                            }
                            
                            //Delete!
                            $query = "UPDATE va_mailbox SET mbS" . $mailid . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            $query = "UPDATE va_mailbox SET mbM" . $mailid . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            $query = "UPDATE va_mailbox SET mbF" . $mailid . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            $query = "UPDATE va_mailbox SET mbT" . $mailid . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            echo "<hr>Mail successfully deleted!<hr>";
                            
                        } elseif (isset($_GET['markau'])) {
                            
                            $query2  = "SELECT * FROM va_users";
                            $result2 = mysql_query($query2);
                            $num2    = mysql_numrows($result2);
                            
                            $mailid = $_GET['markau'];
                            
                            for ($i = 0; $i < $num2; $i++) {
                                if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                    $MBid = mysql_result($result2, $i, "mailbox");
                                }
                            }
                            
                            //Make Unread...
                            $query = "UPDATE va_mailbox SET mbT" . $mailid . "='Unread' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to mark as UNREAD.");
                            
                            echo "<hr>Mail successfully marked!<hr>";
                            
                        } else {
                            $query  = "SELECT * FROM va_mailbox";
                            $result = mysql_query($query);
                            $num1   = mysql_numrows($result);
                            
                            $query2  = "SELECT * FROM va_users";
                            $result2 = mysql_query($query2);
                            $num2    = mysql_numrows($result2);
                            
                            for ($i = 0; $i < $num2; $i++) {
                                if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                    $MBid = mysql_result($result2, $i, "mailbox");
                                }
                            }
                            
                            for ($i2 = 0; $i2 < $num1; $i2++) {
                                if (mysql_result($result, $i2, "id") == $MBid) {
                                    
                                    for ($i = 29; $i >= 0; $i -= 1) {
                                        if (mysql_result($result, $i2, "mbT" . $i) != "") {
                                            $hasmail = true;
                                            $subject = mysql_result($result, $i2, "mbS" . $i);
                                            $status  = mysql_result($result, $i2, "mbT" . $i);
                                            $messages++;
                                            
                                            if ($subject == "") {
                                                $subject = "<b><i>No Subject</i></b>";
                                            }
                                            
                                            if ($status == "Unread") {
                                                $mailbox .= "<hr> <span style='color: #00ff00'><b>" . $status . "</b>: <a href='index.php?page=game&gamepage=inbox&mail=" . $i . "'><span style='color: #00ff00'>" . $subject . "</span></a></span>";
                                            } else {
                                                $mailbox .= "<hr> <span style='color: #ff0000'>" . $status . ": <a href='index.php?page=game&gamepage=inbox&mail=" . $i . "'><span style='color: #ff0000'>" . $subject . "</span></a></span>";
                                            }
                                            
                                        }
                                    }
                                    
                                }
                            }
                            
                            if ($hasmail != true) {
                                echo "<br><b>Inbox</b><hr><center><a href='index.php?page=game&gamepage=sendmail'>Send Mail</a> &#8226; <a href='index.php?page=game&gamepage=clearmail'>Clear Mailbox</a>";
                                echo "<hr>You have no mail!";
                            } else {
                                echo "<br><b>Inbox</b><hr><center><a href='index.php?page=game&gamepage=sendmail'>Send Mail</a> &#8226; <a href='index.php?page=game&gamepage=clearmail'>Clear Mailbox</a>";
                                if ($messages == 1) {
                                    echo "<br>There is (" . $messages . "/30) Messages in your Mailbox";
                                } else {
                                    echo "<br>There are (" . $messages . "/30) Messages in your Mailbox";
                                }
                                echo "<div align='left'>";
                                echo $mailbox;
                                echo "</div>";
                            }
                            
                        }
                        break;
                    case 'logout':
                        echo "<hr>Are you sure you want to logout?<br>[<a href='logout.php'>Yes</a> | <a href='index.php?page=game'>No</a>]";
                        break;
                    case 'mailHTML':
                        echo "<hr>Your message cannot contain HTML!<br>Please remove tags (< and >)!<hr>";
                        break;
                    case 'clearmail':
                        
                        $query  = "SELECT * FROM va_mailbox";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        $mailid = $_GET['del'];
                        
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                $MBid = mysql_result($result2, $i, "mailbox");
                            }
                        }
                        
                        for ($i = 0; $i < 30; $i++) {
                            //Delete!
                            $query = "UPDATE va_mailbox SET mbS" . $i . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            $query = "UPDATE va_mailbox SET mbM" . $i . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            $query = "UPDATE va_mailbox SET mbF" . $i . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                            
                            $query = "UPDATE va_mailbox SET mbT" . $i . "='' WHERE id='" . $MBid . "'";
                            mysql_query($query) or die("Unable to delete!");
                        }
                        
                        echo "<hr>Mail successfully deleted!<hr>";
                        
                        break;
                    case 'mailUNF':
                        echo "<hr>User not found!<hr>";
                        break;
                    case 'sendmail':
?>
<form action="sendmail.php" method="post" name="sendmail">
<center>
<br>
<table border=2 cellspacing=0 cellpadding=0 width=95%>
<tr>
<td colspan="2" id='top'>
<center>
To: <input type="text" name="to"
<?
                        if (isset($_GET['totext'])) {
                            echo "value='" . $_GET['totext'] . "'";
                        } else {
                            echo "";
                        }
?>
><br><br>
Subject: <input type="text" name="subject"
<?
                        if (isset($_GET['subject'])) {
                            $subject = str_replace("%20", " ", $_GET['subject']);
                            echo "value='re: " . $subject . "'";
                        } else {
                            echo "";
                        }
?>
>
</center>
</td>
</tr>
<tr>
<td width="33.5%" id='top'>
<center>
Emoticons
</td>
<td width="66.5%" id='top'>
<center>
Message<br>
</td>
</tr>
<tr>
<td width="33.5%" id='top'>
<a href="javascript:emoticon(':)');"><img src='images/smiles/icon_smile.gif' border="0"></a>
<a href="javascript:emoticon(':(');"><img src='images/smiles/icon_sad.gif' border="0"></a>
<a href="javascript:emoticon(':D');"><img src='images/smiles/icon_biggrin.gif' border="0"></a>
<a href="javascript:emoticon(':o');"><img src='images/smiles/icon_surprised.gif' border="0"></a><br>
<a href="javascript:emoticon(':shock:');"><img src='images/smiles/icon_eek.gif' border="0"></a>
<a href="javascript:emoticon(':?')"><img src='images/smiles/icon_confused.gif' border="0"></a>
<a href="javascript:emoticon('8)')"><img src='images/smiles/icon_cool.gif' border="0"></a>
<a href="javascript:emoticon(':lol:')"><img src='images/smiles/icon_lol.gif' border="0"></a><br>
<a href="javascript:emoticon(':x')"><img src='images/smiles/icon_mad.gif' border="0"></a>
<a href="javascript:emoticon(':P')"><img src='images/smiles/icon_razz.gif' border="0"></a>
<a href="javascript:emoticon(':oops:')"><img src='images/smiles/icon_redface.gif' border="0"></a>
<a href="javascript:emoticon(':cry:')"><img src='images/smiles/icon_cry.gif' border="0"></a><br>
<a href="javascript:emoticon(':evil:')"><img src='images/smiles/icon_evil.gif' border="0"></a>
<a href="javascript:emoticon(':twisted:')"><img src='images/smiles/icon_twisted.gif' border="0"></a>
<a href="javascript:emoticon(':roll:')"><img src='images/smiles/icon_rolleyes.gif' border="0"></a>
<a href="javascript:emoticon(':wink:')"><img src='images/smiles/icon_wink.gif' border="0"></a><br>
<a href="javascript:emoticon(':!:')"><img src='images/smiles/icon_exclaim.gif' border="0"></a>
<a href="javascript:emoticon(':?:')"><img src='images/smiles/icon_question.gif' border="0"></a>
</center>
</td>
<td width="33.5%" id='top'>
<textarea cols="50" rows="10" name="message"></textarea>
</center>
</td>
</tr>
<tr>
<td colspan="2" id='top'>
<center>
<button type="submit">Send</button> <button type="reset">Reset</button>
</center>
</td>
</tr>
</table>
</center>
</form>
<?
                        break;
                    case 'donS':
                        echo "<hr>Donation Successful!</hr>";
                        break;
                    case 'donF':
                        echo "<hr>Donation Failed!</hr>";
                        break;
                    case 'donC':
?>
<center>
<form action="donatecredits.php" method="post">
Amount: <input type="text" name="amount" value="0"><br><br>
<hr>Message <br> <textarea cols="50" rows="15" name="message">Type your message here!</textarea><hr>
<input type="hidden" name="id"
<?
                        echo "value='" . $_GET['id'] . "'>";
?>
<br>
<button type="submit">Give Credits</button>
</form>
</center>
<?
                        break;
                    case 'chat':
                        
                        $query  = "SELECT * FROM va_items";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        $i2     = 1;
                        for ($i = 0; $i < $num1; $i++) {
                            switch ($i2) {
                                case 1:
                                    if (mysql_result($result, $i, "id") == 1) {
                                        $line1n = mysql_result($result, $i, "name");
                                        $line1m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 2:
                                    if (mysql_result($result, $i, "id") == 2) {
                                        $line2n = mysql_result($result, $i, "name");
                                        $line2m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 3:
                                    if (mysql_result($result, $i, "id") == 3) {
                                        $line3n = mysql_result($result, $i, "name");
                                        $line3m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 4:
                                    if (mysql_result($result, $i, "id") == 4) {
                                        $line4n = mysql_result($result, $i, "name");
                                        $line4m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 5:
                                    if (mysql_result($result, $i, "id") == 5) {
                                        $line5n = mysql_result($result, $i, "name");
                                        $line5m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 6:
                                    if (mysql_result($result, $i, "id") == 6) {
                                        $line6n = mysql_result($result, $i, "name");
                                        $line6m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 7:
                                    if (mysql_result($result, $i, "id") == 7) {
                                        $line7n = mysql_result($result, $i, "name");
                                        $line7m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 8:
                                    if (mysql_result($result, $i, "id") == 8) {
                                        $line8n = mysql_result($result, $i, "name");
                                        $line8m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 9:
                                    if (mysql_result($result, $i, "id") == 9) {
                                        $line9n = mysql_result($result, $i, "name");
                                        $line9m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 10:
                                    if (mysql_result($result, $i, "id") == 10) {
                                        $line10n = mysql_result($result, $i, "name");
                                        $line10m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 11:
                                    if (mysql_result($result, $i, "id") == 11) {
                                        $line11n = mysql_result($result, $i, "name");
                                        $line11m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 12:
                                    if (mysql_result($result, $i, "id") == 12) {
                                        $line12n = mysql_result($result, $i, "name");
                                        $line12m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 13:
                                    if (mysql_result($result, $i, "id") == 13) {
                                        $line13n = mysql_result($result, $i, "name");
                                        $line13m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 14:
                                    if (mysql_result($result, $i, "id") == 14) {
                                        $line14n = mysql_result($result, $i, "name");
                                        $line14m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 15:
                                    if (mysql_result($result, $i, "id") == 15) {
                                        $line15n = mysql_result($result, $i, "name");
                                        $line15m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                                case 16:
                                    if (mysql_result($result, $i, "id") == 16) {
                                        $line16n = mysql_result($result, $i, "name");
                                        $line16m = mysql_result($result, $i, "message");
                                        $i2++;
                                    }
                                    break;
                            }
                        }
                        /*
                        $line1n = mysql_result($result,1,"name");
                        $line2n = mysql_result($result,2,"name");
                        $line3n = mysql_result($result,3,"name");
                        $line4n = mysql_result($result,4,"name");
                        $line5n = mysql_result($result,5,"name");
                        $line6n = mysql_result($result,6,"name");
                        $line7n = mysql_result($result,7,"name");
                        $line8n = mysql_result($result,8,"name");
                        $line9n = mysql_result($result,9,"name");
                        $line10n = mysql_result($result,10,"name");
                        $line11n = mysql_result($result,11,"name");
                        $line12n = mysql_result($result,12,"name");
                        $line13n = mysql_result($result,13,"name");
                        $line14n = mysql_result($result,14,"name");
                        $line15n = mysql_result($result,15,"name");
                        $line16n = mysql_result($result,16,"name");
                        $line1m = mysql_result($result,1,"message");
                        $line2m = mysql_result($result,2,"message");
                        $line3m = mysql_result($result,3,"message");
                        $line4m = mysql_result($result,4,"message");
                        $line5m = mysql_result($result,5,"message");
                        $line6m = mysql_result($result,6,"message");
                        $line7m = mysql_result($result,7,"message");
                        $line8m = mysql_result($result,8,"message");
                        $line9m = mysql_result($result,9,"message");
                        $line10m = mysql_result($result,10,"message");
                        $line11m = mysql_result($result,11,"message");
                        $line12m = mysql_result($result,12,"message");
                        $line13m = mysql_result($result,13,"message");
                        $line14m = mysql_result($result,14,"message");
                        $line15m = mysql_result($result,15,"message");
                        $line16m = mysql_result($result,16,"message");
                        */
                        $i = 1;
                        echo "<table bordercolor=green border=1 width=90% height=272 cellpadding='0' cellspacing='0'>";
                        echo "<tr>";
                        echo "<td id='top2' height=256>";
                        //begin Chat
                        while ($i < 17) {
                            switch ($i) {
                                case 1:
                                    if ($line1m != "") {
                                        echo $line1n . ": " . $line1m;
                                    }
                                    break;
                                case 2:
                                    if ($line2m != "") {
                                        echo $line2n . ": " . $line2m;
                                    }
                                    break;
                                case 3:
                                    if ($line3m != "") {
                                        echo $line3n . ": " . $line3m;
                                    }
                                    break;
                                case 4:
                                    if ($line4m != "") {
                                        echo $line4n . ": " . $line4m;
                                    }
                                    break;
                                case 5:
                                    if ($line5m != "") {
                                        echo $line5n . ": " . $line5m;
                                    }
                                    break;
                                case 6:
                                    if ($line6m != "") {
                                        echo $line6n . ": " . $line6m;
                                    }
                                    break;
                                case 7:
                                    if ($line7m != "") {
                                        echo $line7n . ": " . $line7m;
                                    }
                                    break;
                                case 8:
                                    if ($line8m != "") {
                                        echo $line8n . ": " . $line8m;
                                    }
                                    break;
                                case 9:
                                    if ($line9m != "") {
                                        echo $line9n . ": " . $line9m;
                                    }
                                    break;
                                case 10:
                                    if ($line10m != "") {
                                        echo $line10n . ": " . $line10m;
                                    }
                                    break;
                                case 11:
                                    if ($line11m != "") {
                                        echo $line11n . ": " . $line11m;
                                    }
                                    break;
                                case 12:
                                    if ($line12m != "") {
                                        echo $line12n . ": " . $line12m;
                                    }
                                    break;
                                case 13:
                                    if ($line13m != "") {
                                        echo $line13n . ": " . $line13m;
                                    }
                                    break;
                                case 14:
                                    if ($line15m != "") {
                                        echo $line15n . ": " . $line15m;
                                    }
                                    break;
                                case 15:
                                    if ($line15m != "") {
                                        echo $line15n . ": " . $line15m;
                                    }
                                    break;
                                case 16:
                                    if ($line16m != "") {
                                        echo $line16n . ": " . $line16m;
                                    }
                                    break;
                            }
                            $i++;
                        }
                        //end chat
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr>";
                        
                        if (isset($_GET['chat'])) {
                            if ($_GET['chat'] != "") {
                                $chatvalue = $_GET['chat'];
                            }
                        } else {
                            $chatvalue = "";
                        }
                        
                        
                        echo "<td height=16>";
                        echo "<form action='chat.php' method='post' name='chatform'>";
                        echo "<center><input type='text' size='100%' name='chat' value='$chatvalue'><br><button type='submit'>Chat!</button></center></form>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        
                        redirect("index.php?page=game&gamepage=chat&chat=$chatvalue");
                        
                        break;
                    case 'bounties':
                        $query2  = "SELECT * FROM va_bounty";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        $bountys = 0;
                        for ($i = 0; $i < $num2; $i++) {
                            if (mysql_result($result2, $i, "id") != "") {
                                $bID[$i]     = mysql_result($result2, $i, "id");
                                $sendID[$i]  = mysql_result($result2, $i, "sendID");
                                $receID[$i]  = mysql_result($result2, $i, "reciveID");
                                $bountyA[$i] = mysql_result($result2, $i, "bounty");
                                $bountys++;
                            }
                        }
                        
                        $max     = $bountys + 1;
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        for ($i = 0; $i < $num2; $i++) {
                            
                            for ($i2 = 0; $i2 < $bountys; $i2++) {
                                
                                if (mysql_result($result2, $i, "id") == $sendID[$i2]) {
                                    $sendN[$i2] = mysql_result($result2, $i, "user");
                                }
                                
                                if (mysql_result($result2, $i, "id") == $receID[$i2]) {
                                    $receN[$i2] = mysql_result($result2, $i, "user");
                                }
                                
                            }
                            
                        }
                        
                        $max = $bountys + 1;
                        $i2  = 0;
                        for ($i = 1; $i < $max; $i++) {
                            
                            $bountyT .= "</tr><tr>";
                            if ($color == "#636363") {
                                $color = "#a9a9a9";
                            } else {
                                $color = "#636363";
                            }
                            
                            $bountyT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                            $bountyT .= "<font face=\"Verdana\">" . $i . "</font></small></center></td>";
                            $bountyT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                            $bountyT .= "<font face=\"Verdana\">" . $receN[$i2] . "</font></small></center></td>";
                            $bountyT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                            $bountyT .= "<font face=\"Verdana\">" . $sendN[$i2] . "</font></small></center></td>";
                            $bountyT .= "<td width=\"6%\" bgcolor=\"$color\"><center><small>";
                            $bountyT .= "<font face=\"Verdana\">" . $bountyA[$i2] . "</font></small></center></td>";
                            $i2++;
                        }
                        
                        
                        if ($bountys > 0) {
?>
<hr />
<strong>Bounty Office</strong>
<hr />
<table border="0" width="100%">
<tr>
<td width="10%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Number</font></small></td>
<td width="30%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Recipiant</font></small></td>
<td width="30%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Sender</font></small></td>
<td width="30%" bgcolor="#a16410"><p align="center"><small><font face="Verdana">Amount</font></small></td>
<?
                            echo $bountyT;
                            echo "</td></tr><tr><td colspan=\"4\" bgcolor=\"#a16410\"><center><small><font face=\"Verdana\"><a href=\"index.php?page=game&gamepage=setbounty\">Click Here to Set A Bounty on a Player</a></font></small></center></td></tr></table><hr />";
                        } else {
                            echo "<br>There are no bounties!<hr /><a href=\"index.php?page=game&gamepage=setbounty\">Click Here to Set A Bounty on a Player</a>";
                        }
                        break;
                    case 'bca':
                        echo "<hr>Set of Bounty Success!<hr>";
                        break;
                    case 'bca2':
                        echo "<hr>Set of Bounty Failed!<hr>";
                        break;
                    case 'setbounty':
?>
<form action="addbounty.php" method="post">
Player to Bounty: <input type="text" name="recieve" /><br />
Amount of Credits: <input type="text" name="bounty" /><br />
<br />
<button type="submit">Add Bounty</button>
</form>
<?
                        break;
                    case 'hire':
                        echo "<br>There is no one currently up for hire!";
                        break;
                    case 'map':
                        if (isset($_GET['gal']) and $_GET['gal'] > 0 and $_GET['gal'] < 13) {
                            $q1         = "SELECT * FROM va_galaxy";
                            $q2         = "SELECT * FROM va_users";
                            $q3         = "SELECT * FROM va_users WHERE user = '" . $usr_N_R . "'";
                            $result1    = mysql_query($q1);
                            $result2    = mysql_query($q2);
                            $result3    = mysql_query($q3);
                            $num1       = mysql_numrows($result1);
                            $num2       = mysql_numrows($result2);
                            $userID     = 0;
                            $location   = "";
                            $locationID = 0;
                            $owner      = "";
                            $ownerID    = 0;
                            $request    = "";
                            $locationID = $_GET['gal'];
                            
                            for ($i = 0; $i < $num1; $i++) {
                                $request = mysql_result($result1, $i, "id");
                                if ($request == $locationID) {
                                    $location = mysql_result($result1, $i, "name");
                                    $ownerID  = mysql_result($result1, $i, "ownerid");
                                    $tog      = mysql_result($result1, $i, "gov");
                                    $TollIn   = mysql_result($result1, $i, "tollEnter");
                                    $regIn    = mysql_result($result1, $i, "register");
                                }
                            }
                            
                            for ($i = 0; $i < $num2; $i++) {
                                if (mysql_result($result2, $i, "user") == $usr_N_R) {
                                    $uID      = mysql_result($result2, $i, "id");
                                    $credits  = mysql_result($result2, $i, "currency");
                                    $galaxyID = mysql_result($result2, $i, "Cgalaxy");
                                }
                            }
                            
                            for ($i = 0; $i < $num1; $i++) {
                                $request = mysql_result($result1, $i, "id");
                                if ($request == $galaxyID) {
                                    $TollOut = mysql_result($result1, $i, "tollExit");
                                    $regOut  = mysql_result($result1, $i, "register");
                                    
                                }
                            }
                            
                            for ($i = 0; $i < $num2; $i++) {
                                $request = mysql_result($result2, $i, "id");
                                if ($request == $ownerID) {
                                    $owner = mysql_result($result2, $i, "user");
                                }
                            }
                            
                            switch ($tog) {
                                case 0:
                                    $togT = "Empire";
                                    break;
                                case 1:
                                    $togT = "Democratic-Republic";
                                    break;
                                case 2:
                                    $togT = "Republic";
                                    break;
                                case 3:
                                    $togT = "Theocracy";
                                    break;
                                case 4:
                                    $togT = "Monarchy";
                                    break;
                                case 5:
                                    $togT = "Communism";
                                    break;
                                case 6:
                                    $togT = "Socialism";
                                    break;
                                case 7:
                                    $togT = "Dictatorship";
                                    break;
                                case 8:
                                    $togT = "Aristocracy";
                                    break;
                                case 9:
                                    $togT = "Plutocracy";
                                    break;
                                case 10:
                                    $togT = "Stratocracy";
                                    break;
                                case 11:
                                    $togT = "Stratocracy";
                                    break;
                                default:
                                    $togT = "Anarchy";
                                    break;
                            }
                            
                            if (isset($_GET['travel'])) {
                                $tolls = $TollIn + $TollOut;
                                if ($credits >= $tolls) {
                                    $updatedC = $credits - $tolls;
                                    
                                    
                                    
                                    $UregisterIN  = $regIn + $TollIn;
                                    $UregisterOUT = $regOut + $TollOut;
                                    
                                    $query = "UPDATE va_users SET currency='" . $updatedC . "' WHERE id='" . $uID . "'";
                                    mysql_query($query) or die("Unable to edit credits!");
                                    
                                    $query = "UPDATE va_users SET Cgalaxy='" . $locationID . "' WHERE id='" . $uID . "'";
                                    mysql_query($query) or die("Unable to travel!");
                                    
                                    $query = "UPDATE va_galaxy SET register='" . $UregisterOUT . "' WHERE id='" . $galaxyID . "'";
                                    mysql_query($query) or die("Unable to edit register!");
                                    
                                    $query = "UPDATE va_galaxy SET register='" . $UregisterIN . "' WHERE id='" . $locationID . "'";
                                    mysql_query($query) or die("Unable to edit register!");
                                    
                                    $message  = "<br>You have successfully traveled to " . $location . ".";
                                    $message2 = "<br>You payed " . $TollOut . " Credits in leaving your current galaxy and " . $TollIn . " Credits for entering " . $location . ".";
                                } else {
                                    $message  = "<br> You dont have enough for traveling to " . $location . "!";
                                    $message2 = "";
                                }
                            } elseif (isset($_GET['move'])) {
                                $mvCredits = floor($credits * 0.25);
                                if ($credits >= $mvCredits) {
                                    
                                    $updatedC2 = $credits - $mvCredits;
                                    $query     = "UPDATE va_users SET currency='" . $updatedC2 . "' WHERE id='" . $uID . "'";
                                    mysql_query($query) or die("Unable to edit credits!");
                                    
                                    $query = "UPDATE va_users SET galaxy='" . $locationID . "' WHERE id='" . $uID . "'";
                                    mysql_query($query) or die("Unable to move!");
                                    $message = "<br>You have successfully moved your home to " . $location . ".";
                                    
                                } else {
                                    $message = "<br>You do not have enough credits to move your home to " . $location . ".";
                                }
                                
                            } else {
                                $mvCredits = floor($credits * 0.25);
                                $message   = "<br> This galaxy is called:  " . $location . ".  It is owned by: " . $owner . ".<br>The Government on this galaxy is: " . $togT . ".";
                                $message .= "<br>The toll to Enter this Galaxy is " . $TollIn . ". The Toll to exit your current galaxy is " . $TollOut . ".";
                                $message .= "<br><a href='index.php?page=game&gamepage=map&gal=" . $locationID . "&travel=" . $locationID . "'>Travel to " . $location . "?</a>";
                                $message .= "<br><a href='index.php?page=game&gamepage=map&gal=" . $locationID . "&move=" . $locationID . "'>Move your home to " . $location . " (" . $mvCredits . " Credits)?</a>";
                            }
                            
                        } else {
                            $message  = "";
                            $message2 = "";
                        }
                        
                        echo $message;
                        echo "<br><IMG NAME='galaxymap1a0' SRC='images/map/galaxymap1a.png' WIDTH='630' HEIGHT='630' BORDER='0' USEMAP='#galaxymap1a'>";
                        
                        echo "<MAP NAME='galaxymap1a'>";
                        echo "<AREA SHAPE='rect' COORDS='13,58,114,138' HREF='index.php?page=game&gamepage=map&gal=1#map'>";
                        echo "<AREA SHAPE='rect' COORDS='300,52,418,134' HREF='index.php?page=game&gamepage=map&gal=2#map'>";
                        echo "<AREA SHAPE='rect' COORDS='466,4,624,112' HREF='index.php?page=game&gamepage=map&gal=3#map'>";
                        echo "<AREA SHAPE='rect' COORDS='124,174,212,250' HREF='index.php?page=game&gamepage=map&gal=4#map'>";
                        echo "<AREA SHAPE='rect' COORDS='250,244,328,310' HREF='index.php?page=game&gamepage=map&gal=6#map'>";
                        echo "<AREA SHAPE='rect' COORDS='410,190,502,256' HREF='index.php?page=game&gamepage=map&gal=5#map'>";
                        echo "<AREA SHAPE='rect' COORDS='110,330,176,386' HREF='index.php?page=game&gamepage=map&gal=7#map'>";
                        echo "<AREA SHAPE='rect' COORDS='362,330,426,380' HREF='index.php?page=game&gamepage=map&gal=8#map'>";
                        echo "<AREA SHAPE='rect' COORDS='2,414,96,494' HREF='index.php?page=game&gamepage=map&gal=9#map'>";
                        echo "<AREA SHAPE='rect' COORDS='164,460,224,500' HREF='index.php?page=game&gamepage=map&gal=10#map'>";
                        echo "<AREA SHAPE='rect' COORDS='406,432,524,524' HREF='index.php?page=game&gamepage=map&gal=11#map'>";
                        echo "<AREA SHAPE='rect' COORDS='26,526,150,614' HREF='index.php?page=game&gamepage=map&gal=12#map'>";
                        echo "</MAP>";
                        
                        break;
                    case 'univ':
                        $q1         = "SELECT * FROM va_galaxy";
                        $q2         = "SELECT * FROM va_users";
                        $q3         = "SELECT * FROM va_users WHERE user = '" . $usr_N_R . "'";
                        $result1    = mysql_query($q1);
                        $result2    = mysql_query($q2);
                        $result3    = mysql_query($q3);
                        $num1       = mysql_numrows($result1);
                        $num2       = mysql_numrows($result2);
                        $userID     = 0;
                        $location   = "";
                        $locationID = 0;
                        $owner      = "";
                        $ownerID    = 0;
                        $request    = "";
                        for ($i = 0; $i < $num2; $i++) {
                            $request = mysql_result($result2, $i, "user");
                            if ($request == $usr_N_R) {
                                $userID     = mysql_result($result2, $i, "id");
                                $locationID = mysql_result($result2, $i, "galaxy");
                                $currentG   = mysql_result($result2, $i, "Cgalaxy");
                            }
                        }
                        
                        for ($i = 0; $i < $num1; $i++) {
                            $request = mysql_result($result1, $i, "id");
                            if ($request == $locationID) {
                                $location = mysql_result($result1, $i, "name");
                                $ownerID  = mysql_result($result1, $i, "ownerid");
                            }
                        }
                        
                        for ($i = 0; $i < $num2; $i++) {
                            $request = mysql_result($result2, $i, "id");
                            if ($request == $ownerID) {
                                $owner = mysql_result($result2, $i, "user");
                            }
                        }
                        
                        //Phew... Long code for one line >_>
                        
                        echo "<br>You live on the galaxy " . $location . " owned by: " . $owner . ".";
                        break;
                    default:
                        $query  = "SELECT * FROM va_users";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "user") == $_COOKIE["va_users"]) {
                                $Uid    = mysql_result($result, $i, "id");
                                $access = mysql_result($result, $i, "access");
                                $banTU  = mysql_result($result, $i, "user");
                            }
                        }
                        
                        $query  = "SELECT * FROM va_news";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        $query2  = "SELECT * FROM va_users";
                        $result2 = mysql_query($query2);
                        $num2    = mysql_numrows($result2);
                        
                        echo "<table border=1 cellpadding=0 cellspacing=0>";
                        echo "<tr>";
                        echo "<td>";
                        echo "<strong><center>News</center></strong><hr />";
                        echo "<table border=0 width=512>";
                        $start = $num1 - 1;
                        $beep  = 1;
                        for ($i = $start; $i >= 0; $i--) {
                            if (mysql_result($result, $i, "id") != "") {
                                $newsID  = mysql_result($result, $i, "id");
                                $subject = mysql_result($result, $i, "subject");
                                $text    = mysql_result($result, $i, "text");
                                $postedU = mysql_result($result, $i, "user");
                                $date    = mysql_result($result, $i, "date");
                                
                                for ($i2 = 0; $i2 < $num2; $i2++) {
                                    if (mysql_result($result2, $i2, "user") == $postedU) {
                                        $postedID = mysql_result($result2, $i2, "id");
                                    }
                                }
                                
                                if ($beep != 1) {
                                    echo "<tr>";
                                    echo "<td height='16' bgcolor='#000000'></td>";
                                    echo "</tr>";
                                }
                                echo "<tr>";
                                echo "<td height='16' bgcolor='#700000'><center>Date: {" . $date . "}<strong>" . $subject . "</strong></center></td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td bgcolor='#A07000'>";
                                echo $text;
                                echo "</td>";
                                echo "</tr>";
                                echo "<tr>";
                                echo "<td height='16' bgcolor='#700000'>";
                                echo "<table id='inv' border=0 align='left' cellpadding=0 cellspacing=0>";
                                echo "<tr>";
                                echo "<td id='top3'>";
                                echo "<div align=center>Poster: <b><a href='index.php?page=player&id=" . $postedID . "'>[" . $postedU . "]</a></b></div>";
                                echo "</td>";
                                echo "</tr>";
                                echo "</table>";
                                if ($access > 3) {
                                    echo "<table id='inv' border=0 align='right' cellpadding=0 cellspacing=0>";
                                    echo "<tr>";
                                    echo "<td id='top3'>";
                                    echo "<div align=right>Commands: [ <a href='index.php?page=cp&cpage=editNew&nID=" . $newsID . "'>Edit</a> | <a href='index.php?page=cp&cpage=delNews&nID=" . $newsID . "'>Delete</a> ]</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                    echo "</table>";
                                }
                                echo "</td>";
                                echo "</tr>";
                                
                                $beep++;
                            }
                        }
                        echo "</table>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        if ($access > 2) {
                            echo "<br><br><a href='index.php?page=game&gamepage=writebook'><span style='color: #ffd400'><b>G. Mods and up can Click Here to Write A Book</b></span></a><br>";
                        }
                        $query  = "SELECT * FROM va_config";
                        $result = mysql_query($query);
                        $num1   = mysql_numrows($result);
                        
                        for ($i = 0; $i < $num1; $i++) {
                            if (mysql_result($result, $i, "id") == 1) {
                                $lastReset = mysql_result($result, $i, "lastreset");
                            }
                        }
                        
                }
                
                
                break;
            }
        }
        break;
    
    case 'loginfailed':
        echo "<br />Login Failed:  Invalid User or Password.<br>So you know you put it in right? Try and see if Cookies are enabled.";
        break;
    case 'rstep1':
        echo "Please tell me which Galaxy you want to belong to: (Click it...)<br>";
        echo "<IMG NAME='galaxymap1a0' SRC='images/map/galaxymap1a.png' WIDTH='630' HEIGHT='630' BORDER='0' USEMAP='#galaxymap1a'>";
        
        echo "<MAP NAME='galaxymap1a'>";
        echo "<AREA SHAPE='rect' COORDS='13,58,114,138' HREF='register.php?step=2&gal=1'>";
        echo "<AREA SHAPE='rect' COORDS='300,52,418,134' HREF='register.php?step=2&gal=2'>";
        echo "<AREA SHAPE='rect' COORDS='466,4,624,112' HREF='register.php?step=2&gal=3'>";
        echo "<AREA SHAPE='rect' COORDS='124,174,212,250' HREF='register.php?step=2&gal=4'>";
        echo "<AREA SHAPE='rect' COORDS='250,244,328,310' HREF='register.php?step=2&gal=6'>";
        echo "<AREA SHAPE='rect' COORDS='410,190,502,256' HREF='register.php?step=2&gal=5'>";
        echo "<AREA SHAPE='rect' COORDS='110,330,176,386' HREF='register.php?step=2&gal=7'>";
        echo "<AREA SHAPE='rect' COORDS='362,330,426,380' HREF='register.php?step=2&gal=8'>";
        echo "<AREA SHAPE='rect' COORDS='2,414,96,494' HREF='register.php?step=2&gal=9''>";
        echo "<AREA SHAPE='rect' COORDS='164,460,224,500' HREF='register.php?step=2&gal=10'>";
        echo "<AREA SHAPE='rect' COORDS='406,432,524,524' HREF='register.php?step=2&gal=11'>";
        echo "<AREA SHAPE='rect' COORDS='26,526,150,614' HREF='register.php?step=2&gal=12'>";
        echo "</MAP>";
        break;
    case 'rstep2':
        echo "Please tell me which Class you want to be:<br>";
        echo "<a href='register.php?step=3&cla=1'>Human (Your average cool race)</a><br>";
        echo "<a href='register.php?step=3&cla=2'>Allicarin (Big heads, little green bodys)</a>";
        break;
    case 'regtaken':
        echo "That username is taken!";
?>
<form action="register.php?step=1" method="post">
<center>
<br>
Username: <input type="text" name="user"><br><br>
Password: <input type="password" name="pass"><br><br>
Email:  <input type="text" name="email"><br><br><br>
<button type="submit">Register</button> <button type="reset">Reset</button>
</center>
</form>
<?
        break;
    case 'register':
        $open = GameIsOpen();
        if ($open == true) {
?>
<form action="register.php?step=1" method="post">
<center>
<br>
Username: <input type="text" name="user"><br><br>
Password: <input type="password" name="pass"><br><br>
Email:  <input type="text" name="email"><br><br><br>
<button type="submit">Register</button> <button type="reset">Reset</button>
</center>
</form>
<?
        } else {
            echo "The game is not open!";
        }
        break;
    case 'logoutsuccess':
        echo "<br>You are now logged out!";
        break;
    case 'regsuccess':
        echo "Registration Successful! You may now log in!";
        break;
    case 'login':
        if (empty($_COOKIE['va_users']) == true) {
            echo "Please Log In:<br>";
?>
<br>
<form action="login.php" method="post">
<center>
Username: <input type="text" name="user"><br><br>
Password: <input type="password" name="pass"><br><br><br>
<button type="submit">Login</button> <button type="reset">Reset</button>
</center>
</form>
<a href='index.php?page=register'>If you aren't a user, register please!</a>
<?
        } elseif (empty($_COOKIE['va_users']) == false) {
            echo "<br>You are logged in, " . $usr_N_R . "!";
        } else {
            echo "<br>Cookies are not enabled on your browser!";
        }
        break;
    default:
        echo "<br>Unknown page, contact us about it in the forum.<br>Please return to:  <a href='index.php?page=index'>The Home Page.</a>";
        //add more obiously...
}
echo "</center>";
//Please Note: Between These Comments are GPL Protected.
cpl_wra();
//END GPL PROTECTION
echo "</td>";
//End Index Writing

writeright();
writebottom();
?>
</body>
</html>
