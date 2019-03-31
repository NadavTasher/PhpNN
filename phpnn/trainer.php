<?php

$chunk = 1;

function add_node($node)
{
    global $nodes;
    $found = false;
    foreach ($nodes as $n) {
        if ($n->v === $node) {
            $n->f++;
            $found = true;
        }
    }
    if (!$found) {
        array_push($nodes, create_node($node));
    }
}

function add_origin($node, $link)
{
    global $nodes;
    foreach ($nodes as $n) {
        if ($n->v === $node) {
            $found = false;
            foreach ($n->o as $o) {
                if ($o->v === $link) {
                    $o->s++;
                    $found = true;
                }
            }
            if (!$found) {
                array_push($n->o, create_link($link));
            }
        }
    }
}

function add_destination($node, $link)
{
    global $nodes;
    foreach ($nodes as $n) {
        if ($n->v === $node) {
            $found = false;
            foreach ($n->d as $d) {
                if ($d->v === $link) {
                    $d->s++;
                    $found = true;
                }
            }
            if (!$found) {
                array_push($n->d, create_link($link));
            }
        }
    }
}

function create_node($value)
{
    $node = new stdClass();
    $node->v = $value;
    $node->f = 1;
    $node->o = array();
    $node->d = array();
    return $node;
}

function create_link($next)
{
    $link = new stdClass();
    $link->v = $next;
    $link->s = 1;
    return $link;
}

function filter($input)
{
    $rebuilt = "";
    foreach (str_split($input) as $char) {
        if (!preg_match('/[^\x00-\x7f]/', $char)) $rebuilt .= $char;
    }
    return $rebuilt;
}

function frequent()
{
    global $nodes;
    sort_nodes();
    return $nodes[0]->v;
}

function weighted()
{
    global $nodes;
    $array = array();
    foreach ($nodes as $node) {
        for ($f = 0; $f < $node->f; $f++) array_push($array, $node->v);
    }
    if (!empty($array)) {
        shuffle($array);
        return $array[0];
    }
    return "";
}

function reset_frequency()
{
    global $nodes;
    foreach ($nodes as $node) {
        $node->f = 0;
    }
}

function sort_nodes()
{
    global $nodes;
    $modifications = 0;
    for ($n = 1; $n < sizeof($nodes); $n++) {
        $previous = $nodes[$n - 1];
        $current = $nodes[$n];
        if ($current->f > $previous->f) {
            $temporary = $previous;
            $nodes[$n - 1] = $current;
            $nodes[$n] = $temporary;
            $modifications++;
        }
    }
    if ($modifications != 0) sort_nodes();
}

function scan($content)
{
    global $chunk;
    $chunks = array();
    if (is_string($chunk)) {
        $chunks = preg_split("/$chunk/", $content);
    } else if (is_numeric($chunk)) {
        $chunks = str_split($content, $chunk);
    }

    $previous = "";
    add_node($previous);
    foreach ($chunks as $current) {
        if (!empty($current)) {
            add_node($current);
            add_destination($previous, $current);
            add_origin($current, $previous);
            $previous = $current;
        }
    }
}

function train($seconds, $output = false)
{
    global $files;
    if ($output)
        echo "Starting Training, Length: " . ($seconds) . "s\n";

    $end_time = time() + $seconds;
    $fileIndex = 0;
    while (time() < $end_time && $fileIndex < sizeof($files)) {
        $content = filter(file_get_contents($files[$fileIndex]));
        scan($content);
        $fileIndex++;
        sort_nodes();
    }
    if ($output)
        echo "Finished Training, Delay: " . (time() - $end_time) . "s, Trained " . ($fileIndex + 1) . "\n";
}
