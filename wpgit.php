<?php
/*
Plugin Name: WPGIT
Plugin URI: http://www.wordpress.com/wordpress-plugins/wpgit
Description: WPGIT | Search and install Wordpress themes and plugins from Github:
Author: onigetoc
Version: 0.1
Author URI: http://www.wpgit.org
*/

/* add js and css file to admin plugin */
function wpgit_admin_scripts() { 

    /* Add jQuery that's already built into WordPress */
	wp_enqueue_script('jquery');
	
    /* Add stylesheet CSS */
  	wp_enqueue_style( 'bootstrap-wrapper_CSS', plugins_url('admin/css/bootstrap-wrapper.css', __FILE__) );
    
    /* Add stylesheet CSS */
	wp_enqueue_style( 'wpgit_CSS', plugins_url('admin/css/style.css', __FILE__) );
	
	/* Add js file */
	//wp_enqueue_script( 'wpgit_JS', plugin_dir_url( __FILE__ ) . 'admin/js/scripts.js' );
	
    /* Add bootstrap js */
    /*
   	wp_enqueue_script(
   		'bootstrap_JS', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', 
   		array('jquery')
   	);    
    */
    
	/* Add js file who need jQuery to work */
	wp_enqueue_script(
		'wpgit_JS', plugin_dir_url( __FILE__ ) . 'admin/js/script.js', 
		array('jquery')
	);
	
}
add_action( 'admin_enqueue_scripts', 'wpgit_admin_scripts' );


/* Plugin setting and post */
function wpgit_settings()
{

    // this is where we'll display our admin options
    if ( isset($_POST['action']) == 'update')
    {
        //$wpgit_external = $_POST['wpgit_external'];
        //update_option('wpgit_external', $wpgit_external);

        $message = '<div id="message" class="updated fade"><p><strong>Options Saved</strong></p></div>';

    }

    if ( isset($_POST['wpgit_options'] )) {
        $wpgit_options = serialize($_POST['wpgit_options']);
        update_option('wpgit_options', $wpgit_options);
    }

    if ( isset($_POST['radio_list'] )) {
        if($_POST['radio_list'] !== '') {
            include( plugin_dir_path( __FILE__ ) . 'parse_radionomy.php');
        }
    }

    /* Retrieve options */
    $wpgit_options_get = unserialize( get_option('wpgit_options') );


    if($wpgit_options_get){
        $wpgit_option1 = $wpgit_options_get['option1'];
        $wpgit_option2 = $wpgit_options_get['option2'];

    }else{
        $wpgit_option1 = 'my option 1';
        $wpgit_option2 = 'my option 2';
    }


    /* Get Paths */
    $plugin_dir = plugins_url();
    $plugin_url = plugins_url( '' , __FILE__ ).'/';

    /* Get plugin infos */
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_version = $plugin_data['Version'];
    $plugin_name = $plugin_data['Name'];
    $plugin_description = $plugin_data['Description'];
    
    
    // http://codex.wordpress.org/ThickBox
    add_thickbox();
    
    ?>




    <!--- Bootstrap --->
    <div class="bootstrap-wrapper wrap">
        <h2>
    <span class="dashicons dashicons-search" style="line-height: inherit;"></span> 
    <?php echo $plugin_description ?>
    </h2>

        <div class="">

            <form id="gitsearch" class="form-inline well">
                <div class="form-group">
                    <input type="text" class="form-control" id="searchgit" name="search" placeholder="Search..." />
                </div>

                <select class="form-control" id="options">
                    <option value="+wordpress+theme+in:name,readme,description" selected>Themes</option>
                    <option value="+wordpress+plugin+in:name,readme,description">Plugins</option>
                    <option value="+wordpress+widget+in:name,readme,description">Widget</option>
                </select>

                <!--
<span class="radio radio-primary">
           <input type="radio" name="radio1" id="radio1" value="Themes" checked>
            <label class="" for="radio1">Themes</label>
            </span>

<span class="radio radio-primary">
            <input type="radio" name="radio1" id="radio2" value="Plugins">
            <label class="" for="radio2">Plugins</label>          
          </span>
-->

                <button class="btn btn-primary" type="submit">Search</button>
                <hr>
                <div id="resultsinfos">

                    <span id="nbrresults"></span>
                    <span id="limit_remaining" class="pull-right"></span>
                </div>

            </form>


            <div id="wpgit_results" class="row"></div>

            <div id="pagination">
                <div id="prevnext" class="well">
                    <span>
        <a href="#" id="previous">< PREVIOUS</a>
        </span>
                    <span>
        <span class="separator">|</span>
                    <a href="#" id="Next"> NEXT ></a>
                    </span>
                </div>
            </div>

        </div>


    </div>
    <!--Wrap end -->
    <!--End basic Table -->
    <script type="text/javascript">
        var plugin_url = "<?php echo $plugin_url ; ?>";

        /* jquery function to add to admin */
        jQuery(function($) {

            // jquery function to add to admin

        });

        /* jquery ready function to add to admin */
        jQuery(document).ready(function($) {

            // jquery ready function to add to admin

        });
        // Ready end

    </script>

    <style>
        /* plugin style admin */
        
        .admin_style {
            margin: 10px;
        }

    </style>

    <?php } // End Setting function

// Admin plugin setting
function wpgit_admin_menu()
{
    // this is where we add our plugin to the admin menu
    add_options_page('WPGIT settings', 'WPGIT', 'read', basename(__FILE__), 'wpgit_settings');
}

add_action('admin_menu', 'wpgit_admin_menu');



add_action('plugin_action_links_' . plugin_basename(__FILE__), 'wpgit_adminbar');
function wpgit_adminbar($links){

    $new_links = array();

    $adminlink = get_bloginfo('wpurl').'/wp-admin/';

    $wpgit_link = 'http://mywebsite.com';

    $new_links[] = '<a href="'.$adminlink.'options-general.php?page=wpgit.php">Settings</a>';

    return array_merge($links,$new_links );

}
?>
