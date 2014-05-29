<?php

include_once 'config.php';

$connection = mysql_connect($url, $user, $pass);
mysql_select_db($db);

$errs = array();
$cons = array();

$action = filter_input(INPUT_GET, "action");

if ($action == "user") { //add/edit user
  $userFirstName = filter_input(INPUT_POST, "userFirstName", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($userFirstName == false || $userFirstName == NULL) array_push($errs, "A first name is required.");
  
  $userLastName = filter_input(INPUT_POST, "userLastName", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($userLastName == false || $userLastName == NULL) array_push($errs, "A last name is required.");
  
  $userSellerID = filter_input(INPUT_POST, "userSellerID", FILTER_SANITIZE_SPECIAL_CHARS);
  //TODO check duplicate SID and BID in users!
  
  $userBuyerID = filter_input(INPUT_POST, "userBuyerID", FILTER_SANITIZE_SPECIAL_CHARS);
  
  if (!$userSellerID && !$userBuyerID) array_push($errs, "A user should have a Buyer ID or a Seller ID or both.");
  
  $userEmail = filter_input(INPUT_POST, "userEmail", FILTER_SANITIZE_EMAIL);

  if (count($errs) == 0) {
    $q = mysql_query("SELECT * FROM users WHERE first_name='".$userFirstName."' AND last_name='".$userLastName."';");
    if(mysql_num_rows($q) == 0) {
      $d = mysql_query("INSERT INTO users (first_name, last_name, buyer_id, seller_id, email) VALUES ('".$userFirstName."','".$userLastName."','".$userBuyerID."','".$userSellerID."','".$userEmail."');");
      if (!$d) array_push ($errs, "Couldn't insert data into database. Please try again.");
      else array_push($cons, "Successfully added user ".$userLastName.", ".$userFirstName."!");
    } else {
      $d = mysql_query("UPDATE users SET buyer_id='".$userBuyerID."', seller_id='".
              $userSellerID."', email='".$userEmail."' WHERE first_name='".$userFirstName."' AND last_name='".$userLastName."'");
      if (!$d) array_push ($errs, "Couldn't update data in database. Please try again.");
      else array_push($cons, "Successfully updated user ".$userLastName.", ".$userFirstName."!");
    }
  }
} else if ($action == "lot") { //add/edit lot
  $lotNumber = filter_input(INPUT_POST, "lotNumber", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($lotNumber == false || $lotNumber == NULL) array_push($errs, "A lot number is required.");
  
  $lotSellerID = filter_input(INPUT_POST, "lotSellerID", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($lotSellerID == false || $lotNumber == NULL) array_push($errs, "A lot must have a seller ID.");
  $q = mysql_query("SELECT * FROM users WHERE seller_id='".$lotSellerID."';");
  mysql_num_rows($q);
  if(mysql_num_rows($q) == 0) array_push($errs, "Seller ID \"".$lotSellerID."\" does not map to a valid user.");
  
  $lotBuyerID = filter_input(INPUT_POST, "lotBuyerID", FILTER_SANITIZE_SPECIAL_CHARS);
  $qb = mysql_query("SELECT * FROM users WHERE buyer_id='".$lotBuyerID."';");
  if($lotBuyerID && mysql_num_rows($qb) == 0) {
    array_push($errs, "Buyer ID \"".$lotBuyerID."\" does not map to a valid user.");
  }
  
  $lotPrice = intval(filter_input(INPUT_POST, "lotPrice", FILTER_SANITIZE_SPECIAL_CHARS));
  
  $lotTitle = filter_input(INPUT_POST, "lotTitle");
  
  if (count($errs) == 0) {
    $q = mysql_query("SELECT * FROM items WHERE lot_no='".$lotNumber."';");
    if(mysql_num_rows($q) == 0) {
      $d = mysql_query("INSERT INTO items (lot_no, buyer_id, seller_id, price, title) ".
              "VALUES ('".$lotNumber."','".$lotBuyerID."','".$lotSellerID."','".$lotPrice."','".$lotTitle."');");
      if (!$d) array_push ($errs, mysql_error ()."Couldn't insert data into database. Please try again.");
      else array_push($cons, "Successfully added lot number ".$lotNumber."!");
    } else {
      $d = mysql_query("UPDATE items SET buyer_id='".$lotBuyerID."', seller_id='".
              $lotSellerID."', price='".$lotPrice."', title='".$lotTitle."' WHERE lot_no='".$lotNumber."'");
      if (!$d) array_push ($errs, "Couldn't update data in database. Please try again.");
      else array_push($cons, "Successfully updated lot number ".$lotNumber."!");
    }
  }
} else if ($action == "deluser") {
  $userFirstName = filter_input(INPUT_POST, "userFirstName", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($userFirstName == false || $userFirstName == NULL) array_push($errs, "A first name is required.");
  
  $userLastName = filter_input(INPUT_POST, "userLastName", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($userLastName == false || $userLastName == NULL) array_push($errs, "A last name is required.");
  
  $q = mysql_query("SELECT * FROM users WHERE first_name='".$userFirstName."' AND last_name='".$userLastName."'");
  if ($q != NULL && $result = mysql_fetch_assoc($q)) {
    mysql_query("UPDATE items SET buyer_id='' WHERE buyer_id='".$result["buyer_id"]."';");
    mysql_query("UPDATE items SET seller_id='' WHERE seller_id='".$result["seller_id"]."';");
    mysql_query("DELETE FROM users WHERE first_name='".$userFirstName."' AND last_name='".$userLastName."';");
    array_push($cons, "Successfully deleted user ".$userFirstName." ".$userLastName."!");
  } else {
    array_push($errs, "This user couldn't be deleted because it wasn't found in the database.");
  }
} else if ($action == "dellot") {
  $lotNumber = filter_input(INPUT_POST, "lotNumber", FILTER_SANITIZE_SPECIAL_CHARS);
  if ($lotNumber == false || $lotNumber == NULL) array_push($errs, "A lot number is required.");
  
  $q = mysql_query("SELECT * FROM items WHERE lot_no='".$lotNumber."'");
  if ($q != NULL && $result = mysql_fetch_assoc($q)) {
    mysql_query("DELETE FROM items WHERE lot_no='".$lotNumber."';");
    array_push($cons, "Successfully deleted lot ".$lotNumber."!");
  } else {
    array_push($errs, "This lot couldn't be deleted because it wasn't found in the database.");
  }
}

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Auction Database Management</title>
    <link rel="stylesheet" type="text/css" href="./style.css"/>
    <script type="text/javascript" src="./forms.js"></script>
  </head>
  <body onload="onLoadFunction();">
    <header>Auction Database Management</header>
    <span>Font Size:</span>
    <div id="fontButtonContainer">
      <div id="smallFont" class="minibutton">A</div>
      <div id="medFont" class="minibutton">A</div>
      <div id="bigFont" class="minibutton">A</div>
    </div>
    <?php
    if (count($errs) > 0) {
      echo "<div id=\"err\">\n";
      while ($res = array_pop($errs)) {
        echo "      <p>" . $res. "</p>\n";
      }
      echo "</div>";
    }

    if (count($cons) > 0) {
      echo "<div id=\"confirm\">\n";
      while ($res = array_pop($cons)) {
        echo "      <p>" . $res. "</p>\n";
      }
      echo "</div>";
    }

    echo "\n";
    ?>
    <div class="button" id="userButton">Add/Edit User Info</div>
    <div class="foldout" id="userInfo" <?php if ($action == user) echo "style=\"display: block;\""; ?>>
      <p>Entering a user with the same name will edit an existing entry.</p>
      <form action="index.php?action=user" method="POST">
        <table>
          <tr>
            <td><label for="userFirstName">First Name:</label></td>
            <td><input type="text" id="userFirstName" name="userFirstName" required/><br/></td>
            <td><label for="userLastName">Last Name:</label></td>
            <td><input type="text" id="userLastName" name="userLastName" required/><br/></td>
          </tr>
          <tr>
            <td><label for="userBuyerID">Buyer ID:</label></td>
            <td><input type="text" id="userBuyerID" name="userBuyerID"/><br/></td>
            <td><label for="userSellerID">Seller ID:</label></td>
            <td><input type="text" id="userSellerID" name="userSellerID"/><br/></td>
          </tr>
          <tr>
            <td><label for="userEmail">E-mail:</label></td>
            <td><input type="text" id="userEmail" name="userEmail"/><br/></td>
            <td colspan="2" style="text-align: right;">
              <input type="submit" value="Add/Edit User"/><br/>
            </td>
          </tr>
        </table>
      </form>
      <p>Select a user from below to edit or delete an existing entry.</p>
      <div id="ulist">
<?php
//var firstName, lastName, userBuyerID, userSellerID, email;
        $userQuery = mysql_query("SELECT * from users ORDER BY last_name, first_name");
        while ($result = mysql_fetch_assoc($userQuery)) {
          $js = "firstName.value = '".$result["first_name"]."'; ";
          $js .= "lastName.value = '".$result["last_name"]."'; ";
          if ($result["buyer_id"]) $js .= "userBuyerID.value = '".$result["buyer_id"]."'; ";
          else $js .= "userBuyerID.value = ''; ";
          if ($result["seller_id"]) $js .= "userSellerID.value = '".$result["seller_id"]."'; ";
          else $js .= "userSellerID.value = ''; ";
          if ($result["email"]) $js .= "userEmail.value = '".$result["email"]."'; ";
          else $js .= "userEmail.value = ''; ";
          echo "        ";
          echo "<div>";
          echo $result["last_name"];
          echo "<span>, </span>";
          echo $result["first_name"];
          echo "<span class=\"links\">";
          echo "<a href=\"#userButton\" onclick=\"" . $js . ";\">Edit</a> - ";
          echo "<a href=\"deleteUser.php?first=".$result["first_name"]."&last=".$result["last_name"]."\">Delete</a>";
          echo "</span>";
          echo "</div>\n";
        }
        //echo "\n";
?>
      </div>
    </div>
    <div class="button" id="lotButton">Add/Edit Lot Info</div>
    <div class="foldout" id="lotInfo" <?php if ($action == "lot") echo "style=\"display: block;\""; ?> >
      <p>Entering a lot with the same number will edit an existing entry.</p>
      <form action="index.php?action=lot" method="POST">
        <table>
          <tr>
            <td><label for="lotNumber">Lot Number:</label></td>
            <td><input type="text" size="4" id="lotNumber" name="lotNumber" required/><br/></td>
            <td><label for="lotSellerID" name="lotNumber">Seller ID:</label></td>
            <td><input type="text" size="4" id="lotSellerID" name="lotSellerID" required/><br/></td>
            <td><label for="lotBuyerID">Buyer ID:</label></td>
            <td><input type="text" size="4" id="lotBuyerID" name="lotBuyerID"/><br/></td>
            <td><label for="lotPrice">Sale Price</label></td>
            <td><input type="number" id="lotPrice" min="0" size="24" name="lotPrice"/><br/></td>
            <td><label for="lotTitle">Title:</label></td>
            <td><input type="text" id="lotTitle" name="lotTitle"/><br/></td>
            <td style="text-align: right;">
              <input type="submit" value="Add/Edit Lot"/><br/>
            </td>
          </tr>
        </table>
      </form>
      <p>Select a lot from below to edit or delete an existing entry.</p>
      <div id="ilist">
<?php
//var lotNumber, lotBuyerID, lotSellerID, lotPrice, lotTitle;
        $itemQuery = mysql_query("SELECT * from items ORDER BY lot_no");
        while ($result = mysql_fetch_assoc($itemQuery)) {
          $js = "lotNumber = document.getElementById(\"lotNumber\"); lotLoads[\"".$result["lot_no"]."\"] = function() {";
          $js .= "lotNumber.value = '".$result["lot_no"]."'; ";
          if ($result["buyer_id"]) $js .= "lotBuyerID.value = '".$result["buyer_id"]."'; ";
          else $js .= "lotBuyerID.value = ''; ";
          $js .= "lotSellerID.value = '".$result["seller_id"]."'; ";
          if ($result["price"]) $js .= "lotPrice.value = '".$result["price"]."'; ";
          else $js .= "lotPrice.value = ''; ";
          if ($result["title"]) $js .= "lotTitle.value = '".$result["title"]."'; ";
          else $js .= "lotTitle.value = ''; ";
          $js .= "};";
          echo "        ";
          echo "<script type=\"text/javascript\">".$js."</script>\n";
          echo "        ";
          echo "<div>";
          echo $result["lot_no"];
          echo "<span>, sold by </span>";
          echo $result["seller_id"];
          echo "<span class=\"links\">";
          $js2 = "lotLoads[".$result["lot_no"]."]();";
          echo "<a href=\"#lotButton\" onclick=\"" . $js2 . ";\">Edit</a> - ";
          echo "<a href=\"deleteLot.php?no=".$result["lot_no"]."\">Delete</a>";
          echo "</span>";
          echo "</div>\n";
        }
        //echo "\n";
?>
      </div>
    </div>
    <div class="button" id="reportsButton">View Reports</div>
    <div class="foldout" id="reportsInfo">
      <p>
        <form style="display: inline;" action="buyer.php" method="GET"><input type="submit" value="View Buyer Reports (All)"/></form>
        <form style="display: inline;" action="buyer.php" method="GET">
          <label for="buyerReportID">Buyer ID: </label>
          <input type="text" name="buyerReportID" id="buyerReportID"/>
          <input type="Submit" value="View Individual Report">
        </form>
      </p>
      <p>
        <form style="display: inline;" action="seller.php" method="GET"><input type="submit" value="View Seller Reports (All)"/></form>
        <form style="display: inline;" action="seller.php" method="GET">
          <label for="sellerReportID">Seller ID: </label>
          <input type="text" name="sellerReportID" id="sellerReportID"/>
          <input type="Submit" value="View Individual Report">
        </form>
      </p>
      <p>
        <form style="display: inline;" action="overall.php" method="GET"><input type="submit" value="View Overall Report"/></form>
      </p>
    </div>
    <footer>
      This software copyright (c) Andrew Jenner, 2014. Available under the MIT
      License, meaning it is provided without warranty of any kind and may be
      modified or redistributed at the will of user.
    </footer>
  </body>
</html>
<?php
mysql_close($connection);
?>
