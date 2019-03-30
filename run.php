<?php
include "phpnn/phpnn.php";
//ini_set('memory_limit', '1024M');
//load("newphp");
shoves("examples/php");
//shove("examples/training2/1.txt");
chunk(" ");
train(200, true);
save("newphp");

echo "\nResult:\n\n";
echo generate(100,"",true," ");