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
                $dataset->nodes[$n]->repetitions = sizeof(explode($dataset->nodes[$n]->value, $content));
        }
    }

    function scan_node_split($file)
    {
        global $dataset;
        $content = file_get_contents($file);
        foreach ($dataset->nodes as $node) {
            if (strlen($node->value) > 0) {
                $split = explode($node->value, $content);
                if (sizeof($split) > 0) {
                    foreach ($split as $splat) {
                        add($splat);
                    }
                }
            }
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

function random()
{
    global $dataset;
    $sum = 0;
    foreach ($dataset->nodes as $node) {
        $sum += $node->repetitions;
    }
    $random = rand(0, $sum);
    $count = 0;
    foreach ($dataset->nodes as $node) {
        if ($random > $count && $random <= $count + $node->repetitions)
            return $node->value;
        else
            $count += $node->repetitions;
    }
    return "";
}

function generate($length=20)
{
    global $dataset;

    if ($length > 0) {
        return random() . generate($length - 1);
    }
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