<div class="fl-custom-query fl-loop-data-source" data-source="custom_query">
	<div id="fl-builder-settings-section-filter" class="fl-builder-settings-section">
		<h3 class="fl-builder-settings-title"></h3>

		<table class="fl-form-table fl-post-type-filter">
		<?php
			$post_types    = array();
			$taxonomy_type = array();

		foreach ( FLBuilderLoop::post_types() as $slug => $type ) {

			$taxonomies = FLBuilderLoop::taxonomies( $slug );

			if ( ! empty( $taxonomies ) ) {
				$post_types[ $slug ] = $type->label;

				foreach ( $taxonomies as $tax_slug => $tax ) {
					$taxonomy_type[ $slug ][ $tax_slug ] = $tax->label;
				}
			}
		}

			FLBuilder::render_settings_field(
				'post_type',
				array(
					'type'    => 'select',
					'label'   => __( 'Post Type', 'bb-powerpack' ),
					'options' => $post_types,
					'default' => isset( $settings->post_type ) ? $settings->post_type : 'post',
				)
			);
			?>
		</table>

		<?php
		foreach ( $post_types as $slug => $label ) :
			$selected = isset( $settings->{'posts_' . $slug . '_type'} ) ? $settings->{'posts_' . $slug . '_type'} : 'post';
			?>
			<table class="fl-form-table fl-custom-query-filter fl-custom-query-<?php echo $slug; ?>-filter"<?php echo ( $slug == $selected ) ? ' style="display:table;"' : ''; ?>>
			<?php

			FLBuilder::render_settings_field(
				'posts_' . $slug . '_tax_type',
				array(
					'type'    => 'select',
					'label'   => __( 'Taxonomy', 'bb-powerpack' ),
					'options' => $taxonomy_type[ $slug ],
				)
			);

			foreach ( $taxonomy_type[ $slug ] as $tax_slug => $tax_label ) {

				FLBuilder::render_settings_field(
					'tax_' . $slug . '_' . $tax_slug,
					array(
						'type'     => 'suggest',
						'action'   => 'fl_as_terms',
						'data'     => $tax_slug,
						'label'    => $tax_label,
						/* translators: %s: tax label */
						'help'     => sprintf( __( 'Enter a list of %1$s.', 'bb-powerpack' ), $tax_label ),
						'matching' => true,
					),
					$settings
				);
			}

			?>
			</table>
		<?php endforeach; ?>
		<p class="fl-builder-settings-description">
			<a href="<?php echo admin_url( 'options-general.php?page=ppbb-settings&tab=extensions' ); ?>" target='_blank'>
				<?php echo __( 'Click to enable thumbnail for taxonomies.', 'bb-powerpack' ); ?>
			</a>
		</p>
		<table class='fl-form-table'>
			<?php

				FLBuilder::render_settings_field(
					'display_data',
					array(
						'type'    => 'select',
						'label'   => __( 'Display', 'bb-powerpack' ),
						'default' => isset( $settings->display_data ) ? $settings->display_data : 'default',
						'options' => array(
							'default'       => __( 'Default', 'bb-powerpack' ),
							'parent_only'   => __( 'Parent Only', 'bb-powerpack' ),
							'children_only' => __( 'Children Only', 'bb-powerpack' ),
						),
					)
				);
				?>
		</table>

		<table class='fl-form-table'>
			<?php
				FLBuilder::render_settings_field(
					'order_by',
					array(
						'type'    => 'select',
						'label'   => __( 'Order By', 'bb-powerpack' ),
						'default' => isset( $settings->order_by ) ? $settings->order_by : 'name',
						'options' => array(
							'name'   => __( 'Name', 'bb-powerpack' ),
							'slug'   => __( 'Slug', 'bb-powerpack' ),
							'parent' => __( 'Parent', 'bb-powerpack' ),
							'count'  => __( 'Post Count', 'bb-powerpack' ),
						),
					)
				);
				?>
		</table>

		<table class='fl-form-table'>
		<?php
				FLBuilder::render_settings_field(
					'order',
					array(
						'type'    => 'select',
						'label'   => __( 'Order', 'bb-powerpack' ),
						'default' => isset( $settings->order ) ? $settings->order : 'ASC',
						'options' => array(
							'ASC'  => __( 'Ascending', 'bb-powerpack' ),
							'DESC' => __( 'Descending', 'bb-powerpack' ),
						),
					)
				);

				?>
		</table>
	</div>
</div>
