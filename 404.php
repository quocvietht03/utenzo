<?php
/*
Template Name: 404 Template
*/
get_header();
?>
<main id="bt_main" class="bt-site-main">
	<div class="bt-main-content-ss">
		<div class="bt-container">
			<div class="bt-404-error">
				<div class="bt-404-error__img">
					<img src="<?php echo get_template_directory_uri(); ?>/assets/images/img-404.png" alt="<?php echo esc_attr_e('404', 'utenzo'); ?>">
				</div>
				<div class="bt-404-error__content">
					<h2><?php echo esc_html_e('Oops!', 'utenzo'); ?></h2>
					<p><?php esc_html_e('We are sorry, but something went wrong.', 'utenzo'); ?></p>
					<a href="<?php echo esc_url(home_url()); ?>" class="bt-primary-btn bt-button bt-button-hover">
						<?php echo esc_html_e('Back To Homepage', 'utenzo'); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</main><!-- #main -->
<?php get_footer(); ?>