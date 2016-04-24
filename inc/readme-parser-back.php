<head>
    <meta charset="UTF-8">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' type='text/css'
          media='all'/>

</head>
<?php
/*
GC Parser | WP readme.txt | Github readme.txt
*/

define('__ROOT__', dirname(dirname(__FILE__)));
// WP readme parser
require_once(__ROOT__ . '/inc/wp_readme-parser.php');
// Github readme parser
require_once(__ROOT__ . '/inc/markdown.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        #wpgit_parse img {
            display: block;
            max-width: 100%;
            /*width: 100%;*/
            height: auto;
        }
    </style>
</head>
<body>

<div id="wpgit_parse" class="container"><?php

    $readme_file = "https://raw.githubusercontent.com/$githubfolder/master/readme.txt";

    $file_headers = @get_headers($readme_file);
    if ($file_headers[0] == 'HTTP/1.1 404 Not Found') {
        
        echo "WP Readme.txt File does not exist exist | Github may File exists ";

        $readme_file = "https://raw.githubusercontent.com/$githubfolder/master/README.md";

        $file = @file_get_contents($readme_file);
        if (empty($file))
            return '<b>Markdown Parser: readme.txt Oups! Fichier non trouvé</b>';

        $markdown = new MarkdownExtra_Parser();
        echo $html = $markdown->transform($file);
        
    } else {
        
        echo "WP Readme.txt File found";

        $wpParser = new readme_parser();
        echo $wpParser->url($readme_file);

    }

    ?>
</div>

</body>
</html>
