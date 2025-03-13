<?php
$post_id = get_the_ID();

?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
    <?php echo utenzo_post_cover_featured_render($args['image-size']); ?>
    <div class="bt-post--content">
      <?php
      echo utenzo_post_title_render();
      echo utenzo_post_publish_render();
      ?>
    </div>

  </div>
</article>