<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$filenames = $argv[1] ?? null;

if ($filenames) {
    $filenames = [$filenames];
} else {
    $filenames = glob(__DIR__ . '/../data/*/*.prejson');
    $filenames = array_map(function ($path) {
        return pathinfo($path, PATHINFO_FILENAME);
    }, $filenames);
}

foreach ($filenames as $filename) {
    $parser = new \src\PreJsonParser($filename);

    $json = $parser->json();
    file_put_contents(__DIR__ . '/../data/' . $filename . '/' . $filename . '.json', $json);
    file_put_contents(__DIR__ . '/../data/' . $filename . '/' . $filename . '.min.json', \src\PreJsonParser::minify($json));
}

exit(0);
