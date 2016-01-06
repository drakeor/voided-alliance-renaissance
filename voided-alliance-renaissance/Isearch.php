<?
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


$item = $_POST['name'];

header("Location: index.php?page=game&gamepage=iSearch&name=" . $item . "");
?>
