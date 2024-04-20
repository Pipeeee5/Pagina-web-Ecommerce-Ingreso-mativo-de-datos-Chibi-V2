<?php

define("CLIENT_ID","AQceuJLPSJyKajP3Af8Vc1JSlfs_8DDhBq0faLjLk-JXhpDqwthrYL-Unzwo_6h7Hb_NCFbS8d2pNY91");
define("CURRENCY","USD");
define("KEY_TOKEN","APR.wqc-354*");
define("MONEDA","US $");

session_start();

$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>