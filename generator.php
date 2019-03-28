<?php

$suggestions=array();

function suggest_node($starter)
{
    global $dataset,$suggestions;
    $suggestions=array();
    foreach ($dataset->nodes as $node) {
        if ($node->value === $starter) {
            $strongest = 0;
            foreach ($node->links as $link) {
                global $suggestions;
                if ($link->strength > $strongest) {
                    $suggestions = array();
                    $strongest = $link->strength;
                }
                if ($link->strength === $strongest) {
                    array_push($suggestions, $link->value);
                }
            }
        }
        var_dump($suggestions);
    }
    if (sizeof($suggestions) > 0) {
        $suggestion=shuffle($suggestions)[0];
        echo $suggestion;
        return $suggestion;
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