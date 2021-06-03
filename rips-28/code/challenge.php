function get_option_html( $args ) {
    $options = $args['options'];
    $value = $args['value'];
    switch($options['type']) {

        case 'html':
            $buf .= $value;
            break;
        case 'date':
            wp_enqueue_script( 'jquery-ui-datepicker' );

    }

    return $buf;
}

$args = get_post_meta($post->ID, '_aioseop_opengraph');

echo get_option_html($args);
