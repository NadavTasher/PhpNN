<?php

$chunkLength = 1;

function train($seconds, $output = false)
{
    global $files;
    if ($output)
        echo "Starting Training, Length: " . ($seconds) . "s\n";

    $end_time = time() + $seconds;
    $fileIndex = 0;
    while (time() < $end_time && $fileIndex < sizeof($files)) {
        $content = file_get_contents($files[$fileIndex]);
        scan_recursively($content);
        $fileIndex++;
        sort_nodes();
    }
    if ($output)
        echo "Finished Training, Delay: " . (time() - $end_time) . "s\n";
}

function sort_nodes()
{
    global $dataset;
    $modifications = 0;
    for ($n = 1; $n < sizeof($dataset->nodes); $n++) {
        $previous = $dataset->nodes[$n - 1];
        $current = $dataset->nodes[$n];
        if ($current->frequency > $previous->frequency) {
            $temporary = $previous;
            $dataset->nodes[$n - 1] = $current;
            $dataset->nodes[$n] = $temporary;
            $modifications++;
        }
    }
    if ($modifications != 0) sort_nodes();
}

function add_node($node)
{
    global $dataset;
    $found = false;
    foreach ($dataset->nodes as $n) {
        if ($n->value === $node) {
            $n->frequency++;
            $found = true;
        }
    }
    if (!$found) {
        array_push($dataset->nodes, create_node($node));
    }
}

function create_node($value)
{
    $node = new stdClass();
    $node->value = $value;
    $node->frequency = 1;
    $node->links = array();
    return $node;
}

function add_link($node, $link)
{
    foreach (nodes() as $n) {
        if ($n->value === $node) {
            $found = false;
            foreach ($n->links as $l) {
                if ($l->value === $link) {
                    $l->strength++;
                    $found = true;
                }
            }
            if (!$found) {
                array_push($n->links, create_link($link));
            }
        }
    }
}

function create_link($next)
{
    $link = new stdClass();
    $link->value = $next;
    $link->strength = 1;
    return $link;
}

function scan_recursively($content)
{
    global $chunkLength;
    if (strlen($content) > 0) {
        $current = substr($content, 0, $chunkLength);
        add_node($current);
        $next = scan_recursively(substr($content, $chunkLength));
        if (!empty($next))
            add_link($current, $next);
        return $current;
    }
    return "";
}

function reset_frequency()
{
    global $dataset;
    for ($n = 0; $n < sizeof($dataset->nodes); $n++) {
        $dataset->nodes[$n]->frequency = 0;
    }
}