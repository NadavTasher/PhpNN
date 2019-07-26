<?php

const WEIGHTED = 1 << 0;
const ORIGINATED = 1 << 1;
const NONNEGOTIABLE = 1 << 2;

function generate($sequences = 20, $mask = WEIGHTED | ORIGINATED, $previous = null, $recreation_chunk = null)
{
    if ($previous === null) $previous = weighted();
    if ($sequences > 0) {
        return $previous . (($recreation_chunk !== null) ? $recreation_chunk : "") . generate($sequences - 1, $mask, next_node($previous, $mask), $recreation_chunk);
    }
    return "";
}

function next_node($previous, $mask)
{
    global $nodes;
    if (isset($nodes->$previous)) {
        $suggestions = array();
        if ($mask & ORIGINATED) {
            foreach ($nodes->$previous->destination as $d => $destination) {
                if (isset($nodes->$d)) {
                    $total = 0;
                    foreach ($nodes->$d->origin as $no => $next_origin) {
                        foreach ($nodes->$previous->origin as $o => $origin) {
                            if ($no === $o) {
                                $total += $next_origin->weight + $origin->weight;
                            }
                        }
                    }
                    for ($w = 0; $w < $total; $w++) array_push($suggestions, $d);
                }

            }
        }
        if ($mask & NONNEGOTIABLE) {
            $max = null;
            foreach ($nodes->$previous->destination as $d => $destination) {
                if ($max === null) {
                    $max = $d;
                } else {
                    if ($destination->weight > $nodes->$previous->destination->$max->weight)
                        $max = $d;
                }
            }
            array_push($suggestions, $max);
        } else {
            if (empty($suggestions)) {
                foreach ($nodes->$previous->destination as $d => $destination) {
                    if ($mask & WEIGHTED) {
                        for ($t = 0; $t < $destination->weight; $t++) {
                            array_push($suggestions, $d);
                        }
                    } else {
                        array_push($suggestions, $d);
                    }
                }
            }
        }
        if (!empty($suggestions)) {
            shuffle($suggestions);
            return $suggestions[0];
        }
    }
    return "";
}