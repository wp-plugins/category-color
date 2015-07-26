<?php
/*
Plugin Name: Category Color
Plugin URI: https://wordpress.org/plugins/category-color/
Description: Easily set a custom color per Post Category and use the colors in your Wordpress templates to spice up your theme.
Version: 1.2
Author: Zayed Baloch, Naeem Nur
Author URI: http://www.radlabs.biz/
License: GPL2+
*/

class RadLabs_Category_Colors{
    protected $_meta;
    protected $_taxonomies;
    protected $_fields;

    function __construct( $meta ){
        if ( !is_admin() )
            return;
        $this->_meta = $meta;
        $this->normalize();

        add_action( 'admin_init', array( $this, 'add' ), 100 );
        add_action( 'edit_term', array( $this, 'save' ), 10, 2 );
        add_action( 'delete_term', array( $this, 'delete' ), 10, 2 );
        add_action( 'load-edit-tags.php', array( $this, 'load_edit_page' ) );
    }

    /********************************
     * Enqueue scripts and styles
     ********************************/
    function load_edit_page(){
        $screen = get_current_screen();
        if(
            'edit-tags' != $screen->base
            || empty( $_GET['action'] ) || 'edit' != $_GET['action']
            || !in_array( $screen->taxonomy, $this->_taxonomies )
        ){
            return;
        }

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_head', array( $this, 'output_css' ) );
        add_action( 'admin_footer', array( $this, 'output_js' ), 100 );
    }

    /*******************************
     * Enqueue scripts and styles
     *******************************/
    function admin_enqueue_scripts(){
        wp_enqueue_script( 'jquery' );
        $this->check_field_color();
    }

    // Output CSS into header
    function output_css(){
        echo $this->css ? '<style>' . $this->css . '</style>' : '';
    }

    // Output JS into footer
    function output_js(){
        echo $this->js ? '<script>jQuery(function($){' . $this->js . '});</script>' : '';
    }

    /***************
     * COLOR FIELD
     ***************/

    // Check field color
    function check_field_color(){
        if ( !$this->has_field( 'color' ) )
            return;
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        $this->js .= '$(".color").wpColorPicker();';
    }

    /*****************
     * META BOX PAGE
     *****************/

    // Add meta fields for taxonomies
    function add(){
        foreach ( get_taxonomies() as $tax_name ) {
            if ( in_array( $tax_name, $this->_taxonomies ) ) {
                add_action( $tax_name . '_edit_form', array( $this, 'show' ), 9, 2 );
            }
        }
    }

    // Show meta fields
    function show( $tag, $taxonomy ){
        // get meta fields from option table
        $metas = get_option( $this->_meta['id'] );
        if ( empty( $metas ) ) $metas = array();
        if ( !is_array( $metas ) ) $metas = (array) $metas;

        // get meta fields for current term
        $metas = isset( $metas[$tag->term_id] ) ? $metas[$tag->term_id] : array();

        wp_nonce_field( basename( __FILE__ ), 'radlabs_taxonomy_meta_nonce' );

        echo "<h3>{$this->_meta['title']}</h3>
            <table class='form-table'>";
        foreach ( $this->_fields as $field ) {
            echo '<tr>';

            $meta = !empty( $metas[$field['id']] ) ? $metas[$field['id']] : $field['std'];
            $meta = is_array( $meta ) ? array_map( 'esc_attr', $meta ) : esc_attr( $meta );
            call_user_func( array( $this, 'show_field_' . $field['type'] ), $field, $meta );

            echo '</tr>';
        }
        echo '</table>';
    }

    /*******************
     * META BOX FIELDS
     *******************/

    function show_field_begin( $field, $meta ) {
        echo "<th scope='row' valign='top'><label for='{$field['id']}'>{$field['name']}</label></th><td>";
    }

    function show_field_end( $field, $meta ) {
        echo $field['desc'] ? "<br><span class='description'>{$field['desc']}</span></td>" : '</td>';
    }

    function show_field_color( $field, $meta ){
        if ( empty( $meta ) ) $meta = '#';
        $this->show_field_begin( $field, $meta );
        echo "<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' class='color'>";
        $this->show_field_end( $field, $meta );
    }


    /*****************
     * META BOX SAVE
     *****************/

    // Save meta fields
    function save( $term_id, $tt_id ) {
        $metas = get_option( $this->_meta['id'] );
        if ( !is_array( $metas ) )
            $metas = (array) $metas;
        $meta = isset( $metas[$term_id] ) ? $metas[$term_id] : array();
        foreach ( $this->_fields as $field ) {
            $name = $field['id'];
            $new = isset( $_POST[$name] ) ? $_POST[$name] : ( $field['multiple'] ? array() : '' );
            $new = is_array( $new ) ? array_map( 'stripslashes', $new ) : stripslashes( $new );
            if ( empty( $new ) ) {
                unset( $meta[$name] );
            } else {
                $meta[$name] = $new;
            }
        }
        $metas[$term_id] = $meta;
        update_option( $this->_meta['id'], $metas );
    }

    /******************
    * META BOX DELETE
    *******************/

    function delete( $term_id, $tt_id ){
        $metas = get_option( $this->_meta['id'] );
        if ( !is_array( $metas ) ) $metas = (array) $metas;
        unset( $metas[$term_id] );
        update_option( $this->_meta['id'], $metas );
    }

    /*********************
     * HELPER FUNCTIONS
     *********************/

    function normalize(){
        // Default values for meta box
        $this->_meta = array_merge( array(
            'taxonomies' => array( 'category', 'post_tag' )
        ), $this->_meta );

        $this->_taxonomies = $this->_meta['taxonomies'];
        $this->_fields = $this->_meta['fields'];

    }

    // Check if field with $type exists
    function has_field( $type ) {
        foreach ( $this->_fields as $field ) {
            if ( $type == $field['type'] ) return true;
        }
        return false;
    }
}

//Load Texonomy metaboxes
require_once('fields.php');

function rl_color($catid){
    $meta = get_option('rl_category_meta');
    $meta = isset($meta[$catid]) ? $meta[$catid] : array();
    $yt_cat_color = $meta['rl_cat_color'];
    return $yt_cat_color;
}