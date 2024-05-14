<?php
function main()
{
    $files = glob("./source/*xml");
    if (empty($files) || !is_array($files)) {
        exit('No XML files in Dir');
    }

    $article_links = [];

    foreach ($files as $filename) {
        $dom = new DOMDocument;
        $dom->loadXML(file_get_contents($filename));
        $links = $dom->getElementsByTagName('loc');
        foreach ($links as $link) {
            if (strpos($link->nodeValue, 'bringmethesports') == false) {
                continue;
            }

            $article_links[] = $link->nodeValue;
        }

        echo "Processed file: " . $filename;
    }

    foreach ($article_links as $link) {
        echo $link . "\n";
    }
}


// 
main();
