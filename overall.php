<?php

include_once 'config.php';

$connection = mysql_connect($url, $user, $pass);
mysql_select_db($db);

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Overall Report</title>
    <link rel="stylesheet" type="text/css" href="./report.css"/>
  </head>
  <body>
    <p>Overall Report:</p>
    <?php
    
    $q = mysql_query("SELECT * FROM users WHERE buyer_id IS NOT NULL;");
    $totalBuyers = mysql_num_rows($q);
    
    $q = mysql_query("SELECT * FROM users WHERE seller_id IS NOT NULL;");
    $totalSellers = mysql_num_rows($q);
    
    $q = mysql_query("SELECT * FROM items WHERE seller_id IS NOT NULL;");
    $totalListings = mysql_num_rows($q);
    
    $q = mysql_query("SELECT * FROM items WHERE seller_id IS NOT NULL AND buyer_id IS NOT NULL AND price IS NOT NULL;");
    $totalSales = mysql_num_rows($q);
    
    $totalSalePrice = 0;
    while ($result = mysql_fetch_assoc($q)) {
      $totalSalePrice += $result["price"];
    }
    
    
    
    ?>
    <table>
      <tr>
        <td colspan="8">--------------------------------------------------------------------------------</td>
      </tr>
      <tr>
        <td>Total Buyers: </td>
        <td><?php echo $totalBuyers; ?></td>
      </tr>
      <tr>
        <td>Total Sellers: </td>
        <td><?php echo $totalSellers; ?></td>
      </tr>
      <tr>
        <td>Total Listings:</td>
        <td><?php echo $totalListings; ?></td>
      </tr>
      <tr>
        <td>Total Sales:</td>
        <td><?php echo $totalSales; ?></td>
      </tr>
      <tr>
        <td>Total Sale Price:</td>
        <td><?php echo "$".$totalSalePrice; ?></td>
      </tr>
      <tr>
        <td>Average Listing per Seller: </td>
        <td><?php echo $totalListings / $totalSellers; ?></td>
      </tr>
      <tr>
        <td>Average Sales per Seller: </td>
        <td><?php echo $totalSales / $totalSellers; ?></td>
      </tr>
      <tr>
        <td>Average Sales per Buyer: </td>
        <td><?php echo $totalSales / $totalBuyers; ?></td>
      </tr>
      <tr>
        <td>Average Sale Price: </td>
        <td><?php echo $totalSalePrice / $totalSales; ?></td>
      </tr>
    </table>
  </body>
</html>
