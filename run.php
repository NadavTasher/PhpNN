<?php
include "nn.php";
load("trained");
nodes(10);
shove("examples/phpcode1.php");
shove("examples/phpcode2.php");
train(10, true);
save("trained");

echo "\n\nResult:\n\n".generate(40);