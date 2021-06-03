function set_screen_options() {

    $user = wp_get_current_user();

    $option = $_POST['wp_screen_options']['option'];
    $value = $_POST['wp_screen_options']['value'];

    switch ( $option ) {

        case 'products_per_page':
        case 'posts_per_page':
        case 'users_per_page':
            $value = (int) $value;
            break;
        default:
                $value = apply_filters( 'set-screen-option', false, $option, $value );

                if ( false === $value )
                    return;
                break;
    }

    update_user_meta($user->ID, $option, $value);

}
