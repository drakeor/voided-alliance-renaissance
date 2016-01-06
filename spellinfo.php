<?php
/*
This file and all others are protected by
a GPL.  Please read the licence before
editing these files.

-Voided Alliance, Copyright Auburnflame Games
*/


$id = $_POST['spellname'];

header("Location: index.php?page=game&gamepage=spellinfo&name=" . $id);
die();
?>
