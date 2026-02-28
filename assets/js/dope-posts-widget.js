( function( $ ) {
	'use strict';

	function debounce( fn, wait ) {
		var timeout = null;
		return function() {
			var context = this;
			var args = arguments;
			clearTimeout( timeout );
			timeout = setTimeout( function() {
				fn.apply( context, args );
			}, wait );
		};
	}

	function parseSettings( $widget ) {
		var raw = $widget.attr( 'data-settings' ) || '{}';
		try {
			return JSON.parse( raw );
		} catch ( err ) {
			return {};
		}
	}

	function initWidget( $widget ) {
		if ( ! $widget.length || $widget.data( 'dppwInitialized' ) ) {
			return;
		}
		$widget.data( 'dppwInitialized', true );

		var settings = parseSettings( $widget );
		var $grid = $widget.find( '.dppw-grid' ).first();
		var $search = $widget.find( '.dppw-search' ).first();
		var $category = $widget.find( '.dppw-category' ).first();
		var $tag = $widget.find( '.dppw-tag' ).first();
		var $order = $widget.find( '.dppw-order' ).first();
		var $loadMore = $widget.find( '.dppw-load-more' ).first();
		var isLoading = false;
		var currentPage = 1;

		function requestPosts( reset ) {
			if ( isLoading ) {
				return;
			}

			isLoading = true;
			$widget.addClass( 'is-loading' );
			var targetPage = reset ? 1 : currentPage + 1;

			$.post( DopePostsWidget.ajaxUrl, {
				action: 'dope_posts_filter',
				nonce: DopePostsWidget.nonce,
				paged: targetPage,
				search: $search.length ? $search.val() : '',
				category: $category.length ? $category.val() : 0,
				tag: $tag.length ? $tag.val() : 0,
				order: $order.length ? $order.val() : settings.default_order,
				settings: JSON.stringify( settings )
			} )
				.done( function( response ) {
					if ( ! response || ! response.success || ! response.data ) {
						return;
					}

					if ( reset ) {
						$grid.html( response.data.html );
						currentPage = 1;
					} else {
						$grid.append( response.data.html );
						currentPage = targetPage;
					}

					if ( response.data.has_more ) {
						$loadMore.removeAttr( 'hidden' );
					} else {
						$loadMore.attr( 'hidden', 'hidden' );
					}
				} )
				.always( function() {
					isLoading = false;
					$widget.removeClass( 'is-loading' );
				} );
		}

		var debouncedSearch = debounce( function() {
			requestPosts( true );
		}, 350 );

		if ( $search.length ) {
			$search.on( 'input', debouncedSearch );
		}

		if ( $category.length ) {
			$category.on( 'change', function() {
				requestPosts( true );
			} );
		}

		if ( $tag.length ) {
			$tag.on( 'change', function() {
				requestPosts( true );
			} );
		}

		if ( $order.length ) {
			$order.on( 'change', function() {
				requestPosts( true );
			} );
		}

		if ( $loadMore.length ) {
			$loadMore.on( 'click', function() {
				requestPosts( false );
			} );
		}
	}

	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/dope_posts_masonry.default', function( $scope ) {
			initWidget( $scope.find( '.dppw-widget' ).first() );
		} );
	} );

	$( function() {
		$( '.dppw-widget' ).each( function() {
			initWidget( $( this ) );
		} );
	} );
}( jQuery ) );
