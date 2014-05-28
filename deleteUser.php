<?php

include_once 'config.php';

$connection = mysql_connect($url, $user, $pass);
mysql_select_db($db);

$userLastName = filter_input(INPUT_GET, "last", FILTER_SANITIZE_SPECIAL_CHARS);
$userFirstName = filter_input(INPUT_GET, "first", FILTER_SANITIZE_SPECIAL_CHARS);

$q = mysql_query("SELECT * FROM users WHERE first_name='".$userFirstName."' AND last_name='".$userLastName."';");
if ($result = mysql_fetch_assoc($q)) {
  $q2 = mysql_query("SELECT * FROM items where seller_id='".$result['seller_id']."'");
  $slots = mysql_num_rows($q2);
  $q3 = mysql_query("SELECT * FROM items where buyer_id='".$result['buyer_id']."'");
  $blots = mysql_num_rows($q3);
} else { //no such user
  mysql_close($connection);
  header("Location: ./index.php");
}

mysql_close($connection);

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Delete User Confirmation</title>
    <link rel="stylesheet" type="text/css" href="./style.css"/>
  </head>
  <body style="font-size: xx-large;">
    <p>Are you sure you want to delete the user <?php echo $userFirstName." ".$userLastName;?>?</p>
    <p>Deleting this user will also clear the Seller ID of <?php echo $blots; ?> 
      lots, and the Buyer ID of <?php echo $slots;?> lots.
    </p>
    <form action="index.php?action=deluser" method="POST" style="display: inline;" >
      <input type="hidden" name="userLastName" value="<?php echo $userLastName; ?>"/>
      <input type="hidden" name="userFirstName" value="<?php echo $userFirstName; ?>"/>
      <input style="display: inline;" type="submit" value="Yes I am sure.">
    </form>
    <form  style="display: inline;" action="index.php" method="POST">
      <input style="display: inline;" type="submit" value="Cancel.">
    </form>
  </body>
</html>
