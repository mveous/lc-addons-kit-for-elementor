<?php
/**
 * Uninstall routine for LC Addons Kit for Elementor.
 *
 * Removes the plugin's settings. Header / Footer templates (the
 * lcake_hf_template posts) are user-created content and are intentionally
 * kept, so reinstalling the plugin restores them.
 *
 * @package LC_Addons_Kit_For_Elementor
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Deletes this plugin's data for the current site.
 *
 * @return void
 */
function lcake_eak_uninstall_site() {
	delete_option( 'lcake_kit_enabled_widgets' );

	// Stop telling Elementor that the (now removed) template post type is editable.
	$supported = get_option( 'elementor_cpt_support', array() );
	if ( is_array( $supported ) && in_array( 'lcake_hf_template', $supported, true ) ) {
		update_option( 'elementor_cpt_support', array_values( array_diff( $supported, array( 'lcake_hf_template' ) ) ) );
	}
}

if ( is_multisite() ) {
	$lcake_eak_site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);
	foreach ( $lcake_eak_site_ids as $lcake_eak_site_id ) {
		switch_to_blog( $lcake_eak_site_id );
		lcake_eak_uninstall_site();
		restore_current_blog();
	}
} else {
	lcake_eak_uninstall_site();
}
