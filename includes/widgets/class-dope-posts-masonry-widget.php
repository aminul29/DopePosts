<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Dope_Posts_Masonry_Widget extends Widget_Base {
	public function get_name(): string {
		return 'dope_posts_masonry';
	}

	public function get_title(): string {
		return esc_html__( 'Dope Posts Masonry', 'dope-posts' );
	}

	public function get_icon(): string {
		return 'eicon-posts-grid';
	}

	public function get_categories(): array {
		return array( 'general' );
	}

	public function get_keywords(): array {
		return array( 'posts', 'masonry', 'publication', 'grid', 'blog' );
	}

	public function get_style_depends(): array {
		return array( 'dope-posts-widget' );
	}

	public function get_script_depends(): array {
		return array( 'dope-posts-widget' );
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Posts Per Page', 'dope-posts' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 24,
				'default' => 6,
			)
		);

		$this->add_control(
			'excerpt_words',
			array(
				'label'   => esc_html__( 'Excerpt Words', 'dope-posts' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 80,
				'default' => 20,
			)
		);

		$this->add_control(
			'default_order',
			array(
				'label'   => esc_html__( 'Default Order', 'dope-posts' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'newest',
				'options' => self::get_order_options(),
			)
		);

		$this->add_control(
			'layout_mode',
			array(
				'label'   => esc_html__( 'Layout Mode', 'dope-posts' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'masonry',
				'options' => array(
					'masonry'       => esc_html__( 'Masonry', 'dope-posts' ),
					'featured_stack'=> esc_html__( 'Featured + Stacked', 'dope-posts' ),
				),
			)
		);

		$this->add_control(
			'featured_show_author',
			array(
				'label'        => esc_html__( 'Show Author', 'dope-posts' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'dope-posts' ),
				'label_off'    => esc_html__( 'Hide', 'dope-posts' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout_mode' => 'featured_stack',
				),
			)
		);

		$this->add_control(
			'featured_show_date',
			array(
				'label'        => esc_html__( 'Show Date', 'dope-posts' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'dope-posts' ),
				'label_off'    => esc_html__( 'Hide', 'dope-posts' ),
				'return_value' => 'yes',
				'condition'    => array(
					'layout_mode' => 'featured_stack',
				),
			)
		);

		$this->add_control(
			'show_search',
			array(
				'label'        => esc_html__( 'Show Search', 'dope-posts' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'dope-posts' ),
				'label_off'    => esc_html__( 'Hide', 'dope-posts' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_category_filter',
			array(
				'label'        => esc_html__( 'Show Category Filter', 'dope-posts' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'dope-posts' ),
				'label_off'    => esc_html__( 'Hide', 'dope-posts' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_tag_filter',
			array(
				'label'        => esc_html__( 'Show Topic (Tag) Filter', 'dope-posts' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Show', 'dope-posts' ),
				'label_off'    => esc_html__( 'Hide', 'dope-posts' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	private function register_style_controls(): void {
		$this->start_controls_section(
			'section_style_filters',
			array(
				'label' => esc_html__( 'Filters Bar', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'filters_gap',
			array(
				'label'      => esc_html__( 'Controls Gap', 'dope-posts' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 40,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-filters' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filters_margin_bottom',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'dope-posts' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-filters' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'filter_input_typography',
				'selector' => '{{WRAPPER}} .dppw-search, {{WRAPPER}} .dppw-category, {{WRAPPER}} .dppw-tag, {{WRAPPER}} .dppw-order',
			)
		);

		$this->add_control(
			'filter_input_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-search, {{WRAPPER}} .dppw-category, {{WRAPPER}} .dppw-tag, {{WRAPPER}} .dppw-order' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'filter_input_background',
				'selector' => '{{WRAPPER}} .dppw-search-wrap, {{WRAPPER}} .dppw-category, {{WRAPPER}} .dppw-tag, {{WRAPPER}} .dppw-order',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'filter_input_border',
				'selector' => '{{WRAPPER}} .dppw-search-wrap, {{WRAPPER}} .dppw-category, {{WRAPPER}} .dppw-tag, {{WRAPPER}} .dppw-order',
			)
		);

		$this->add_responsive_control(
			'filter_input_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'dope-posts' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-search-wrap, {{WRAPPER}} .dppw-category, {{WRAPPER}} .dppw-tag, {{WRAPPER}} .dppw-order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'filter_input_padding',
			array(
				'label'      => esc_html__( 'Padding', 'dope-posts' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-search-wrap, {{WRAPPER}} .dppw-category, {{WRAPPER}} .dppw-tag, {{WRAPPER}} .dppw-order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_grid',
			array(
				'label' => esc_html__( 'Grid', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'grid_columns',
			array(
				'label'          => esc_html__( 'Columns', 'dope-posts' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .dppw-grid' => 'column-count: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => esc_html__( 'Column Gap', 'dope-posts' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .dppw-card' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => esc_html__( 'Card', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'card_background',
				'selector' => '{{WRAPPER}} .dppw-card',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .dppw-card',
			)
		);

		$this->add_responsive_control(
			'card_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'dope-posts' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .dppw-card',
			)
		);

		$this->add_responsive_control(
			'card_content_padding',
			array(
				'label'      => esc_html__( 'Content Padding', 'dope-posts' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_meta',
			array(
				'label' => esc_html__( 'Meta', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .dppw-meta',
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-meta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'meta_category_color',
			array(
				'label'     => esc_html__( 'Category Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-meta-category' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Title', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .dppw-title, {{WRAPPER}} .dppw-title a',
			)
		);

		$this->start_controls_tabs( 'tabs_title_colors' );

		$this->start_controls_tab(
			'tab_title_normal',
			array(
				'label' => esc_html__( 'Normal', 'dope-posts' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			array(
				'label' => esc_html__( 'Hover', 'dope-posts' ),
			)
		);

		$this->add_control(
			'title_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_excerpt',
			array(
				'label' => esc_html__( 'Excerpt', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .dppw-excerpt',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_readmore',
			array(
				'label' => esc_html__( 'Read More', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'readmore_typography',
				'selector' => '{{WRAPPER}} .dppw-readmore',
			)
		);

		$this->start_controls_tabs( 'tabs_readmore_colors' );

		$this->start_controls_tab(
			'tab_readmore_normal',
			array(
				'label' => esc_html__( 'Normal', 'dope-posts' ),
			)
		);

		$this->add_control(
			'readmore_color',
			array(
				'label'     => esc_html__( 'Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-readmore' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_readmore_hover',
			array(
				'label' => esc_html__( 'Hover', 'dope-posts' ),
			)
		);

		$this->add_control(
			'readmore_hover_color',
			array(
				'label'     => esc_html__( 'Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-readmore:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_load_more',
			array(
				'label' => esc_html__( 'Load More Button', 'dope-posts' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'load_more_typography',
				'selector' => '{{WRAPPER}} .dppw-load-more',
			)
		);

		$this->add_responsive_control(
			'load_more_padding',
			array(
				'label'      => esc_html__( 'Padding', 'dope-posts' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'load_more_border',
				'selector' => '{{WRAPPER}} .dppw-load-more',
			)
		);

		$this->add_responsive_control(
			'load_more_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'dope-posts' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dppw-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_load_more_colors' );

		$this->start_controls_tab(
			'tab_load_more_normal',
			array(
				'label' => esc_html__( 'Normal', 'dope-posts' ),
			)
		);

		$this->add_control(
			'load_more_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-load-more' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-load-more' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_load_more_hover',
			array(
				'label' => esc_html__( 'Hover', 'dope-posts' ),
			)
		);

		$this->add_control(
			'load_more_hover_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-load-more:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_hover_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-load-more:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_hover_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'dope-posts' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dppw-load-more:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings     = self::normalize_settings( $this->get_settings_for_display() );
		$widget_id    = 'dppw-' . $this->get_id();
		$layout_class = 'dppw-layout--' . $settings['layout_mode'];
		$request      = array(
			'search'   => '',
			'category' => 0,
			'tag'      => 0,
			'order'    => $settings['default_order'],
		);

		$args  = self::build_query_args( $request, 1, $settings );
		$query = new WP_Query( $args );

		$categories = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
			)
		);
		$tags       = get_terms(
			array(
				'taxonomy'   => 'post_tag',
				'hide_empty' => true,
			)
		);

		?>
		<div
			class="<?php echo esc_attr( 'dppw-widget ' . $layout_class ); ?>"
			id="<?php echo esc_attr( $widget_id ); ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $settings ) ); ?>"
		>
			<div class="dppw-filters">
				<?php if ( 'yes' === $settings['show_search'] ) : ?>
					<label class="dppw-search-wrap">
						<span class="dppw-search-icon" aria-hidden="true"></span>
						<input
							type="search"
							class="dppw-search"
							placeholder="<?php echo esc_attr__( 'Search publications...', 'dope-posts' ); ?>"
							aria-label="<?php echo esc_attr__( 'Search posts', 'dope-posts' ); ?>"
						/>
					</label>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_category_filter'] ) : ?>
					<select class="dppw-category" aria-label="<?php echo esc_attr__( 'Filter by category', 'dope-posts' ); ?>">
						<option value="0"><?php echo esc_html__( 'All Categories', 'dope-posts' ); ?></option>
						<?php if ( is_array( $categories ) ) : ?>
							<?php foreach ( $categories as $category ) : ?>
								<option value="<?php echo esc_attr( (string) $category->term_id ); ?>">
									<?php echo esc_html( $category->name ); ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_tag_filter'] ) : ?>
					<select class="dppw-tag" aria-label="<?php echo esc_attr__( 'Filter by topic', 'dope-posts' ); ?>">
						<option value="0"><?php echo esc_html__( 'All Topics', 'dope-posts' ); ?></option>
						<?php if ( is_array( $tags ) ) : ?>
							<?php foreach ( $tags as $tag ) : ?>
								<option value="<?php echo esc_attr( (string) $tag->term_id ); ?>">
									<?php echo esc_html( $tag->name ); ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				<?php endif; ?>

				<select class="dppw-order" aria-label="<?php echo esc_attr__( 'Order posts', 'dope-posts' ); ?>">
					<?php foreach ( self::get_order_options() as $value => $label ) : ?>
						<option
							value="<?php echo esc_attr( $value ); ?>"
							<?php selected( $settings['default_order'], $value ); ?>
						>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="dppw-grid" data-page="1">
				<?php echo self::render_posts_markup( $query, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="dppw-actions">
				<button
					type="button"
					class="dppw-load-more"
					<?php echo ( $query->max_num_pages > 1 ) ? '' : 'hidden'; ?>
				>
					<?php echo esc_html__( 'Load More Publications', 'dope-posts' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	public static function normalize_settings( array $settings ): array {
		$defaults = array(
			'posts_per_page'      => 6,
			'excerpt_words'       => 20,
			'default_order'       => 'newest',
			'layout_mode'         => 'masonry',
			'featured_show_author'=> 'yes',
			'featured_show_date'  => 'yes',
			'show_search'         => 'yes',
			'show_category_filter'=> 'yes',
			'show_tag_filter'     => 'yes',
		);
		$settings = wp_parse_args( $settings, $defaults );

		$settings['posts_per_page'] = max( 1, min( 24, absint( $settings['posts_per_page'] ) ) );
		$settings['excerpt_words']  = max( 0, min( 80, absint( $settings['excerpt_words'] ) ) );

		$order_options = self::get_order_options();
		if ( ! isset( $order_options[ $settings['default_order'] ] ) ) {
			$settings['default_order'] = 'newest';
		}

		$layout_modes = array(
			'masonry',
			'featured_stack',
		);
		if ( ! in_array( $settings['layout_mode'], $layout_modes, true ) ) {
			$settings['layout_mode'] = 'masonry';
		}

		$settings['featured_show_author'] = ( 'yes' === $settings['featured_show_author'] ) ? 'yes' : '';
		$settings['featured_show_date']   = ( 'yes' === $settings['featured_show_date'] ) ? 'yes' : '';
		$settings['show_search']          = ( 'yes' === $settings['show_search'] ) ? 'yes' : '';
		$settings['show_category_filter'] = ( 'yes' === $settings['show_category_filter'] ) ? 'yes' : '';
		$settings['show_tag_filter']      = ( 'yes' === $settings['show_tag_filter'] ) ? 'yes' : '';

		return $settings;
	}

	private static function get_order_options(): array {
		return array(
			'newest'    => esc_html__( 'Newest First', 'dope-posts' ),
			'oldest'    => esc_html__( 'Oldest First', 'dope-posts' ),
			'title_asc' => esc_html__( 'Title A to Z', 'dope-posts' ),
			'title_desc'=> esc_html__( 'Title Z to A', 'dope-posts' ),
			'random'    => esc_html__( 'Random', 'dope-posts' ),
		);
	}

	public static function build_query_args( array $request, int $paged, array $settings ): array {
		$settings = wp_parse_args(
			$settings,
			array(
				'posts_per_page' => 6,
				'default_order'  => 'newest',
			)
		);

		$posts_per_page = max( 1, min( 24, absint( $settings['posts_per_page'] ) ) );
		$order_key      = ! empty( $request['order'] ) ? sanitize_key( (string) $request['order'] ) : sanitize_key( (string) $settings['default_order'] );

		$args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_per_page,
			'paged'               => max( 1, $paged ),
			'ignore_sticky_posts' => true,
		);

		switch ( $order_key ) {
			case 'oldest':
				$args['orderby'] = 'date';
				$args['order']   = 'ASC';
				break;
			case 'title_asc':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'title_desc':
				$args['orderby'] = 'title';
				$args['order']   = 'DESC';
				break;
			case 'random':
				$args['orderby'] = 'rand';
				break;
			case 'newest':
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
		}

		$search = isset( $request['search'] ) ? sanitize_text_field( (string) $request['search'] ) : '';
		if ( '' !== $search ) {
			$args['s'] = $search;
		}

		$category_id = isset( $request['category'] ) ? absint( $request['category'] ) : 0;
		$tag_id      = isset( $request['tag'] ) ? absint( $request['tag'] ) : 0;

		if ( $category_id > 0 || $tag_id > 0 ) {
			$args['tax_query'] = array( 'relation' => 'AND' );

			if ( $category_id > 0 ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => array( $category_id ),
				);
			}

			if ( $tag_id > 0 ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => array( $tag_id ),
				);
			}
		}

		return $args;
	}

	public static function render_posts_markup( WP_Query $query, array $settings, bool $is_load_more = false ): string {
		$settings = self::normalize_settings( $settings );

		if ( 'featured_stack' === $settings['layout_mode'] ) {
			return $is_load_more
				? self::get_featured_stack_append_markup( $query, $settings )
				: self::get_featured_stack_markup( $query, $settings );
		}

		return self::get_masonry_cards_markup( $query, $settings );
	}

	public static function get_cards_markup( WP_Query $query, array $settings ): string {
		return self::render_posts_markup( $query, $settings, false );
	}

	private static function get_masonry_cards_markup( WP_Query $query, array $settings ): string {
		ob_start();

		if ( ! $query->have_posts() ) {
			echo '<p class="dppw-empty">' . esc_html__( 'No publications found.', 'dope-posts' ) . '</p>';
			return (string) ob_get_clean();
		}

		$excerpt_words = isset( $settings['excerpt_words'] ) ? max( 0, absint( $settings['excerpt_words'] ) ) : 20;

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id    = get_the_ID();
			$categories = get_the_category();
			$category   = ! empty( $categories[0]->name ) ? $categories[0]->name : esc_html__( 'Uncategorized', 'dope-posts' );
			$date       = get_the_date();
			$excerpt    = wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), $excerpt_words, '...' );
			?>
			<article class="dppw-card">
				<a class="dppw-thumb-link" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php echo get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'dppw-thumb', 'loading' => 'lazy' ) ); ?>
					<?php else : ?>
						<div class="dppw-thumb dppw-thumb-placeholder"></div>
					<?php endif; ?>
				</a>
				<div class="dppw-card-body">
					<div class="dppw-meta">
						<span class="dppw-meta-category"><?php echo esc_html( $category ); ?></span>
						<span class="dppw-sep">&bull;</span>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( $date ); ?></time>
					</div>
					<h3 class="dppw-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h3>
					<?php if ( $excerpt_words > 0 ) : ?>
						<p class="dppw-excerpt"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>
					<a class="dppw-readmore" href="<?php the_permalink(); ?>">
						<?php echo esc_html__( 'Read More', 'dope-posts' ); ?>
					</a>
				</div>
			</article>
			<?php
		}

		wp_reset_postdata();

		return (string) ob_get_clean();
	}

	private static function get_featured_stack_markup( WP_Query $query, array $settings ): string {
		ob_start();

		if ( ! $query->have_posts() ) {
			echo '<p class="dppw-empty">' . esc_html__( 'No publications found.', 'dope-posts' ) . '</p>';
			return (string) ob_get_clean();
		}

		$hero_markup    = '';
		$compact_markups = array();
		$post_index     = 0;

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id   = get_the_ID();
			$post_data = self::get_featured_post_data( $post_id, $settings );

			if ( 0 === $post_index ) {
				$hero_markup = self::get_featured_hero_markup( $post_data, $settings );
			} else {
				$compact_markups[] = self::get_featured_compact_markup( $post_data, $settings );
			}

			++$post_index;
		}

		wp_reset_postdata();

		$side_markups   = array_slice( $compact_markups, 0, 3 );
		$bottom_markups = array_slice( $compact_markups, 3 );
		?>
		<div class="dppw-featured-layout">
			<div class="dppw-featured-top">
				<?php echo $hero_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( ! empty( $side_markups ) ) : ?>
					<div class="dppw-featured-side">
						<?php
						foreach ( $side_markups as $card_markup ) {
							echo $card_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $bottom_markups ) ) : ?>
				<div class="dppw-featured-bottom-list">
					<?php
					foreach ( $bottom_markups as $card_markup ) {
						echo $card_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php

		return (string) ob_get_clean();
	}

	private static function get_featured_stack_append_markup( WP_Query $query, array $settings ): string {
		ob_start();

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id   = get_the_ID();
			$post_data = self::get_featured_post_data( $post_id, $settings );
			echo self::get_featured_compact_markup( $post_data, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		wp_reset_postdata();

		return (string) ob_get_clean();
	}

	private static function get_featured_post_data( int $post_id, array $settings ): array {
		$categories = get_the_category( $post_id );
		$category   = ! empty( $categories[0]->name ) ? $categories[0]->name : esc_html__( 'Uncategorized', 'dope-posts' );
		$author_id  = (int) get_post_field( 'post_author', $post_id );
		$author     = ( $author_id > 0 ) ? get_the_author_meta( 'display_name', $author_id ) : '';

		$excerpt_words = isset( $settings['excerpt_words'] ) ? max( 0, absint( $settings['excerpt_words'] ) ) : 20;
		$raw_excerpt   = (string) get_the_excerpt( $post_id );
		$excerpt       = wp_trim_words( wp_strip_all_tags( $raw_excerpt ), $excerpt_words, '...' );

		return array(
			'post_id'   => $post_id,
			'title'     => get_the_title( $post_id ),
			'permalink' => (string) get_permalink( $post_id ),
			'category'  => $category,
			'date'      => get_the_date( '', $post_id ),
			'date_iso'  => get_the_date( 'c', $post_id ),
			'author'    => $author,
			'excerpt'   => $excerpt,
			'is_video'  => has_post_format( 'video', $post_id ),
		);
	}

	private static function get_featured_hero_markup( array $post_data, array $settings ): string {
		ob_start();
		?>
		<article class="dppw-card dppw-featured-hero-card">
			<div class="dppw-featured-hero-media">
				<a class="dppw-thumb-link" href="<?php echo esc_url( $post_data['permalink'] ); ?>" aria-label="<?php echo esc_attr( $post_data['title'] ); ?>">
					<?php echo self::get_featured_thumbnail_markup( (int) $post_data['post_id'], 'large', 'dppw-thumb dppw-featured-hero-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
				<?php echo self::get_featured_play_icon_markup( (bool) $post_data['is_video'], 'dppw-play-icon dppw-play-icon--hero' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<div class="dppw-featured-hero-overlay" aria-hidden="true"></div>
			</div>
			<div class="dppw-card-body dppw-featured-hero-content">
				<span class="dppw-meta-category dppw-featured-category-badge"><?php echo esc_html( $post_data['category'] ); ?></span>
				<h3 class="dppw-title dppw-featured-hero-title">
					<a href="<?php echo esc_url( $post_data['permalink'] ); ?>"><?php echo esc_html( $post_data['title'] ); ?></a>
				</h3>
				<?php if ( isset( $settings['excerpt_words'] ) && absint( $settings['excerpt_words'] ) > 0 && '' !== $post_data['excerpt'] ) : ?>
					<p class="dppw-excerpt dppw-featured-excerpt"><?php echo esc_html( $post_data['excerpt'] ); ?></p>
				<?php endif; ?>
				<?php echo self::get_featured_meta_markup( $post_data, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</article>
		<?php

		return (string) ob_get_clean();
	}

	private static function get_featured_compact_markup( array $post_data, array $settings ): string {
		ob_start();
		?>
		<article class="dppw-card dppw-featured-compact-card">
			<a class="dppw-thumb-link dppw-featured-compact-media" href="<?php echo esc_url( $post_data['permalink'] ); ?>" aria-label="<?php echo esc_attr( $post_data['title'] ); ?>">
				<?php echo self::get_featured_thumbnail_markup( (int) $post_data['post_id'], 'medium_large', 'dppw-thumb dppw-featured-compact-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo self::get_featured_play_icon_markup( (bool) $post_data['is_video'], 'dppw-play-icon dppw-play-icon--compact' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<div class="dppw-card-body dppw-featured-compact-body">
				<span class="dppw-meta-category dppw-featured-category-label"><?php echo esc_html( $post_data['category'] ); ?></span>
				<h3 class="dppw-title dppw-featured-compact-title">
					<a href="<?php echo esc_url( $post_data['permalink'] ); ?>"><?php echo esc_html( $post_data['title'] ); ?></a>
				</h3>
				<?php if ( isset( $settings['excerpt_words'] ) && absint( $settings['excerpt_words'] ) > 0 && '' !== $post_data['excerpt'] ) : ?>
					<p class="dppw-excerpt dppw-featured-excerpt"><?php echo esc_html( $post_data['excerpt'] ); ?></p>
				<?php endif; ?>
				<?php echo self::get_featured_meta_markup( $post_data, $settings ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</article>
		<?php

		return (string) ob_get_clean();
	}

	private static function get_featured_meta_markup( array $post_data, array $settings ): string {
		$parts = array();

		if ( 'yes' === $settings['featured_show_author'] && '' !== $post_data['author'] ) {
			$parts[] = '<span class="dppw-featured-author">' . esc_html( $post_data['author'] ) . '</span>';
		}

		if ( 'yes' === $settings['featured_show_date'] && '' !== $post_data['date'] ) {
			$parts[] = '<time datetime="' . esc_attr( $post_data['date_iso'] ) . '">' . esc_html( $post_data['date'] ) . '</time>';
		}

		if ( empty( $parts ) ) {
			return '';
		}

		return '<div class="dppw-meta dppw-featured-meta-line">' . implode( '<span class="dppw-sep">&bull;</span>', $parts ) . '</div>';
	}

	private static function get_featured_thumbnail_markup( int $post_id, string $size, string $class_name ): string {
		if ( has_post_thumbnail( $post_id ) ) {
			return (string) get_the_post_thumbnail(
				$post_id,
				$size,
				array(
					'class'   => $class_name,
					'loading' => 'lazy',
				)
			);
		}

		return '<div class="' . esc_attr( $class_name . ' dppw-thumb-placeholder' ) . '"></div>';
	}

	private static function get_featured_play_icon_markup( bool $is_video, string $class_name ): string {
		if ( ! $is_video ) {
			return '';
		}

		return '<span class="' . esc_attr( $class_name ) . '" aria-hidden="true"></span>';
	}
}
