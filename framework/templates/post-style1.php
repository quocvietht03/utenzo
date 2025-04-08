<?php
$post_id = get_the_ID();

?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
 
    <div class="bt-post--content">
      <?php
     echo utenzo_post_meta_render();
      echo utenzo_post_title_render();
      ?>
    </div>
    <?php echo utenzo_post_cover_featured_render($args['image-size']); ?>
  </div>
</article>