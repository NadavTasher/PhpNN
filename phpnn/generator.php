<?php

function generate($sequences = 20, $starter = "", $strengthCare = false)
{
    if ($sequences > 0) {
        return $starter . generate($sequences - 1, suggest_node($starter, $strengthCare));
    }
    return "";
}

function suggest_node($starter, $strengthCare = false)
{
    global $dataset;
    $suggestions = array();
    foreach ($dataset->nodes as $node) {
        if ($node->value === $starter) {
            $strongest = 0;
            foreach ($node->links as $link) {
                if ($strengthCare) {
                    if ($link->strength > $strongest) {
                        $suggestions = array();
                        $strongest = $link->strength;
                    }
                    if ($link->strength === $strongest) {
                        array_push($suggestions, $link->value);
                    }
                } else {
                    array_push($suggestions, $link->value);
                }
            }
        }
    }
    if (sizeof($suggestions) > 0) {
        shuffle($suggestions);
        return $suggestions[0];
    }
    return "";
}