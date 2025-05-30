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
				<h2><?php echo esc_html_e('404', 'utenzo'); ?></h2>
				<h3><?php esc_html_e("Oops! That page can't be found.", "utenzo"); ?></h3>
				<p><?php esc_html_e("We're really sorry but we can't seem to find the page you were looking for.", "utenzo") ?></p>
				<a href="<?php echo esc_url(home_url()); ?>" class="bt-primary-btn bt-button bt-button-hover">
					<?php echo esc_html_e('Back To Homepage', 'utenzo'); ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
						<g id="ArrowUpRight">
							<path id="Vector" d="M15.6251 5V13.125C15.6251 13.2908 15.5593 13.4497 15.442 13.5669C15.3248 13.6842 15.1659 13.75 15.0001 13.75C14.8343 13.75 14.6754 13.6842 14.5582 13.5669C14.441 13.4497 14.3751 13.2908 14.3751 13.125V6.50859L5.44229 15.4422C5.32502 15.5595 5.16596 15.6253 5.0001 15.6253C4.83425 15.6253 4.67519 15.5595 4.55792 15.4422C4.44064 15.3249 4.37476 15.1659 4.37476 15C4.37476 14.8341 4.44064 14.6751 4.55792 14.5578L13.4915 5.625H6.8751C6.70934 5.625 6.55037 5.55915 6.43316 5.44194C6.31595 5.32473 6.2501 5.16576 6.2501 5C6.2501 4.83424 6.31595 4.67527 6.43316 4.55806C6.55037 4.44085 6.70934 4.375 6.8751 4.375H15.0001C15.1659 4.375 15.3248 4.44085 15.442 4.55806C15.5593 4.67527 15.6251 4.83424 15.6251 5Z"></path>
						</g>
					</svg>
				</a>
			</div>
		</div>
	</div>
</main><!-- #main -->
<?php get_footer(); ?>