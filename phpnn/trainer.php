<?php

$chunk = 1;

function add_node($node)
{
    global $nodes;
    if (!isset($nodes->$node)) {
        $nodes->$node = create_node();
    } else {
        $nodes->$node->f++;
    }
}

function add_origin($node, $link)
{
    global $nodes;
    if (!isset($nodes->$node->o->$link)) {
        $nodes->$node->o->$link = create_link();
    } else {
        $nodes->$node->o->$link->s++;
    }
}

function add_destination($node, $link)
{
    global $nodes;
    if (!isset($nodes->$node->d->$link)) {
        $nodes->$node->d->$link = create_link();
    } else {
        $nodes->$node->d->$link->s++;
    }
}

function create_node()
{
    $node = new stdClass();
    $node->f = 1;
    $node->o = new stdClass();
    $node->d = new stdClass();
    return $node;
}

function create_link()
{
    $link = new stdClass();
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

function weighted()
{
    global $nodes;
    $array = array();
    foreach ($nodes as $key => $node) {
        for ($f = 0; $f < $node->f; $f++) array_push($array, $key);
    }
    if (!empty($array)) {
        shuffle($array);
        return $array[0];
    }
    return "";
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
    foreach ($chunks as $current) {
        if (!empty($current)) {
            add_node($current);
            if (!empty($previous)) {
                add_destination($previous, $current);
                add_origin($current, $previous);
            }
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
    }
    if ($output)
        echo "Finished Training, Delay: " . (time() - $end_time) . "s, Trained " . ($fileIndex + 1) . "\n";
}
