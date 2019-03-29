<?php
include "phpnn/phpnn.php";
load("lyrics");
shoves("examples/training2");
//shove("examples/training2/1.txt");
chunk(3);
train(10, true);
save("lyrics");

echo "\nResult:\n\n";
echo generate(100, frequent()->value, false);