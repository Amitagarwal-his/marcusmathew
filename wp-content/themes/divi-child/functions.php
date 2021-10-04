<?php

function my_theme_enqueue_styles() { 

    wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); 

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/custom.css' );
    wp_enqueue_style( 'regular', get_stylesheet_directory_uri() . '/regular.css' );


    /****** Js *****/
    wp_enqueue_script( 'his-custom', get_stylesheet_directory_uri() . '/js/his-custom.js' );
    wp_enqueue_script('his-custom'); // I assume you registered it somewhere else
    wp_localize_script('his-custom', 'ajax_custom', array(
       'ajaxurl' => admin_url('admin-ajax.php')
    ));

}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function exportescscript (){
    
       
        
     wp_enqueue_script( 'csv-script', get_stylesheet_directory_uri() . '/js/csv-script.js' );
    wp_enqueue_script('csv-script'); // I assume you registered it somewhere else
    wp_localize_script('csv-script', 'csv_script', array(
       'ajaxurl' => admin_url('admin-ajax.php')
    ));

}


add_action( 'admin_enqueue_scripts', 'exportescscript' );


 include_once(dirname(__FILE__) . "/inc/signup-details.php");

add_shortcode('marc_custom_form', 'marc_custom_form');
function marc_custom_form(){
    $formhtml = '<div class="donateform">
              <div class="row">
                
                    <div class="column6 form-clear">
                    <div class="formgroup">
                      <input type="text" class="input-control" name="firstName" id="firstName" placeholder="First Name" />
                    </div>
                    </div>
                    <div class="column6 form-clear">
                    <div class="formgroup">
                      <input type="text" class="input-control" name="lastName" id="lastName" placeholder="Last Name" />
                    </div>
                    </div>
                    <div class="column12 form-clear">
                        <div class="formgroup">
                        <input type="email" class="input-control" name="userEmail" id="userEmail" placeholder="E-mail Address" />
                    </div>
                </div>
                <div class="column12">
                  <div class="form-btn-wrapper">
                    <input type="submit" class="btn btn-black" name="signUp" id="signUp" value="Sign-Up" />

                    <a class="dntbtn" href="https://www.ifundraise.ie/5139_marcus-matthews-for-seanad-nui-panel.html">Donate</a>
                   
                   
                  </div>
                </div>
                <div class="column12">
                    <span>By signing up to this mailing list you agree to our <a href="https://www.marcusmatthews.ie/privacy-policy/">privacy policy</a></span>
                </div>
              </div>
            </div>';
    return $formhtml;
}


add_action("wp_ajax_custom_signup", "custom_signup");
add_action("wp_ajax_nopriv_custom_signup", "custom_signup");

function custom_signup() {
    extract($_POST);
    $response = '';
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 

    if($firstname == '' && $lastname == '' && $useremail == '' ){
        $response = 'fullempty';
    }else if($lastname == '' && $useremail == ''){
        $response = 'empty';
    }else if($firstname == ''){
        $response = 'firstname';
    }elseif($lastname == '') {
        $response = 'lastname';
    }elseif($useremail == ''){
        $response = 'email';
    }elseif(!filter_var($useremail, FILTER_VALIDATE_EMAIL)) {
        $response = 'invalid';
    }elseif($signcheck == 'false'){
        $response = 'checkbox';
    }else {
        global $wpdb;
        $tablename = $wpdb->prefix."signup_details";
        $select = $wpdb->get_results('SELECT * FROM '.$tablename.'');
        foreach($select as $selectdata){
            $checkemail = $selectdata->email;
        }
        if($checkemail == $useremail) {
            $response = 'emailexist';
        }else {
            $result = $wpdb->insert($tablename, array('first_name' => $firstname,'email' => $useremail,'last_name' => $lastname, 'create_at' => time()));
            if($result){
                marc_registration($firstname, $lastname, $useremail);
                $response = 'success';
            }else {
                $response = 'failed';
            }
        }   
    }
    echo $response;
    die();
}


function marc_registration($firstname, $lastname, $useremail) {

    $htmlContent = ' 
        <html> 
        <head> 
            <title>Welcome to Marcusmatthews</title> 
        </head> 
        <body> 
            <h3>New user joined with you!</h3> 
            <div> 
                <table width="500" cellpadding="10">
                <tr>
                    <td>First Name</td>
                    <td>'.$firstname.'</td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td>'.$lastname.'</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>'.$useremail.'</td>
                </tr>
                </table>
            </div> 
        </body> 
        </html>'; 
     
    // Set content-type header for sending HTML email 
    $headers = "MIME-Version: 1.0" . "\r\n"; 
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
    // send an email out to user
    wp_mail( 'info@marcusmatthews.ie', __('Marcusmatthews new user signup','text-domain'),$htmlContent , $headers);
}


// create shortcode to list all clothes which come in blue
add_shortcode( 'media-posts', 'his_post_shortcode' );
function his_post_shortcode( $atts ) {
    ob_start();
    $query = new WP_Query( array(
        'post_type' => 'broadband',
        'posts_per_page' => 3,
        'order' => 'ASC',
    ) );
    if ( $query->have_posts() ) { ?>
        <div class="media-listing custom-list">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <a href="<?php the_permalink();?>">
                    <div class="media-thumb">
                    <?php the_post_thumbnail();?>
                    </div>
                    <div class="media-content">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <div class="media-post-des">
                            <?php
                                $content = get_the_content();
                                $trimmed_content = wp_trim_words( $content, 15, NULL );
                                echo $trimmed_content;
                            ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
                    </div>
                </a>
            </article>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
            <div class="totalcustompost" style="visibility: hidden;">
                <?php echo $published_posts = wp_count_posts('broadband')->publish; ?>
            </div>
            <p class="text-center">
                <a id="more_custom_posts" href="javascript:void(0);" data-offset="3">See more</a>
            </p>
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}


/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Broadband', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Broadband', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Broadband', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Broadband', 'twentythirteen' ),
        'all_items'           => __( 'All Broadband', 'twentythirteen' ),
        'view_item'           => __( 'View Broadband', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Broadband', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Broadband', 'twentythirteen' ),
        'update_item'         => __( 'Update Broadband', 'twentythirteen' ),
        'search_items'        => __( 'Search Broadband', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'broadband', 'twentythirteen' ),
        'description'         => __( 'Broadband', 'twentythirteen' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'broadband', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );


function custom_broadband_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Categories', 'taxonomy singular name' ),
    'search_items' =>  __( 'Categories' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => __( 'Parent Categories' ),
    'parent_item_colon' => __( 'Parent Categories:' ),
    'edit_item' => __( 'Edit Categories' ), 
    'update_item' => __( 'Update Categories' ),
    'add_new_item' => __( 'Add New Categories' ),
    'new_item_name' => __( 'New Category Name' ),
    'menu_name' => __( 'Categories' ),
  );    
 
// Now register the taxonomy
 
  register_taxonomy('broadband-category',array('broadband'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'broadband-category' ),
  ));
 
}
add_action( 'init', 'custom_broadband_taxonomy', 0 );


function custom_post_type_accordion() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Accordion', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Accordion', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Accordion', 'twentythirteen' ),
        'parent_item_colon'   => __( 'Parent Accordion', 'twentythirteen' ),
        'all_items'           => __( 'All Accordion', 'twentythirteen' ),
        'view_item'           => __( 'View Accordion', 'twentythirteen' ),
        'add_new_item'        => __( 'Add New Accordion', 'twentythirteen' ),
        'add_new'             => __( 'Add New', 'twentythirteen' ),
        'edit_item'           => __( 'Edit Accordion', 'twentythirteen' ),
        'update_item'         => __( 'Update Accordion', 'twentythirteen' ),
        'search_items'        => __( 'Search Accordion', 'twentythirteen' ),
        'not_found'           => __( 'Not Found', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'accordion', 'twentythirteen' ),
        'description'         => __( 'Accordion', 'twentythirteen' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor'),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'accordion', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type_accordion', 0 );


add_shortcode( 'accordion', 'his_post_accordion_shortcode' );
function his_post_accordion_shortcode( $atts ) {
    ob_start();
    $query = new WP_Query( array(
        'post_type' => 'accordion',
        'posts_per_page' => -1,
        'order' => 'DSC',
    ) );
    if ( $query->have_posts() ) { ?>
        <div class="accordion-container">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div class="set">
                <a href="javascript:void(0)">
                  <span class="accordin-icon">
                    <?php the_field('accordion_icon');?>
                  </span>
                  <?php the_title();?> 
                  <span class="toggleright_icon"><i class="fa fa-chevron-down"></i></span>
                </a>
                <div class="content">
                    <?php the_content(); ?>
                </div>
            </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}



// create shortcode to list all clothes which come in blue
add_shortcode( 'blog-posts', 'his_blog_shortcode' );
function his_blog_shortcode( $atts ) {
    ob_start();
    $query = new WP_Query( array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'order' => 'ASC',
    ) );
    if ( $query->have_posts() ) { ?>
        <div class="media-listing">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="media-thumb">
                         <a href="<?php the_field('post_url'); ?>" target="_blank">
                            <?php the_post_thumbnail();?>
                         </a>
                    </div>
                    <div class="media-content">
                        <a href="<?php the_field('post_url'); ?>" target="_blank"><?php the_title(); ?></a>
                        <div class="media-post-des">
                            <?php
                                the_content();
                            ?>
                            <a href="<?php the_field('post_url'); ?>" class="read-more" target="_blank">Read More</a>
                        </div>
                    </div>
            </article>
            <?php endwhile;
            wp_reset_postdata(); ?>
            
        </div>
            <!-- <div class="totalpostcounts" style="visibility: hidden;">
                <?php echo $published_posts = wp_count_posts('post')->publish; ?>
            </div>
            <p class="text-center">
                <a id="more_posts" href="javascript:void(0);" data-offset="3">See more</a>
            </p> -->
    <?php $myvariable = ob_get_clean();
    return $myvariable;
    }
}


add_action('wp_ajax_nopriv_more_post_ajax', 'more_post_ajax'); 
add_action('wp_ajax_more_post_ajax', 'more_post_ajax');
function more_post_ajax(){
    $ppp = $_POST["ppp"];
    $offset = $_POST["offset"];
    $response = '';
    $args = [
        'suppress_filters' => true,
        'post_type' => 'post',
        'posts_per_page' => $ppp,
        'offset' => $offset,
        'order' => 'ASC',
    ];
    $loop = new WP_Query($args);
    while ($loop->have_posts()) { $loop->the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="media-thumb">
                 <a href="<?php the_field('post_url'); ?>" target="_blank">
                    <?php the_post_thumbnail();?>
                 </a>
            </div>
            <div class="media-content">
                <a href="<?php the_field('post_url'); ?>" target="_blank"><?php the_title(); ?></a>
                <div class="media-post-des">
                    <?php the_content(); ?>
                    <a href="<?php the_field('post_url'); ?>" class="read-more" target="_blank">Read More</a>
                </div>
            </div>
        </article>
    <?php }
    $response = 'success';
    die(); 
}

add_action('wp_ajax_nopriv_more_custom_post_ajax', 'more_custom_post_ajax'); 
add_action('wp_ajax_more_custom_post_ajax', 'more_custom_post_ajax');
function more_custom_post_ajax(){
    $ppp = $_POST["ppp"];
    $offset = $_POST["offset"];
    $response = '';
    $args = [
        'suppress_filters' => true,
        'post_type' => 'broadband',
        'posts_per_page' => $ppp,
        'offset' => $offset,
        'order' => 'ASC',
    ];
    $loop = new WP_Query($args);
    while ($loop->have_posts()) { $loop->the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="media-thumb">
                 <a href="<?php the_permalink(); ?>" target="_blank">
                    <?php the_post_thumbnail();?>
                 </a>
            </div>
            <div class="media-content">
                <a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?></a>
                <div class="media-post-des">
                    <?php the_content(); ?>
                    <a href="<?php the_permalink(); ?>" class="read-more" target="_blank">Read More</a>
                </div>
            </div>
        </article>
    <?php }
    $response = 'success';
    die(); 
}


/*
function export_csv (){
global $wpdb;
    $query = $wpdb->get_results("SELECT * FROM wp_signup_details ORDER BY id ASC",ARRAY_A);
    //print_r($query);
    if($query){
        $delimiter = ",";
        $filename = "members_" . date('Y-m-d') . ".csv";
        //create a file pointer
        $f = fopen('php://memory', 'w');
        //set column headers
        $fields = array('ID', 'F Name', 'L Name', 'Email', 'Created');
        fputcsv($f, $fields, $delimiter);
        //output each row of the data, format line as csv and write to file pointer
        foreach($query as $res){
            $lineData = array($res['id'], $res['first_name'], $res['last_name'], $res['email'], $res['create_at']);
            fputcsv($f, $lineData, $delimiter);
        }
        //move back to beginning of file
        fseek($f, 0);
        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        //output all remaining data on a file pointer
        fpassthru($f);
    }
 wp_die();
}
add_action('wp_ajax_export_csv','export_csv');*/

add_action('admin_footer', 'mytheme_export_users');

function mytheme_export_users() {
    $screen = get_current_screen();
    if ( $_REQUEST['page'] != 'signup_details' )   // Only add to users.php page
        return;
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($)
        {
            $('.tablenav.top .clear, .tablenav.bottom .clear').before('<form action="#" method="POST"><input type="hidden" id="mytheme_export_csv" name="mytheme_export_csv" value="1" /><input class="button button-primary user_export_button" style="margin-top:3px;" type="submit" value="<?php esc_attr_e('Export All as CSV', 'mytheme');?>" /></form>');
        });
    </script>
    <?php
}

add_action('admin_init', 'export_csv'); //you can use admin_init as well

function export_csv() {
    if (!empty($_POST['mytheme_export_csv'])) {

        if (current_user_can('manage_options')) {
            header("Content-type: application/force-download");
            header('Content-Disposition: inline; filename="sign-up-details'.date('YmdHis').'.csv"');

            // WP_User_Query arguments
            $args = array (
                'order'          => 'ASC',
                'orderby'        => 'id',
                'fields'         => 'all',
            );

            // The Query
            global $wpdb;
            $query = $wpdb->get_results("SELECT * FROM wp_signup_details ORDER BY id ASC",ARRAY_A);
            echo 'ID,First Name,Last Name,Email,Created'."\r\n";
            
            foreach ( $query as $res ) {

                echo '"' . $res['id'] . '","' . $res['first_name'] . '","' . $res['last_name'] . '","' . $res['email'] . '","' . $res['create_at'] . '"' . "\r\n";
            }

            exit();
        }
    }
}
