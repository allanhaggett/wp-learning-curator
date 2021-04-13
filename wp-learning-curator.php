<?php
/*
Plugin Name: Learning Curator
Plugin URI: https://github.com/allanhaggett/wp-learning-curator
Description: Access learning resources on demand
Author: Allan Haggett <allan.haggett@gov.bc.ca>
Version: 1
Author URI: https://learningcurator.gww.gov.bc.ca
*/

/**
 * This plugin enables a custom content type, and several custom taxonomies to along 
 * with it. We'll create a "pathway" type and associate taxonomies  
 * 
 */

/**
 * Start by defining the pathway content type, then start tacking on our taxonomies
 */
function my_custom_post_pathway() {
    $labels = array(
        'name'               => _x( 'Pathways', 'post type general name' ),
        'singular_name'      => _x( 'Pathway', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'pathway' ),
        'add_new_item'       => __( 'Add New Pathway' ),
        'edit_item'          => __( 'Edit Pathway' ),
        'new_item'           => __( 'New Pathway' ),
        'all_items'          => __( 'All Pathways' ),
        'view_item'          => __( 'View Pathway' ),
        'search_items'       => __( 'Search Pathways' ),
        'not_found'          => __( 'No pathways found' ),
        'not_found_in_trash' => __( 'No pathways found in the Trash' ), 
        'parent_item_colon'  => __( 'Parent pathway:' ),
        'menu_name'          => 'Pathways'
    );
    $args = array(
        'labels'              => $labels,
        'description'         => 'Categorized topics contain pathways of activities.',
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'page',
        'hierarchical'        => true,
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),
    );
    register_post_type( 'pathway', $args ); 
}
add_action( 'init', 'my_custom_post_pathway' );


/**
 * Start applying various taxonomies; start with the methods, 
 * then init them all in one place
 */



/** 
 * Activity types read, watch,listen, participate
 */
function my_taxonomies_activity_types () {
    $labels = array(
        'name'              => _x( 'Activity Types', 'taxonomy general name' ),
        'singular_name'     => _x( 'Activity Type', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Activity Types' ),
        'all_items'         => __( 'All Activity Types' ),
        'parent_item'       => __( 'Parent Activity Type' ),
        'parent_item_colon' => __( 'Activity Types:' ),
        'edit_item'         => __( 'Edit Activity Type' ), 
        'update_item'       => __( 'Update Activity Type' ),
        'add_new_item'      => __( 'Add Activity Type' ),
        'new_item_name'     => __( 'New Activity Type' ),
        'menu_name'         => __( 'Activity Types' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'show_in_rest' => true,
    );
    register_taxonomy( 'activity_type', 'pathway', $args );
}

/** 
 * Now let's initiate 
 */


add_action( 'init', 'my_taxonomies_activity_types', 0 );

/**
 * Now let's make sure that we're using our own customized template
 * so that pathways can show the meta data in a customizable fashion.
 *  
 * #TODO extend this to include archive.php for main index page
 * and also taxonomy pages
 * 
 */
function load_pathway_template( $template ) {
    global $post;
    if ( 'pathway' === $post->post_type && locate_template( array( 'single-pathway.php' ) ) !== $template ) {
        /*
         * This is a 'pathway' page
         * AND a 'single pathway template' is not found on
         * theme or child theme directories, so load it
         * from our plugin directory.
         */
        return plugin_dir_path( __FILE__ ) . 'single-pathway.php';
    }
    return $template;
}

function pathway_archive_template( $archive_template ) {
     global $post;
     if ( is_post_type_archive ( 'pathway' ) ) {
          $archive_template = dirname( __FILE__ ) . '/archive-pathway.php';
     }
     return $archive_template;
}

function pathway_tax_template( $tax_template ) {
    global $post;
    if ( is_tax ( 'activity_type' ) ) {
         $tax_template = dirname( __FILE__ ) . '/taxonomy.php';
    }
    return $tax_template;
}

add_filter( 'single_template', 'load_pathway_template' );
add_filter( 'archive_template', 'pathway_archive_template');
add_filter( 'taxonomy_template', 'pathway_tax_template');

