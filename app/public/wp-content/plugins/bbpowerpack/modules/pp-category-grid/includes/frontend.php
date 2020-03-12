<?php
if ( ! isset( $settings->post_type ) ) {
	$post_type = 'post';
} else {
	$post_type = $settings->post_type;
}
$var_tax_type = 'posts_' . $post_type . '_tax_type';
$tax_type     = $var_cat_matching = $var_cat = '';
if ( isset( $settings->$var_tax_type ) ) {
	$tax_type         = $settings->$var_tax_type;
	$var_cat          = 'tax_' . $post_type . '_' . $tax_type;
	$var_cat_matching = $var_cat . '_matching';
}

$cat_match    = isset( $settings->$var_cat_matching ) ? $settings->$var_cat_matching : false;
$ids          = isset( $settings->$var_cat ) ? explode( ',', $settings->$var_cat ) : array();
$taxonomy     = isset( $tax_type ) ? $tax_type : '';
$orderby      = isset( $settings->order_by ) ? $settings->order_by : 'name';
$order        = isset( $settings->order ) ? $settings->order : 'ASC';
$show_count   = 1;
$pad_counts   = 1;
$hierarchical = 1;
$title        = '';
$empty        = ( isset( $settings->show_empty ) && 'yes' === $settings->show_empty ) ? false : true;

$taxonomy_thumbnail_enable     = BB_PowerPack_Taxonomy_Thumbnail::$taxonomy_thumbnail_enable;
$taxonomy_thumbnail_taxonomies = BB_PowerPack_Taxonomy_Thumbnail::$taxonomies;

$args = array(
	'taxonomy'     => $taxonomy,
	'orderby'      => $orderby,
	'order'        => $order,
	'show_count'   => $show_count,
	'pad_counts'   => $pad_counts,
	'hierarchical' => $hierarchical,
	'title_li'     => $title,
	'hide_empty'   => $empty,
);

if ( $cat_match && 'related' !== $cat_match && ! empty( $ids ) ) {
	if ( isset( $settings->display_data ) && ( 'children_only' === $settings->display_data || 'default' === $settings->display_data ) && ! empty( $ids ) && ! empty( $ids[0] ) ) {
		//only single value is allowed so we have made new custom function, get_child_categories()
		$args['parent'] = $ids;
	} else {
		$args['include'] = $ids;
	}
}
if ( ( ! $cat_match || 'related' === $cat_match ) && ! empty( $ids ) ) {
	if ( isset( $settings->display_data ) && ( 'parent_only' !== $settings->display_data ) && ! empty( $ids ) && ! empty( $ids[0] ) ) {

		foreach ( $ids as $term_id ) {
			$tmp_ids = get_term_children( $term_id, $taxonomy );
			$ids     = array_merge( $ids, $tmp_ids );
		}
		$args['exclude'] = $ids;
	} else {
		$args['exclude'] = $ids;
	}
}

$args = apply_filters( 'pp_category_grid_query_args', $args, $settings );

if ( isset( $settings->display_data ) && 'children_only' === $settings->display_data && isset( $args['parent'] ) && ! empty( $args['parent'][0] ) ) {
	$all_categories = PPCategoryGridModule::get_categories( $args, 'children_only' );
} elseif ( isset( $settings->display_data ) && 'default' === $settings->display_data && isset( $args['parent'] ) && ! empty( $args['parent'][0] ) ) {
	$all_categories = PPCategoryGridModule::get_categories( $args, 'default' );
} else {
	$all_categories = get_categories( $args );
}

?>

<div class="<?php echo 'yes' === $settings->category_grid_slider ? 'swiper-container' : ''; ?> pp-categories-container">
	<div class="<?php echo 'yes' === $settings->category_grid_slider ? 'swiper-wrapper' : ''; ?> pp-categories pp-clear">
	<?php

	foreach ( $all_categories as $cat ) {
		if ( isset( $settings->display_data ) && 'parent_only' === $settings->display_data ) {
			if ( isset( $args['include'][0] ) && intval( $args['include'][0] ) > 0 ) {
				$inc_array = $args['include'];
				if ( ! in_array( $cat->term_id, $inc_array ) ) {
					continue;
				}
			} elseif ( 0 !== $cat->parent ) {
				continue;
			}
		} elseif ( isset( $settings->display_data ) && 'children_only' === $settings->display_data ) {
			if ( isset( $args['include'][0] ) && intval( $args['include'][0] ) > 0 ) {
				$inc_array = $args['include'];
				if ( ! in_array( $cat->parent, $inc_array ) ) {
					continue;
				}
			} elseif ( isset( $args['exclude'][0] ) && intval( $args['exclude'][0] ) > 0 ) {
				$exc_array = $args['exclude'];
				if ( in_array( $cat->parent, $exc_array ) || 0 === $cat->parent ) {
					continue;
				}
			} elseif ( 0 === $cat->parent ) {
				continue;
			}
		} elseif ( isset( $settings->display_data ) && 'default' === $settings->display_data && isset( $args['exclude'] ) && ! empty( $args['exclude'][0] ) ) {
			$exc_array = $args['exclude'];
			if ( in_array( $cat->parent, $exc_array ) ) {
				continue;
			}
		}
		$cat_thumb_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
		if ( 'enabled' === $taxonomy_thumbnail_enable && ! empty( $taxonomy_thumbnail_taxonomies ) && in_array( $tax_type, (array) $taxonomy_thumbnail_taxonomies ) ) {
			$taxonomy_thumbnail_id = get_term_meta( $cat->term_id, 'taxonomy_thumbnail_id', true );
			if ( empty( $cat_thumb_id ) ) {
				$cat_thumb_id = $taxonomy_thumbnail_id;
			}
		}
		$category_image = wp_get_attachment_image_src( $cat_thumb_id, $settings->category_image_size );
		$term_link      = get_term_link( $cat, $taxonomy );

		include BB_POWERPACK_DIR . 'modules/pp-category-grid/includes/layout-1.php';
	}
	?>
	</div>

	<?php
	if ( 'yes' === $settings->category_grid_slider ) {
		?>
		<div class="swiper-pagination"></div>
		<?php if ( 'yes' === $settings->slider_navigation ) { ?>
			<!-- If we need navigation buttons -->
			<div class="swiper-button-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 44"><path d="M0,22L22,0l2.1,2.1L4.2,22l19.9,19.9L22,44L0,22L0,22L0,22z"></svg></div>
			<div class="swiper-button-next"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 44"><path d="M27,22L27,22L5,44l-2.1-2.1L22.8,22L2.9,2.1L5,0L27,22L27,22z"></svg></div>
			<?php
		}
	}
	?>

</div>
