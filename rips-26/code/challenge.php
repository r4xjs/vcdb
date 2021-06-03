function display_notifications() {

    // get all posts with the post type amn_notification
    $plugin_notifications = get_posts(
    array(
        'post_type' => 'amn_notification'
    ));


    foreach ( $plugin_notifications as $notification ) {
        $dismissable = get_post_meta( $notification->ID, 'dismissable', true );
        $type        = get_post_meta( $notification->ID, 'type', true );

        $html = "<div class='notice-$type am-notification notice'>";
        $html .= $notification->post_content;
        $html .= "</div>";

        echo $html;

        }

}
