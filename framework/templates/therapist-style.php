<?php
$job = get_field('job');

?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
    <?php if (has_post_thumbnail()){ ?>
      <div class="bt-post--thumbnail">
        <?php echo utenzo_post_cover_featured_render($args['image-size']); ?>
      </div>
    <?php } ?>

    <div class="bt-post--infor">
    <?php
        if(!empty($job)) {
          echo '<div class="bt-post--job">' . $job . '</div>';
        }
      ?>
      <?php echo utenzo_post_title_render(); ?>
    </div>
  </div>
</article>
