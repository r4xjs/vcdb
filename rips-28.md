---

title: rips-28
author: raxjs
tags: [php, nosolution]

---

Part of the RIPS WordPress Plugin Security Challenges. Not sure what is going on here.

<!--more-->
{{< reference src="https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="php" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}
