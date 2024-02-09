<?php

require_once './Models/Product.php';

$params = array('nome' => 'a', 'marca' => 'b', 'prezzo' => '10');

$prodotto = Product::Create($params);
echo "creato";


/*$eliminato = Product::Find('2');
$eliminato->update("elo","manu", "9");
echo 'prodotto modificato';*/
