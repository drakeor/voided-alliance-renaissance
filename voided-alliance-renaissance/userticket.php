<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


include('includes/functions.php');

connectDB();

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


//$que = "SELECT * FROM va_users WHERE user='" . $_POST['user'] . "' AND pass='" . md5($_POST['pass']) . "'";
$query = "SELECT * FROM va_users";
$result = mysql_query($query);
$num1 = mysql_numrows($result);

$query2 = "SELECT * FROM va_ticket";
$result2 = mysql_query($query2);
$num2 = mysql_numrows($result2);

$name = $usr_N_R;
$text = $_POST['text'];
$date = date("n.j.y");

$query = "INSERT INTO va_ticket VALUES ('','" . $name . "','" . $text . "','" . $date . "')";
//Start of Mail script!

mysql_query($query) or die("Unable to create ticket!");

/*$to = "";
$subject = "User Ticket ";
$message = "Hello! You have recieved a user ticket from $name. 
It's contents say: $text 
Please visit your game to reply to the user.";
$headers = "From: $name";
mail($to,$subject,$message,$headers);*/


header("Location: index.php?page=game&gamepage=crea");
?>
