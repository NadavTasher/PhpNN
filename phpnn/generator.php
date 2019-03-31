<?php

function generate($sequences = 20, $weighted = true, $starter = "", $recreation_chunk = "")
{
    if ($sequences > 0) {
        return $starter . $recreation_chunk . generate($sequences - 1, $weighted, suggest_node($starter, $weighted), $recreation_chunk);
    }
    return "";
}

function suggest_node($starter, $weighted = true, $origin = true)
{
    global $nodes;
    $suggestions = array();
    foreach ($nodes as $node) {
        if ($node->v === $starter) {
            foreach ($node->d as $link) {
                if($origin){

                }
                if ($weighted) {
                    for ($t = 0; $t < $link->s; $t++) {
                        array_push($suggestions, $link->v);
                    }
                } else {
                    array_push($suggestions, $link->v);
                }
            }
        }
    }
    if (!empty($suggestions)) {
        shuffle($suggestions);
        return $suggestions[0];
    }
    return "";
}