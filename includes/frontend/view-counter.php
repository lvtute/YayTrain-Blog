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
            $title_custom_postfix = "&#128065; $views_count &#11088; $post_rating/$point_ladder";
            break;

        case 'badge':
            $title_custom_postfix .= '<br>';
            $title_custom_postfix .=
                "<span>$views_count Views</span> <span>$post_rating/$point_ladder Star</span>";
            break;

        case "tooltip":
            $title = '<style>
            .tooltip {
              position: relative;
              display: inline-block;
            }
            
            .tooltip .tooltiptext {
              width: 80%;
              visibility: hidden;
              background-color: black;
              color: #fff;
              text-align: center;
              padding: 5px 0;
              border-radius: 6px;
              font-size: 0.6em;
             
              position: absolute;
              z-index: 1;
            }
            
            .tooltip:hover .tooltiptext {
              visibility: visible;
            }
            </style>
            
            <div class="tooltip">' . $title .
                '<div class="tooltiptext"><p>' . $views_count . ' View</p> <p>' . $post_rating . '/' . $point_ladder . ' Star</p></div>
            </div>';
            break;

        default:
            break;
    }


    return $title . $title_custom_postfix;
}
