
<?php

$file = "shouts.txt";

$handle = fopen($file, 'r');

$someWords = fread($handle, filesize($file));



$shouts_shown = 25;

$wordChunks = explode("|", $someWords, 10);

$current = count($wordChunks);

for($i = 0; $i < $current; $i++){

echo $wordChunks[$i] . "<br>";

$i++;

}

 









