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
					<?php echo utenzo_get_icon_svg_html('img-404-error'); ?>
				</div>
				<div class="bt-404-error__content">
					<h2><?php echo esc_html_e('Oops!', 'utenzo'); ?></h2>
					<h3><?php echo esc_html_e('Something is Missing.', 'utenzo'); ?></h3>
					<p><?php echo esc_html_e('The page you are looking for cannot be found. Take a break before trying again.', 'utenzo'); ?></p>
					<div class="bt-button-hover">
						<a href="<?php echo esc_url(home_url()); ?>" class="bt-primary-btn bt-button">
							<span class="bt-heading"><?php echo esc_html_e('Back To Homepage', 'utenzo'); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</main><!-- #main -->
<?php get_footer(); ?>
