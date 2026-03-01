<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Dope_Posts_Plugin {
	const MINIMUM_ELEMENTOR_VERSION = '3.20.0';
	const MINIMUM_PHP_VERSION       = '7.4';

	private static $instance = null;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init(): void {
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
			return;
		}

		if ( version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_ajax_dope_posts_filter', array( $this, 'ajax_filter_posts' ) );
		add_action( 'wp_ajax_nopriv_dope_posts_filter', array( $this, 'ajax_filter_posts' ) );
	}

	public function register_assets(): void {
		wp_register_style(
			'dope-posts-widget',
			DOPE_POSTS_URL . 'assets/css/dope-posts-widget.css',
			array(),
			DOPE_POSTS_VERSION
		);

		wp_register_script(
			'dope-posts-widget',
			DOPE_POSTS_URL . 'assets/js/dope-posts-widget.js',
			array( 'jquery' ),
			DOPE_POSTS_VERSION,
			true
		);

		wp_localize_script(
			'dope-posts-widget',
			'DopePostsWidget',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'dope_posts_nonce' ),
			)
		);
	}

	public function register_widgets( $widgets_manager ): void {
		require_once DOPE_POSTS_PATH . 'includes/widgets/class-dope-posts-masonry-widget.php';
		$widgets_manager->register( new Dope_Posts_Masonry_Widget() );
	}

	public function ajax_filter_posts(): void {
		check_ajax_referer( 'dope_posts_nonce', 'nonce' );

		if ( ! class_exists( 'Dope_Posts_Masonry_Widget' ) ) {
			require_once DOPE_POSTS_PATH . 'includes/widgets/class-dope-posts-masonry-widget.php';
		}

		$raw_settings = isset( $_POST['settings'] ) ? wp_unslash( (string) $_POST['settings'] ) : '{}';
		$settings     = json_decode( $raw_settings, true );
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}
		$settings = Dope_Posts_Masonry_Widget::normalize_settings( $settings );

		$request = array(
			'search'   => isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['search'] ) ) : '',
			'category' => isset( $_POST['category'] ) ? absint( $_POST['category'] ) : 0,
			'tag'      => isset( $_POST['tag'] ) ? absint( $_POST['tag'] ) : 0,
			'order'    => isset( $_POST['order'] ) ? sanitize_key( wp_unslash( (string) $_POST['order'] ) ) : 'newest',
		);
		$paged        = isset( $_POST['paged'] ) ? max( 1, absint( $_POST['paged'] ) ) : 1;
		$is_load_more = isset( $_POST['is_load_more'] ) && 1 === absint( $_POST['is_load_more'] );

		$args  = Dope_Posts_Masonry_Widget::build_query_args( $request, $paged, $settings );
		$query = new WP_Query( $args );
		$html  = Dope_Posts_Masonry_Widget::render_posts_markup( $query, $settings, $is_load_more && $paged > 1 );

		wp_send_json_success(
			array(
				'html'       => $html,
				'has_more'   => $paged < (int) $query->max_num_pages,
				'next_page'  => $paged + 1,
				'found_posts'=> (int) $query->found_posts,
			)
		);
	}

	public function admin_notice_missing_elementor(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-warning is-dismissible"><p>';
		echo esc_html__( 'Dope Posts requires Elementor to be installed and activated.', 'dope-posts' );
		echo '</p></div>';
	}

	public function admin_notice_minimum_elementor_version(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: 1: required Elementor version. */
					__( 'Dope Posts requires Elementor version %1$s or greater.', 'dope-posts' ),
					self::MINIMUM_ELEMENTOR_VERSION
				)
			)
		);
	}

	public function admin_notice_minimum_php_version(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: 1: required PHP version. */
					__( 'Dope Posts requires PHP version %1$s or greater.', 'dope-posts' ),
					self::MINIMUM_PHP_VERSION
				)
			)
		);
	}
}
