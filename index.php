<?php
/**
 * Created by PhpStorm.
 * User: danny
 * Date: 2019-12-05
 * Time: 15:36
 */

//this line makes PHP behave in a more strict way
declare(strict_types=1);
//we are going to use session variables so we need to enable sessions
session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}
//your products with their price.
$foods = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];
$drinks = [
    ['name' => 'Cola', 'price' => 2.5],
    ['name' => 'Fanta', 'price' => 2.5],
    ['name' => 'Sprite', 'price' => 2.5],
    ['name' => 'Ice-tea', 'price' => 3],
];
$totalValue = 0;
if (!isset($_SESSION['deliverySpeed'])) {
    $_SESSION['deliverySpeed'] = "first";
}


require 'form-view.php';