/**
 * LC Kit - Header / Footer Builder "Display Settings" meta box.
 *
 * Shows only the condition sub-field that matches the selected "Display On" value.
 *
 * @package LC_Addons_Kit_For_Elementor
 */

( function () {
	'use strict';

	function init() {
		var select = document.getElementById( 'lcake_hf_condition' );
		var fields = document.querySelectorAll( '.lcake-hf-condition-field' );

		if ( ! select || ! fields.length ) {
			return;
		}

		function toggle() {
			Array.prototype.forEach.call( fields, function ( field ) {
				field.hidden = field.getAttribute( 'data-show-for' ) !== select.value;
			} );
		}

		select.addEventListener( 'change', toggle );
		toggle();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
