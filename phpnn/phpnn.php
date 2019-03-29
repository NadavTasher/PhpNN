<?php
include "trainer.php";
include "generator.php";

$files = array();
$dataset = new stdClass();
$dataset->nodes = array();

function shove($file)
{
    global $files;
    array_push($files, $file);
}

function shoves($directory)
{
    foreach (scandir($directory) as $file) {
        shove($directory . DIRECTORY_SEPARATOR . $file);
    }
}

function chunk($length)
{
    global $chunkLength;
    $chunkLength = $length;
}

function load($name)
{
    global $dataset;
    if (file_exists(dataset($name)))
        $dataset = json_decode(file_get_contents(dataset($name)));
}

function save($name)
{
    global $dataset;
    file_put_contents(dataset($name), json_encode($dataset));
}

function dataset($name)
{
    return "datasets/" . $name . ".json";
}
