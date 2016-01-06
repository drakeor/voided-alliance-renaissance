<?php
  function parse_mysql_dump($url){
    $file_content = file($url);
    $query = "";
    foreach($file_content as $sql_line){
      if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
        $query .= $sql_line;
        if(preg_match("/;[\040]*\$/", $sql_line)){
          $result = mysql_query($query)or die(mysql_error());
          $query = "";
        }
      }
    }
  }

echo "Starting Installation... <br>";
$dbhost = $_POST['datahost'];
$dbname = $_POST['dataname'];
$dbpass = $_POST['datapass'];
$dbuser = $_POST['datauser'];
echo "Connecting to Database........";

//Test the connection before we move on!
$testconnect = mysql_connect ($dbhost, $dbuser, $dbpass);
if (!$testconnect) {

unset($dbpasswd);
die ("Cannot Connect to database.");

}
else {

mysql_select_db ($dbname);
unset($dbpasswd);
echo "Successful <br>";

}

echo "Creating Config file.........";
$configfile = "config.php";
$writeconfig = fopen($configfile, 'w') or die("Cannot create/edit config File.");
$stringData = "<?php \n";
fwrite($writeconfig, $stringData);
$stringData = "$" . "dbhost = " . "'" . $dbhost . "'" . ";" . " \n";
fwrite($writeconfig, $stringData);
$stringData = "$" . "dbname = " . "'" . $dbname . "'" . ";" . " \n";
fwrite($writeconfig, $stringData);
$stringData = "$" . "dbuser = " . "'" . $dbuser . "'" . ";" . " \n";
fwrite($writeconfig, $stringData);
$stringData = "$" . "dbpass = " . "'" . $dbpass . "'" . ";" . " \n";
fwrite($writeconfig, $stringData);
$stringData = "?>";
fwrite($writeconfig, $stringData);
fclose($writeconfig);
echo "Successful<br>";

echo "Running database Querys......";

$queryFile = 'SQL.sql.txt';
parse_mysql_dump($queryFile);

echo "<br/>Creating User.</br>";
$query = "INSERT INTO va_mailbox VALUES ('19','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','')";
mysql_query($query) or die("Unable to create Mailbox.");

$query = "INSERT INTO va_users VALUES ('','admin','" . md5($_POST['adminpass']) . "','','" . date("n.j.y") . "','5','1','1','1','0','100','1000','1000','1001','1001','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','19','10','10','10','10','200','5','3','3','0','0','0','0','0','0','0','0','0','0','0','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','0','','')";
mysql_query($query) or die("Unable to register account");

echo "Closing database.<br>";
mysql_close($testconnect);
echo "Voided Alliance has been successfully Installed";
echo "<br><br><b>Now delete install.php and installer.php for security and also the SQL.sql.txt.</b>";




?>