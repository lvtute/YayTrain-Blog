<?php

add_filter('the_content', 'update_post_views');

function update_post_views($content)
{
    if (is_single()) {
        $post_id = get_the_ID();

        $count_key = 'post_views_count';
        $count = get_post_meta($post_id, $count_key, true);

        if (!$count) {
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, 1);
            return;
        }

        $count++;

        update_post_meta($post_id, $count_key, $count);
    }

    return $content;
}
