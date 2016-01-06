<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/

include('includes/functions.php');

$connected = connectDB();
if ($connected == true) {
$name = $_POST['bName'];
$desc = $_POST['bDesc'];
$author = $_POST['bAuthor'];
$text = $_POST['bText'];
$level = $_POST['blevel'];
$pass = md5($_POST['bPass']);

$aPass = md5("123abc");

if ($aPass == $pass) {

$query = "INSERT INTO va_books VALUES ('','" . $name . "','" . $author . "','" . $desc . "','" . $text . "','" . $level . "')";
mysql_query($query) or die("Unable to create Mailbox.");
header("Location: index.php?page=game");
die();
}

//END Write!
} else {
header("Location: index.php?page=game");
die();
}

?>
