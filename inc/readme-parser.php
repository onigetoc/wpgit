<?php
define('__ROOT__', dirname(dirname(__FILE__)));
// WP readme parser
require_once(__ROOT__ . '/inc/wp_readme-parser.php');
//require_once(__ROOT__ . '/inc/wp_readme-parser2.php');
// Github readme parser
require_once(__ROOT__ . '/inc/markdown.php');

//require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
//echo $plugins_url = plugins_url();
//echo $plugin_dir_path = plugin_dir_path( __FILE__ );

    $githubfolder = $_GET["githubfolder"];
    $branch = $_GET["branch"];

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <style>
                H1,
            h2 {
                border-bottom: 1px solid #eee;
                line-height: 1.6;
                margin-bottom: 16px;
            }
            
            #wpgit_parse {
                margin-top: 20px;
            }
            
            #wpgit_parse img {
                display: block;
                max-width: 100%;
                /*width: 100%;*/
                height: auto;
            }
            
            .ajax-loader {
                position: fixed;
                left: 0px;
                top: 60px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('img/reload.gif') 50% 40% no-repeat;
                /*background: url('img/loader.gif') 50% 40% no-repeat rgb(255, 255, 255);*/

        </style>
    </head>

    <body>

        <div id="wpgit_parse" class="container">
            <div class="align-center ajax-loader" id="wpgit_wait"></div>
            <?php
    
    //echo '<div class="align-center ajax-loader" id="wpgit_wait"></div>';
    
    echo "<div class='pull-right' ><a href='https:github.com/$githubfolder' class='btn btn-default wpgit_install' role='button'' target='_blank'><i class='glyphicon glyphicon-eye-open'></i> View on Github</a>";
    echo " <a href='#' class='btn btn-primary wpgit_install' role='button'' target='_blank'><i class='glyphicon glyphicon-download'></i> Install</a></div>";
    
    
    $readme_ext = array("readme.txt", "README.md", "readme.md", "README", "Readme.md", "README.txt", "readme.html");
    
    foreach ($readme_ext as $ext) {
        
        $file_exist = false ;
        
        $readme_file = "https://raw.githubusercontent.com/$githubfolder/".$branch."/".$ext;
        
        if ( ($ext == 'readme.txt' or $ext == 'README.txt' ) ) {
            
            $file_exist = wpgit_check_header($readme_file);
            
            if($file_exist) {
        
                echo "Readme Wordpress File | ".$ext;
                
                $wpParser = new readme_parser();
                echo $wpParser->replace($file_exist);
  
                echo '<style> #wpgit_wait {display:none;}</style>';
                
            }
        
        } else {
            
            $file_exist = wpgit_check_header($readme_file);
            
            if($file_exist) {
                
                echo "Readme Github File | ".$ext;
                
                //$file = @file_get_contents($readme_file);
        
                $markdown = new MarkdownExtra_Parser();
                echo $markdown->transform($file_exist);
                
                echo '<style> #wpgit_wait {display:none;}</style>';

            }
            
        }
        
        if($file_exist) break;
        
    }

    
    function wpgit_check_header($readme_file)
    {   
        return @file_get_contents($readme_file);
    }

    ?>
        </div>

    </body>

    </html>
