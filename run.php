<?php
include "phpnn/phpnn.php";
//ini_set('memory_limit', '1024M');
load("words");
shoves("examples/beatles");
//shove("examples/training2/1.txt");
//chunk(" ");
//train(200, true);
//save("words");

echo "\nResult:\n\n";
echo generate(100, frequent()->v, true, " ");