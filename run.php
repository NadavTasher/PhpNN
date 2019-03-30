<?php
include "phpnn/phpnn.php";
ini_set('memory_limit','1024M');
load("lyrics2");
shoves("examples/training4");
//shove("examples/training2/1.txt");
chunk(3);
//train(200, true);
//save("lyrics2");

echo "\nResult:\n\n";
echo generate(100, frequent()->v, false);