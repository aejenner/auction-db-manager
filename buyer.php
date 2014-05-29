<?php

include_once 'config.php';

$connection = mysql_connect($url, $user, $pass);
mysql_select_db($db);

$buyerUser = filter_input(INPUT_GET, "buyerReportID", FILTER_SANITIZE_SPECIAL_CHARS);

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Buyer Report</title>
    <link rel="stylesheet" type="text/css" href="./report.css"/>
  </head>
  <body>
    <?php if (!$buyerUser) { ?>
    <p>Buyer Reports:</p>
    <p>Users with no Buyer ID are skipped, as are buyers with no purchased items.</p>
    <p>
      This page contains formatting such that when printed there is a page
      break after this page and between every report.
    </p>
    <p>This report was generated at <?php echo date("c"); ?></p>
    <hr/>
    <?php
    }
    if ($buyerUser) $q = mysql_query("SELECT * FROM users WHERE buyer_id = '".$buyerUser."';");
    else $q = mysql_query("SELECT * FROM users WHERE buyer_id IS NOT NULL;");
    //$i = 0;
    //$ct = mysql_num_rows($q);
    while ($result = mysql_fetch_assoc($q)) {
      if ($result["buyer_id"] == "") continue;
      $total = 0;
      $q2 = mysql_query("SELECT * FROM items WHERE buyer_id='".$result["buyer_id"]."';");
      if (mysql_num_rows($q2) == 0) continue;
      echo "    <p>Buyer Report for ".$result["first_name"]." ".$result["last_name"]." (Buyer ID ".$result["buyer_id"].") ".$result["email"]."</p>\n";
      echo "    <table>\n";
      echo "      <tr>\n";
      echo "        <td>Lot Number<td/>\n";
      echo "        <td>Lot Title<td/>\n";
      echo "        <td class=\"right\">Sale Price<td/>\n";
      echo "      </tr>\n";
      echo "      <tr>\n";
      echo "        <td colspan=\"8\">--------------------------------------------------------------------------------<td/>\n";
      echo "      </tr>\n";
      while ($r2 = mysql_fetch_assoc($q2)) {
        echo "      <tr>\n";
        echo "        <td>".$r2["lot_no"]."<td/>\n";
        echo "        <td>".substr($r2["title"], 0, 50)."<td/>\n";
        echo "        <td class=\"right\">".(($r2["price"] && $r2["price"] != 0) ? "$".$r2["price"] : "NO SALE")."<td/>\n";
        if ($r2["price"] && $r2["price"] != 0) $total += $r2["price"];
        echo "      </tr>\n";
      }
      echo "      <tr>\n";
      echo "        <td colspan=\"8\">--------------------------------------------------------------------------------<td/>\n";
      echo "      </tr>\n";
      echo "      <tr>\n";
      echo "        <td colspan=\"3\">Total Payment Due:<td/>\n";
      if ($total == 0) $tv = "NONE";
      else $tv = "$".$total;
      echo "        <td class=\"right\">".$tv."<td/>\n";
      echo "      </tr>\n";
      echo "    </table>\n";
      //echo "    <p>Buyer Report #".$i++." of ".$ct."</p>";
      echo "    <hr/>\n";
    }
    ?>
  </body>
</html>
<?php
mysql_close($connection);
?>
