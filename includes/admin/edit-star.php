<?php

function yayblog_custom_box_html($post)
{
    $options = get_option('yayblog_options');
    $yayblog_point_ladder = substr($options['yayblog_point_ladder'], strlen('out_of_'));
    $value = get_post_meta($post->ID, '_yayblog_review_meta_key', true);

    if ($value > $yayblog_point_ladder) {
        $value = $yayblog_point_ladder;
    }
?>
    <select name="yayblog_review_field" id="yayblog_review_field" class="postbox">
        <option value="">Select review point...</option>
        <?php
        for ($i = 1; $i <= $yayblog_point_ladder; $i++) {
            echo "<option value=\"$i\"" . selected($value, $i, false) . ">$i</option>";
        }
        ?>
    </select>

<?php
}

function yayblog_add_custom_box()
{
    add_meta_box(
        'review_box_id',
        'Review',
        'yayblog_custom_box_html',
        'post'
    );
}

function yayblog_review_save_postdata($post_id)
{
    if (array_key_exists('yayblog_review_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_yayblog_review_meta_key',
            $_POST['yayblog_review_field']
        );
    }
}
add_action('save_post', 'yayblog_review_save_postdata');

add_action('add_meta_boxes', 'yayblog_add_custom_box');
