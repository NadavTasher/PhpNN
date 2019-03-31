<?php
include "trainer.php";
include "generator.php";

$files = array();
$nodes = new stdClass();

function shove($file)
{
    global $files;
    array_push($files, $file);
}

function shoves($directory)
{
    foreach (scandir($directory) as $file) {
        if (!empty($file) && $file[0] !== '.')
            shove($directory . DIRECTORY_SEPARATOR . $file);
    }
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
