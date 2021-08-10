---

title: rips-26
author: raxjs
tags: [php, nosolution]

---

Part of the RIPS WordPress Plugin Security Challenges. This code
shows some notification to the user.

<!--more-->
{{< reference src="https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}
