<?php
include "phpnn/phpnn.php";
//ini_set('memory_limit', '1024M');
load("words");
//shoves("examples/training1");
//shove("examples/training2/1.txt");
//chunk("\n| ");
//train(200, true);
//save("php_trained");

echo "\nResult:\n\n";
echo generate(100,weighted(),true," ");