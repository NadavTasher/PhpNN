<?php
include "trainer.php";
include "generator.php";

$inputs = array();
$nodes = new stdClass();

function shove($file)
{
    global $inputs;
    array_push($inputs, file_get_contents($file));
}

function shoves($directory)
{
    foreach (scandir($directory) as $file) {
        if (!empty($file) && $file[0] !== '.')
            shove($directory . DIRECTORY_SEPARATOR . $file);
    }
}

function feed($input)
{
    global $inputs;
    array_push($inputs, $input);
}

function feeds($array)
{
    foreach ($array as $input) feed($input);
}

function chunk($variable)
{
    global $chunk;
    $chunk = $variable;
}

function load($name)
{
    global $nodes;
    if (file_exists(dataset($name)))
        $nodes = json_decode(file_get_contents(dataset($name)));
    if ($nodes === null) {
        $nodes = array();
        echo "Dataset loading failed\n";
    }
}

function save($name)
{
    global $nodes;
    file_put_contents(dataset($name), json_encode($nodes));
}

function dataset($name)
{
    return "datasets/" . $name . ".json";
}
