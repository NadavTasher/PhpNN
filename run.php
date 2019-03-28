<?php
include "phpnn.php";
load("trained");
nodes(10);
shoves("examples");
train(10, true);
save("trained");

echo "\n\nResult:\n\n";
echo generate(100,"<");