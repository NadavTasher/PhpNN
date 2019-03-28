<?php

$files = array();
$truncate = 10;
$dataset = new stdClass();
$dataset->nodes = array();

function shove($file)
{
    global $files;
    array_push($files, $file);
}

function nodes($amount)
{
    global $truncate;
    $truncate = $amount;

}

function train($seconds, $output = false)
{
    global $files, $dataset;

    function reorder_nodes()
    {
        global $dataset;
        $modifications = 0;
        for ($n = 1; $n < sizeof($dataset->nodes); $n++) {
            $previous = $dataset->nodes[$n - 1];
            $current = $dataset->nodes[$n];
            if ($current->repetitions > $previous->repetitions) {
                $temporary = $previous;
                $dataset->nodes[$n - 1] = $current;
                $dataset->nodes[$n] = $temporary;
                $modifications++;
            }
        }
        if ($modifications != 0) reorder_nodes();
    }

    function create($chunk, $repetitions)
    {
        $node = new stdClass();
        $node->value = $chunk;
        $node->repetitions = $repetitions;
        return $node;
    }

    function add($chunk)
    {
        global $dataset;
        $found = false;
        for ($n = 0; $n < sizeof($dataset->nodes) && !$found; $n++) {
            if ($dataset->nodes[$n]->value === $chunk) {
                $dataset->nodes[$n]->repetitions++;
                $found = true;
            }
        }
        if (!$found) {
            array_push($dataset->nodes, create($chunk, 1));
        }
    }

    function reset_repetitions()
    {
        global $dataset;
        for ($n = 0; $n < sizeof($dataset->nodes); $n++) {
            $dataset->nodes[$n]->repetitions = 0;
        }
    }

    function scan_node_repetitions($file)
    {
        global $dataset;
        $content = file_get_contents($file);
        for ($n = 0; $n < sizeof($dataset->nodes); $n++) {
            if (strlen($dataset->nodes[$n]->value) > 0)
                $dataset->nodes[$n]->repetitions = (strlen($content) - strlen(str_replace($dataset->nodes[$n]->value, "", $content))) / strlen($dataset->nodes[$n]->value);
        }
    }

    function scan_node_split($file)
    {
        global $dataset;
        $content = file_get_contents($file);
        for ($n = 0; $n < sizeof($dataset->nodes); $n++) {
            $split=explode($dataset->nodes->value,$content);
            if(sizeof($split)>0){
                
            }
            if (strlen($dataset->nodes[$n]->value) > 0)
                $dataset->nodes[$n]->repetitions = (strlen($content) - strlen(str_replace($dataset->nodes[$n]->value, "", $content))) / strlen($dataset->nodes[$n]->value);
        }
    }

    function average($files)
    {
        $content = 0;
        foreach ($files as $file) {
            $content += strlen(file_get_contents($file));
        }
        return $content / sizeof($files) > 0 ? sizeof($files) : 0;
    }

    function add_nodes($length, $offset, $file)
    {
        $content = file_get_contents($file);
        $splits = str_split(substr($content, $offset), $length);
        foreach ($splits as $split) {
            add($split);
        }
    }

    if ($output)
        echo "Starting Training, Length: " . ($seconds) . "s\n";

    $end_time = time() + $seconds;
    $size = 1;
    $offset = 0;
    while (time() < $end_time) {
        reorder_nodes();
        reset_repetitions();
        foreach ($files as $file) {
            scan_node_repetitions($file);
            scan_node_split($file);
            add_nodes($size, $offset, $file);
        }
        if ($output) echo generate();
        if ($size > average($files)) {
            $size = 1;
            $offset++;
        } else {
            $size++;
        }
    }
    if ($output)
        echo "Finished Training, Delay: " . (time() - $end_time) . "s\n";
}

function generate()
{
    global $nodes;
    return "";
}

function load($name)
{
    global $dataset;
    if (file_exists(get_dataset_path($name)))
        $dataset = json_decode(file_get_contents(get_dataset_path($name)));
}

function save($name)
{
    global $dataset;
    file_put_contents(get_dataset_path($name), json_encode($dataset));
}

function get_dataset_path($name)
{
    return "datasets/" . $name . ".json";
}