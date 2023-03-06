<?php
add_filter('the_title', 'yay_blog_title_changer');
function yay_blog_title_changer($title)
{
    if (!is_front_page()) {
        return $title;
    }

    $post_id = get_the_ID();

    $options = get_option(
        'yayblog_options',
        [
            'yayblog_point_ladder' => 'out_of_10',
            'review_ui' => 'icon'
        ]
    );

    $title_custom_postfix = '';

    $views_count = get_post_meta($post_id, 'post_views_count', true);

    if (!$views_count) {
        $views_count = 0;
    }

    $post_rating = get_post_meta($post_id, '_yayblog_review_meta_key', true);

    if (!$post_rating) {
        $post_rating = 0;
    }

    $point_ladder = substr($options['yayblog_point_ladder'], strlen('out_of_'));

    if ($post_rating > $point_ladder) {
        $post_rating = $point_ladder;
    }

    if (!$options['review_ui']) {
        return;
    }

    switch ($options['review_ui']) {
        case 'icon':
            $title_custom_postfix = "<span class=\"yay-blog-custom-title-icon\">&#128065; $views_count &#11088; $post_rating/$point_ladder</span>";
            break;

        case 'badge':
            $title_custom_postfix .= '<br>';
            $title_custom_postfix .=
                "<div class=\"yay-blog-custom-title-badge\">
                <span class=\"view\">$views_count Views</span> 
                <span class=\"rating\">$post_rating/$point_ladder Star</span>
                </div>";
            break;

        case "tooltip":
            $title = '
            <div class="tooltip">' . $title .
                '<div class="tooltiptext"><p>' . $views_count . ' Views</p> <p>' . $post_rating . '/' . $point_ladder . ' Star</p></div>
            </div>';
            break;

        default:
            break;
    }


    return $title . $title_custom_postfix;
}

function enqueue_post_style()
{
    wp_enqueue_style(
        'yay_blog_post_style',
        content_url("plugins/yay-train-blog/assets/post.css"),
        array(),
        '0.1.0',
        'all'
    );
}

add_action('wp_enqueue_scripts', 'enqueue_post_style');
