<?php
$post_id = get_the_ID();
$category = get_the_terms($post_id, 'category');
?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
    <?php echo utenzo_post_cover_featured_render($args['image-size']); ?>
    <div class="bt-post--content">
      <div class="bt-post--info">
        <div class="bt-post--publish">
          <?php echo get_the_date('F j, Y'); ?>
        </div>
        <?php if (!empty($category) && is_array($category)) {
          $first_category = reset($category); ?>
          <div class="bt-post--category">
            <a href="<?php echo esc_url(get_category_link($first_category->term_id)); ?>">
              <?php echo esc_html($first_category->name); ?>
            </a>
          </div>
        <?php } ?>
      </div>
      <?php 
      echo utenzo_post_title_render();
      echo utenzo_post_button_render('Read More');
      ?>
    </div>
  </div>
</article>