<?php
include "api/api.php";
//ini_set('memory_limit', '1024M');
//load("words3");
shoves("examples/beatles");
////shove("examples/training2/1.txt");
chunk(" ");
//for ($a = 0; $a < 1000; $a++)
    train(10, true);
//save("words3");

echo "\nResult:\n\n";
//echo weighted();
echo generate(100, WEIGHTED, null, " ");