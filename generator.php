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

function suggest_node($starter)
{
    global $dataset;
    $nexts = array();
    foreach ($dataset->nodes as $node) {
        if ($node->value === $starter) {
            $strongest = 1;
            foreach ($node->links as $link) {
                if ($link->strength === $strongest) {
                    array_push($nexts, $link->value);
                } else if ($link->strength > $strongest) {
                    $nexts = array($link->value);
                    $strongest = $link->strength;
                }
            }
        }
    }
    if (sizeof($nexts) > 0) {
        return shuffle($nexts)[0];
    }
    return "";
}

function generate($length = 20, $starter = "")
{
    if ($length > 0) {
        return $starter . generate($length - 1, suggest_node($starter));
    }
    return "";
}