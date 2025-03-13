<?php
/*
 * Service CPT
 */

function utenzo_service_register() {

	$cpt_slug = get_theme_mod('utenzo_service_slug');

	if(isset($cpt_slug) && $cpt_slug != ''){
		$cpt_slug = $cpt_slug;
	} else {
		$cpt_slug = 'services';
	}

	$labels = array(
		'name'               => esc_html__( 'Services', 'utenzo' ),
		'singular_name'      => esc_html__( 'Service', 'utenzo' ),
		'add_new'            => esc_html__( 'Add New', 'utenzo' ),
		'add_new_item'       => esc_html__( 'Add New Service', 'utenzo' ),
		'all_items'          => esc_html__( 'All Services', 'utenzo' ),
		'edit_item'          => esc_html__( 'Edit Service', 'utenzo' ),
		'new_item'           => esc_html__( 'Add New Service', 'utenzo' ),
		'view_item'          => esc_html__( 'View Item', 'utenzo' ),
		'search_items'       => esc_html__( 'Search Services', 'utenzo' ),
		'not_found'          => esc_html__( 'No service(s) found', 'utenzo' ),
		'not_found_in_trash' => esc_html__( 'No service(s) found in trash', 'utenzo' )
	);

  $args = array(
		'labels'          => $labels,
		'public'          => true,
		'show_ui'         => true,
		'capability_type' => 'post',
		'hierarchical'    => false,
		'has_archive'     => true,
		'menu_icon'       => 'dashicons-admin-post',
		'rewrite'         => array('slug' => $cpt_slug), // Permalinks format
		'show_in_rest' 		=> true,
		'supports'        => array('title', 'editor', 'excerpt', 'thumbnail', 'comments')
  );

  add_filter( 'enter_title_here',  'utenzo_service_change_default_title');

  register_post_type( 'service' , $args );
}
add_action('init', 'utenzo_service_register', 1);


function utenzo_service_taxonomy() {

	register_taxonomy(
		"service_categories",
		array("service"),
		array(
			"hierarchical"   => true,
			"label"          => "Categories",
			"singular_label" => "Category",
			"rewrite"        => true
		)
	);

	register_taxonomy(
        'service_tag',
        'service',
        array(
            'hierarchical'  => false,
            'label'         => __( 'Tags', 'utenzo' ),
            'singular_name' => __( 'Tag', 'utenzo' ),
            'rewrite'       => true,
            'query_var'     => true
        )
    );

}
add_action('init', 'utenzo_service_taxonomy', 1);


function utenzo_service_change_default_title( $title ) {
	$screen = get_current_screen();

	if ( 'service' == $screen->post_type )
		$title = esc_html__( "Enter the service's name here", 'utenzo' );

	return $title;
}


function utenzo_service_edit_columns( $service_columns ) {
	$service_columns = array(
		"cb"                     => "<input type=\"checkbox\" />",
		"title"                  => esc_html__('Title', 'utenzo'),
		"thumbnail"              => esc_html__('Thumbnail', 'utenzo'),
		"service_categories" 			 => esc_html__('Categories', 'utenzo'),
		"date"                   => esc_html__('Date', 'utenzo'),
	);
	return $service_columns;
}
add_filter( 'manage_edit-service_columns', 'utenzo_service_edit_columns' );

function utenzo_service_column_display( $service_columns, $post_id ) {

	switch ( $service_columns ) {

		// Display the thumbnail in the column view
		case "thumbnail":
			$width = (int) 64;
			$height = (int) 64;
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );

			// Display the featured image in the column view if possible
			if ( $thumbnail_id ) {
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
			}
			if ( isset( $thumb ) ) {
				echo wp_kses_post( $thumb ); 
			} else {
				echo esc_html__('None', 'utenzo');
			}
			break;

		// Display the service tags in the column view
		case "service_categories":

		if ( $category_list = get_the_term_list( $post_id, 'service_categories', '', ', ', '' ) ) {
			echo wp_kses_post( $category_list );
		} else {
			echo esc_html__('None', 'utenzo');
		}
		break;
	}
}
add_action( 'manage_service_posts_custom_column', 'utenzo_service_column_display', 10, 2 );
