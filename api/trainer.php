<?php

$chunk = 1;

function add_node($node)
{
    global $nodes;
    if (!isset($nodes->$node)) {
        $nodes->$node = create_node();
    } else {
        $nodes->$node->frequency++;
    }
}

function add_origin($node, $link)
{
    global $nodes;
    if (!isset($nodes->$node->origin->$link)) {
        $nodes->$node->origin->$link = create_link();
    } else {
        $nodes->$node->origin->$link->weight++;
    }
}

function add_destination($node, $link)
{
    global $nodes;
    if (!isset($nodes->$node->destination->$link)) {
        $nodes->$node->destination->$link = create_link();
    } else {
        $nodes->$node->destination->$link->weight++;
    }
}

function create_node()
{
    $node = new stdClass();
    $node->frequency = 1;
    $node->origin = new stdClass();
    $node->destination = new stdClass();
    return $node;
}

function create_link()
{
    $link = new stdClass();
    $link->weight = 1;
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
        for ($f = 0; $f < $node->frequency; $f++) array_push($array, $key);
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

function train($seconds = 0, $output = false)
{
    global $inputs;
    if ($output)
        echo "Starting Training, Length: " . ($seconds) . "s\n";

    $end_time = time() + $seconds;
    $inputIndex = 0;
    for ($inputIndex = 0; $inputIndex < count($inputs) && (time() < $end_time || $seconds === 0); $inputIndex++) {
        scan(filter($inputs[$inputIndex]));
    }

    if ($output)
        echo "Finished Training, Delay: " . (time() - $end_time) . "s, Trained " . ($inputIndex) . "\n";
}
