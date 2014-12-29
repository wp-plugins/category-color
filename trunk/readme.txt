=== Plugin Name ===
Contributors: zayedbaloch, naeemnur
Donate link: http://radlabs.biz/
Tags: category colors, meta box, taxonomy tag colors,
Requires at least: 1.0
Tested up to: 4.1
Stable tag: trunk
License: GPLv2 or later

Easily set a custom color per Post Category and use the colors in your Wordpress templates to spice up your theme.

== Description ==

Easily set a custom color per Post Category and use the colors in your Wordpress templates to spice up your theme. You can use it to color your Category names, your Post titles, background, lines, etc. in your Theme. Colors are always easily adjustable through your Category Edit screen

== Installation ==

1. Unzip the download package
2. Upload \`rl_category_color\` to the \`/wp-content/plugins/\` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How can I add these colors to my template? =

\`<?php
    $category = get_the_category();
    $the_category_id = $category[0]->cat_ID;

    if(function_exists('rl_color')){
        $rl_category_color = rl_color($the_category_id);
    }
?>\`

== Screenshots ==

1. Category Edit page
2. Category Color Picker

== Changelog ==

= 1.0 =
* First Release

== Upgrade Notice ==

= 1.0 =
First Release.
