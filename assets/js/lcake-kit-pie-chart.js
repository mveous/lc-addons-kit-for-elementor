/**
 * LC Kit - Pie Chart widget.
 *
 * Paints each chart's slices from the conic-gradient stops the widget
 * renders into the data-gradient attribute.
 *
 * @package LC_Addons_Kit_For_Elementor
 */

( function ( $ ) {
	'use strict';

	var PieChartWidget = function ( $scope ) {
		$scope.find( '.lcake-pie-chart[data-gradient]' ).each( function () {
			var gradient = this.getAttribute( 'data-gradient' );

			if ( gradient ) {
				this.style.backgroundImage = 'conic-gradient(' + gradient + ')';
			}
		} );
	};

	$( window ).on( 'elementor/frontend/init', function () {
		if ( 'undefined' !== typeof elementorFrontend ) {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/lcake-kit-pie-chart.default', PieChartWidget );
		}
	} );
}( jQuery ) );
