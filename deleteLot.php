<?php

include_once 'config.php';

$connection = mysql_connect($url, $user, $pass);
mysql_select_db($db);

$lotNumber = filter_input(INPUT_GET, "no", FILTER_SANITIZE_SPECIAL_CHARS);

$q = mysql_query("SELECT * FROM items WHERE lot_no='".$lotNumber."';");
if (!$result = mysql_fetch_assoc($q)) {
  mysql_close($connection);
  header("Location: ./index.php");
}

mysql_close($connection);

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Delete Lot Confirmation</title>
    <link rel="stylesheet" type="text/css" href="./style.css"/>
  </head>
  <body style="font-size: xx-large;">
    <p>Are you sure you want to delete the lot <?php echo $lotNumber;?>?</p>
    <form action="index.php?action=dellot" method="POST" style="display: inline;" >
      <input type="hidden" name="lotNumber" value="<?php echo $lotNumber; ?>"/>
      <input style="display: inline;" type="submit" value="Yes I am sure.">
    </form>
    <form  style="display: inline;" action="index.php" method="POST">
      <input style="display: inline;" type="submit" value="Cancel.">
    </form>
  </body>
</html>

