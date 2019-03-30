<?php
include "phpnn/phpnn.php";
//ini_set('memory_limit', '1024M');
//load("lyrics");
shoves("examples/beatles");
//shove("examples/training2/1.txt");
chunk(4);
train(200, true);
//save("lyrics");

echo "\nResult:\n\n";
echo generate(100, frequent()->v, false);