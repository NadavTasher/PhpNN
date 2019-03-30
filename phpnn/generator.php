<?php

function generate($sequences = 20, $starter = "", $weighted = false, $recreation_chunk = "")
{
    if ($sequences > 0) {
        return $starter . $recreation_chunk . generate($sequences - 1, suggest_node($starter, $weighted), $weighted, $recreation_chunk);
    }
    return "";
}

function suggest_node($starter, $weighted = false)
{
    global $nodes;
    $suggestions = array();
    foreach ($nodes as $node) {
        if ($node->v === $starter) {
            foreach ($node->l as $link) {
                if ($weighted) {
                    for ($t=0;$t<$link->s;$t++){
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