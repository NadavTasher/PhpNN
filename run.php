<?php
include "api/api.php";
//ini_set('memory_limit', '1024M');
//load("words3");
shoves("examples/beatles");
////shove("examples/training2/1.txt");
chunk(" ");
for ($a = 0; $a < 10; $a++) train(0, true);
save("beatles");

echo "\nResult:\n\n";
//echo weighted();
echo generate(100, WEIGHTED, null, " ");