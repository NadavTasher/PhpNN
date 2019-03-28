<?php
function random()
{
    global $dataset;
    $sum = 0;
    foreach ($dataset->nodes as $node) {
        $sum += $node->frequency;
    }
    $random = rand(0, $sum);
    $count = 0;
    foreach ($dataset->nodes as $node) {
        if ($random > $count && $random <= $count + $node->frequency)
            return $node->value;
        else
            $count += $node->frequency;
    }
    return "";
}