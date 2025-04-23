<?php
/*
 * Single Post
 */

get_header();
$layout = 'layout-default';
$banner = '';

if (function_exists('get_field')) {
	$layout_single_post = get_field('layout_single_post', 'options') ?: $layout;
	$banner = get_field('banner_post', get_the_ID()) ?: '';
	$enable_layout = get_field('enable_layout', get_the_ID()) ?: false;
	$layout_post = get_field('layout_post', get_the_ID()) ?: $layout_single_post;
	$layout = $enable_layout ? $layout_post : $layout_single_post;
}
?>

<main id="bt_main" class="bt-site-main">
	<?php if ($layout == 'layout-01') { ?>
		<div class="bt-single-post-breadcrumb">
			<div class="bt-container">
				<div class="bt-row-breadcrumb-single-post">
					<?php
					$home_text = 'Home';
					$delimiter = '/';
					echo '<div class="bt-breadcrumb">';
					echo utenzo_page_breadcrumb($home_text, $delimiter);
					echo '</div>';
					?>
				</div>
			</div>
		</div>
		<div class="bt-main-content-ss bt-main-content-layout-01">
			<div class="bt-container">
				<div class="bt-main-post-row">
					<div class="bt-main-post-col">
						<?php
						while (have_posts()) : the_post();
						?>
							<div class="bt-main-post bt-layout-01">
								<?php get_template_part('framework/templates/post', null, array('layout' => $layout)); ?>
							</div>
							<div class="bt-main-actions">
								<?php
								echo utenzo_tags_render();
								echo utenzo_share_render();
								?>
							</div>
						<?php
							utenzo_post_nav();

							// If comments are open or we have at least one comment, load up the comment template.
							if (comments_open() || get_comments_number()) comments_template();
						endwhile;

						?>
					</div>
					<div class="bt-sidebar-col">
						<div class="bt-sidebar">
							<?php if (is_active_sidebar('main-sidebar')) echo get_sidebar('main-sidebar'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="bt-main-image-full">
			<?php
			if (!empty($banner)) {
			?>
				<div class="bt-post--featured">
					<div class="bt-cover-image">
						<img src='<?php echo esc_url($banner['url']) ?>' />
					</div>
				</div>
			<?php
			} else {
				echo utenzo_post_featured_render('full');
			}
			?>
		</div>
		<div class="bt-container-single">
			<?php
			while (have_posts()) : the_post();
			?>
				<div class="bt-main-post">
					<?php get_template_part('framework/templates/post'); ?>
				</div>
				<div class="bt-main-actions">
					<?php
					echo utenzo_tags_render();
					echo utenzo_share_render();
					?>
				</div>
			<?php
				utenzo_post_nav();

				// If comments are open or we have at least one comment, load up the comment template.
				if (comments_open() || get_comments_number()) comments_template();
			endwhile;

			?>

		</div>
	<?php } ?>
	<?php echo utenzo_related_posts(); ?>
</main><!-- #main -->

<?php get_footer(); ?>