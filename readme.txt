=== Plugin Name ===
Contributors: zayedbaloch, naeemnur, pixeldesign,
Donate link: http://pixeldesign.io/
Tags: category colors, meta box, taxonomy tag colors, post, page,
Requires at least: 3.2
Tested up to: 4.3
Stable tag: 1.2
License: GPLv2 or later

Easily set a custom color per Post Category and use the colors in your Wordpress templates to spice up your theme.

== Description ==

Easily set a custom color per Post Category and use the colors in your Wordpress templates to spice up your theme. You can use it to color your Category names, your Post titles, background, lines, etc. in your Theme. Colors are always easily adjustable through your Category Edit screen

== Installation ==

1. Unzip the download package
2. Upload `rl_category_color` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

= Manual Plugin Installation =
1. Download Category Color Plugin to your desktop.
2. If downloaded as a zip archive, extract the Plugin folder to your desktop.
3. With your FTP program, upload the Plugin folder to the `wp-content/plugins` folder in your WordPress directory online.
4. Go to Plugins screen and find Category Color Plugin in the list.
5. Click Activate Plugin to activate it.

== Frequently Asked Questions ==

= How can I add these colors to my template? =

`<?php
    $category = get_the_category();
    $the_category_id = $category[0]->cat_ID;

    if(function_exists('rl_color')){
        $rl_category_color = rl_color($the_category_id);
    }
?>`

Now you used the color in your template in an inline stylesheet. The variable `<?php echo $category_color; ?>` can be used for anything.

= How to add colors in single post template? =

In your WordPress theme, find the file where you want the color to appear. For instance `single.php` your single post will be shown. Find these lines:

`<?php if (have_posts()) :  ?>
<?php while (have_posts()) : the_post(); ?>

WORDPRESS_EXTRA_CODE_DONT_REMOVE_IT

<?php endwhile; ?>
<?php endif; ?>`

Don't edit or remove the above lines, but add these lines in between instead:

`<?php
    $category = get_the_category();
    $the_category_id = $category[0]->cat_ID;

    if(function_exists('rl_color')){
        $rl_category_color = rl_color($the_category_id);
    }
?>
<h1 style="color: #<?php echo $rl_category_color; ?>;"><?php the_title(); ?></h1>
<p style="background: #<?php echo $rl_category_color; ?>;">Awesome Category Color!</p>`

= How add color in multiple categories or the_category() tag =
Replace

`<?php the_category(); ?>`

With

`<?php
    $categories = get_the_category();
    $separator = ' / ';
    $output = '';
    if($categories){
        foreach($categories as $category) {
                $rl_category_color = rl_color($category->cat_ID);
            $output .= '<a href="'.get_category_link( $category->term_id ).'" style="color:'.$rl_category_color.';">'.$category->cat_name.'</a>'.$separator;
        }
    echo trim($output, $separator);
    }
?>`

= Why can't pick a color right away when creating a Category, but only when editing a Category? =

Both for consistency and efficiency. First of all WordPress want to keep creating Categories sweet and simple with as little effort as possible. Adding Colors can be considered as customizing Categories, thus it would be more logical to show it in the Edit section only.

Second, when writing a Post you can also quick-add a new category. For consistency's sake we'd have to add the Colorpicker there too, which would be too much of a hassle user-wise at least.


== Screenshots ==

1. Category Edit page
2. Category Color Picker

== Changelog ==

= 1.2 =
* Removed extra codes.

= 1.1 =
* Fixed multiple categories colors

= 1.0 =
* First Release

== Upgrade Notice ==

= 1.2 =
* Removed extra codes.

= 1.1 =
* Fixed multiple categories colors

= 1.0 =
First Release.
