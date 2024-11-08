<?php

if ( ! function_exists( 'get_editable_roles' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/user.php' );
}

add_action('cmb2_admin_init', function() {
	$cmb_obj = cmb2_get_metabox( 'achievement_data' );

	$prefix  = "_badgeos_";
	$options = array(
		'none' => __( 'None', 'badgeos-award-role' )
	);
	foreach ( get_editable_roles() as $role_name => $role_info ) {
		$options[$role_name] = $role_info['name'];
	}
	$cmb_obj->add_field( array(
		'name'    => __( 'Award Role', 'badgeos-award-role' ),
		'desc'    => ' ' . __( 'Role which should be awarded to the user when earning this achievement.', 'badgeos-award-role' ),
		'id'      => $prefix . 'award_role',
		'type'    => 'select',
		'options' => $options,
		'default' => 'none',
	) );

}, 99 );

add_action( 'badgeos_award_achievement', function ( $user_id, $achievement_id ) {
	$role = get_post_meta( $achievement_id, '_badgeos_award_role', true );

	if ( $role && $role != 'none' ) {
		$user = new WP_User( $user_id );

		// Add role
		$user->add_role( $role );
	}
}, 10, 2 );