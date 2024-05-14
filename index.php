<?php

// Load .env vars
$env = [];
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
}

/**
 * @param string $content
 */
function save_result(string $content)
{
    if (empty($content)) {
        return;
    }

    $filepath = './results/article-links.csv';
    if (!file_exists($filepath)) {
        $file = fopen($filepath, 'x');
    } else {
        $file = fopen($filepath, 'a');
    }

    fwrite($file, $content . PHP_EOL);
    fclose($file);
}

/**
 * 
 */
function parse_xml_files()
{
    global $env;
    $files = glob("./source/*xml");
    if (empty($files) || !is_array($files)) {
        exit('No XML files in Dir');
    }

    foreach ($files as $filename) {
        $dom = new DOMDocument;
        $dom->loadXML(file_get_contents($filename));
        $links = $dom->getElementsByTagName('loc');
        echo "Link count: " . count($links) . "\n";
        foreach ($links as $link) {
            if (strpos($link->nodeValue, $env['MATCH_URL']) == false) {
                continue;
            }

            $url = $link->nodeValue;
            echo "Link found: " . $url . "\n";
            save_result($url);
        }
    }

    echo "Scrip process completed";
}

/**
 * 
 */
function parse_csv_files()
{
    global $env;
    $files = glob("./source/*csv");
    if (empty($files) || !is_array($files)) {
        exit('No CSV files in Dir');
    }

    foreach ($files as $filename) {
        echo "\n\nProcessing: " . $filename . "\n";
        $file_content = file_get_contents($filename);
        $data_rows = explode("\n", $file_content);
        echo "Record count: " . count($data_rows) . "\n";
        foreach ($data_rows as $row) {
            $string = str_replace(',', '', $row);
            if (strpos($string, $env['MATCH_URL']) == false) {
                continue;
            }

            $url = $string;
            echo "Link found: " . $url . "\n";
            save_result($url);
        }
    }

    echo "Scrip process completed";
}

parse_csv_files();
