<?php
$source             = $settings->source;
$author_name        = '';
$biography          = '';
$archive_url        = 'javascript:void(0);';
$img_url            = '';
$link_url           = 'javascript:void(0);';
$link_url_target    = '_self';
$link_nofollow      = '';
$archive_url_target = '_blank';
$archive_nofollow   = '';
if ( 'custom' === $settings->source ) {
	$author_name = $settings->author_name_text;
	$biography   = $settings->biography_text;
	// originally image id
	if ( '' == $settings->img_url || 0 == $settings->img_url ) {
		$img_url = BB_POWERPACK_URL . 'images/default-user.png';
	} else {
		$img_url = $settings->img_url_src;
	}

	if ( '' !== trim( $settings->link_url ) ) {
		$link_url        = $settings->link_url;
		$link_url_target = $archive_url_target = $settings->link_url_target;
		$archive_url 	 = $link_url;
		if ( 'yes' === $settings->link_url_nofollow ) {
			$link_nofollow = $archive_nofollow = "rel='noopener nofollow'";
		}
	}

	if ( 'show' === $settings->archive_btn ) {
		if ( '' != trim( $settings->archive_url ) ) {
			$archive_url        = $settings->archive_url;
			$archive_url_target = $settings->archive_url_target;
			if ( 'yes' === $settings->archive_url_nofollow ) {
				$archive_nofollow = "rel='noopener nofollow'";
			}
		}
	}
} elseif ( 'current_author' === $settings->source ) {
	$avatar_args['size'] = 300;

	$author_id = get_the_author_meta( 'ID' );
	if ( empty( $author_id ) ) {
		global $post;
		$author_id = $post->post_author;
	}
	$img_url     = get_avatar_url( $author_id, $avatar_args );
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$biography   = get_the_author_meta( 'description', $author_id );

	$archive_url = get_author_posts_url( $author_id );

	if ( 'posts_archive' === $settings->link_to ) {
		$link_url = $archive_url;
	} elseif ( 'website' === $settings->link_to ) {
		$link_url = get_the_author_meta( 'user_url', $author_id );
	}

	if ( '_blank' === $settings->link_to_target ) {
		$link_url_target = $settings->link_to_target;
		$link_nofollow   = "rel='nofollow'";
	}
}

if ( isset( $settings->content_length ) && ! empty( $settings->content_length ) ) {
	if ( ! empty( $biography ) ) {
		$biography = wp_trim_words( $biography, absint( $settings->content_length ), '...' );
	}
}
?>
<div class="pp-authorbox-content">
	<div class="pp-authorbox-wrapper">
		<?php
		if ( 'show' == $settings->profile_picture ) {
			?>
			<div class="pp-authorbox-img">
				<a href="<?php echo $link_url; ?>" target='<?php echo $link_url_target; ?>' <?php echo $link_nofollow; ?> >
					<img src="<?php echo $img_url; ?>" >
				</a>
			</div>
			<?php
		}
		?>
		<div class="pp-authorbox-author-wrapper">
			<div class="pp-authorbox-author">
				<?php
				if ( 'show' == $settings->author_name ) {
					?>
					<div class="pp-authorbox-author-name-container">
						<<?php echo $settings->author_name_html_tag; ?>  class="pp-authorbox-author-name">
							<a href="<?php echo $link_url; ?>" target='<?php echo $link_url_target; ?>' <?php echo $link_nofollow; ?> >
								<span class="pp-authorbox-author-name-span"><?php echo $author_name; ?></span>
							</a>
						</<?php echo $settings->author_name_html_tag; ?>>
					</div>
					<?php
				}

				if ( 'show' == $settings->biography ) {
					?>
					<div class="pp-authorbox-bio"><?php echo wpautop( $biography ); ?></div>
					<?php
				}

				if ( 'show' == $settings->archive_btn ) {
					?>
					<div class="pp-author-box-button">
						<a class="pp-author-archive-btn" href="<?php echo $archive_url; ?>" target="<?php echo $archive_url_target; ?>" <?php echo $archive_nofollow; ?>>
							<span><?php echo $settings->button_text; ?></span>
						</a>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
