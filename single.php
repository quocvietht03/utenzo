<?php
/*
 * Single Post
 */

get_header();
if (function_exists('get_field')) {
	$banner = get_field('banner_post', get_the_ID());
} else {
	$banner = '';
}
?>

<main id="bt_main" class="bt-site-main">
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

</main><!-- #main -->

<?php get_footer(); ?>