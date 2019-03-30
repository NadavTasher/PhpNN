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
    global $nodes;
    $suggestions = array();
    foreach ($nodes as $node) {
        if ($node->v === $starter) {
            $strongest = 0;
            foreach ($node->l as $link) {
                if ($strengthCare) {
                    if ($link->s > $strongest) {
                        $suggestions = array();
                        $strongest = $link->s;
                    }
                    if ($link->s === $strongest) {
                        array_push($suggestions, $link->v);
                    }
                } else {
                    array_push($suggestions, $link->v);
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