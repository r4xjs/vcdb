---

title: rips-29
author: raxjs
tags: [php]

---

Part of the RIPS WordPress Plugin Security Challenges.
IMO not so easy challenge and also only exploitable under some
assumptions.

<!--more-->
{{< reference src="https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/" >}}

# Code
{{< code language="php"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
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

{{< /code >}}

# Solution
{{< code language="php" highlight="5,16,17,23,24" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}
function set_screen_options() {

    $user = wp_get_current_user();
    // 1) $option and $value is user input
    $option = $_POST['wp_screen_options']['option'];
    $value = $_POST['wp_screen_options']['value'];

    switch ( $option ) {
        case 'products_per_page':
        case 'posts_per_page':
        case 'users_per_page':
            $value = (int) $value;
            break;
        default:
            // 2) this is only bypassable if a vuln
            // filter callback is added via add_filter('set-screen-option', ..)
         $value = apply_filters('set-screen-option', false, $option, $value );
         if ( false === $value )
             return;
         break;
    }
    // 3) in case apply_filters('set-screen-option'..) can be bypassed
    //    wp_capabalities could be changed here for example
    update_user_meta($user->ID, $option, $value);
}


// Without deeper knowledge of wordpress add_filter/apply_filters system
// this challnege is not easy to solve, solution was taken from the following
// link (7 Typeset Toolbox):
- https://web.archive.org/web/20190328023701/https://www.ripstech.com/php-security-calendar-2018/

/*
add_filter('set-screen-option', 'wpcf_table_set_option', 10, 3);
function wpcf_table_set_option($status, $option, $value)
{
    return $value;
}
*/

{{< /code >}}
