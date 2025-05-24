<?php
// Get the ACF gallery field (replace 'acf_post_images' with your field name if needed)
$gallery = get_field('acf_post_images');

// Set the desired WordPress image size ('thumbnail', 'medium', 'large', 'full', or custom size)
$image_size = 'medium';

if ($gallery && is_array($gallery)) : ?>
    <div class="my-gallery">
        <?php foreach ($gallery as $image) : 
            // Get the image URL for the selected size
            $img_url = esc_url($image['sizes'][$image_size]);
            // Get the image alt text
            $img_alt = esc_attr($image['alt']);
        ?>
            <img class="my-gallery-image"
                 src="<?php echo $img_url; ?>"
                 alt="<?php echo $img_alt; ?>">
        <?php endforeach; ?>
    </div>
<?php endif; ?>
