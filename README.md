## Polos WordPress Theme

### About
Polos is a minimalist theme I needed for [my blog](http://kucrut.org).
Download it, (ab?)use it, or do whatever you want with it ( as long as it's permitted by the GPL v2 :D ).

This theme is made for modern browsers ONLY. So if you have many visitors using IE < 9,
either create a child theme and add the css fixes for IE, or simply don't use it :)

### Credits
* The whole WordPress and web dev community
* [Libertine Open Fonts Projekt] (http://www.linuxlibertine.org/)
* DevPress' [Dotos] (http://devpress.com/themes/dotos/) theme for the background image

### Notes
If you decided to create a child theme, you need to enqueue your child theme's stylesheet:
```php
function child_styles() {
	wp_enqueue_style( 'child-theme', get_bloginfo( 'stylesheet_url' ) );
}
add_action( 'wp_enqueue_scripts', 'child_styles', 11 );
````

If you don't want the default stylesheet to be used, you need to dequeue it:
```php
function child_styles() {
	wp_dequeue_style( 'polos' );
	wp_enqueue_style( 'child-theme', get_bloginfo( 'stylesheet_url' ) );
}
add_action( 'wp_enqueue_scripts', 'child_styles', 11 );
```

To remove default actions, you need to call `remove_action()` from `init` or `after_setup_theme` hooks:
```php
function child_setup() {
	remove_action( 'kct_after_branding', 'get_search_form' );
}
add_action( 'after_setup_theme', 'child_setup', 100 );
```

This theme doesn't support long site name and description by default. Also, the menu location (`main`) on
the header should only be used for menu with 3 or 4 parent menu items (sub-menus are not limited).
However, you can always create a child theme to support these:
1. If you need more parent menu items, override the main menu styles (mainly the absolute positioning)
2. If you have long site name and/or description, remove the search form on the header (see example above)
