<article <?php post_class('bt-post'); ?>>
	<div class="bt-post--infor">
		<?php
		echo utenzo_post_category_render();
		if (is_single()) {
			echo utenzo_single_post_title_render();
		} else {
			echo utenzo_post_title_render();
		}
		echo utenzo_post_meta_single_render();
		?>
	</div>
	<?php
	$layout = isset($args['layout']) ? $args['layout'] : 'layout-default';
	if ($layout == 'layout-01') {
		echo utenzo_post_featured_render();
	}
	echo utenzo_post_content_render();
	?>
</article>