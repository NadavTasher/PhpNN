<?php
include "phpnn/phpnn.php";
load("lyrics");
//shoves("examples/training2");
shove("examples/training2/1.txt");
train(10, true);
save("lyrics");

echo "\n\nResult:\n\n";
echo generate(10000,"u", true);