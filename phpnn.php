<?php
include "trainer.php";
include "generator.php";

$files = array();
$truncate = 10;
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

function nodes($amount)
{
    global $truncate;
    $truncate = $amount;

}

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
        reorder_nodes();
    }
    if ($output)
        echo "Finished Training, Delay: " . (time() - $end_time) . "s\n";
}

function generate($length = 20)
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
