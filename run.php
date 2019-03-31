<?php
include "phpnn/phpnn.php";
//ini_set('memory_limit', '1024M');
//load("words");
shoves("examples/php");
////shove("examples/training2/1.txt");
chunk(" ");
train(10, true);
//save("words2");

echo "\nResult:\n\n";
//echo weighted();
echo generate(100, ORIGINATED, null, " ");