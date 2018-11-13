# Wordpress Theme Development

## Planning example:

* SPA type front page with multiple sections and navigation between these parts 
* New blog post type for some special use like listing company's customers
* Each product has own page and front page has section for these
* Blog page has grid of few latest blog posts with pagination
* Each blog can have comments 


## Usually used files:

`style.css`,
`functions.php`,
`header.php`,
`footer.php`,
`screenshot.png` or `.jpg`.

file |path |comment
---- |---  |---
`front-page.php`|`/`|Front page 
`index.php`|`/<page>`|Individual page for `/product-X` and `/blog`. Templates can be used based on __post_format__
||
`content.php`|`/*`|Template if not type speficied
`content-posttype.php`|`/*`|Post format specific template 
||
`single.php`|`/<blog-post-X>`|Blog post format without any dedicated post format type
`single-customer.php`|`/customer/<customer-post-X>`|Customer post specific format 
`404.php`|`/<any page that does not exist>`|Page at not found path 
`archive.php`|`/category/<category-X>`|List all posts of some category or tag or time etc.
`archive-customer.php`|`/customer`|List of Customer posts of some type, if specified
`comments.php`|`/*`|Blog post commenting part 
 


## Create static HTML pages to show how the site should look like

* `index.html` for front page
* `customer.html` for customer page
* `customer_story.html` for customer posts
* `product.html` for product page
* `blog.html` for blog page
* `blog_post.html` for blog post
* `style.css`
* etc.
* Include
  * Bootstrap
  * Font Awesome icons
  * etc.

## Start Wordpress Theme development from scratch

Create a theme folder to `/wp-content/themes` folder.

Create files:
* `style.css` - Theme information in comment field
* `index.php` - primary template for site content
* `screenshot.jpg` - Theme image (1200x900) to present the theme
* `404.php` - template for not found page

And then enable this theme from Wordpress admin menu. If you visit the webpage you should see an empty page.

Create these two files with dynamic information:
* `header.php`:
```php
<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php bloginfo( 'name' ); ?><?php wp_title( '|' ); ?></title>
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">
    <?php wp_head();  ?>
  </head>
<body>
```
* `footer.php`:
```php
    <footer>
      <div>&copy; <?php echo Date('Y'); ?> Company Oy</div>
    </footer>
    <?php wp_footer(); ?>
  </body>
</html>
```

And call them in `index.php` with `<?php get_header(); ?>` and `<?php get_footer(); ?>`


## Add new option to theme Customizer

```php
<?php

function dsb_customize_register($wp_customize){
    // Add new menu to Theme customizer
    $wp_customize->add_section('landingpage', array(
      'title'   => __('Landingpage', 'dsbusiness'),
      'description' => sprintf(__('Options for landingpage','dsbusiness')),
      'priority'    => 130
    ));

    // Add image selector
    $wp_customize->add_setting('landingpage_image', array(
      'default'   => get_bloginfo('template_directory').'/img/landing.jpg',
      'type'      => 'theme_mod'
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'landingpage_image', array(
      'label'   => __('Background Image', 'dsbusiness'),
      'section' => 'landingpage',
      'settings' => 'landingpage_image',
      'priority'  => 1
    )));

    // Add text input
    $wp_customize->add_setting('landingpage_heading', array(
      'default'   => _x('Company X', 'dsbusiness'),
      'type'      => 'theme_mod'
    ));

    $wp_customize->add_control('landingpage_heading', array(
      'label'   => __('Title', 'dsbusiness'),
      'section' => 'landingpage',
      'priority'  => 4
    ));


  }

add_action('customize_register', 'dsb_customize_register');

```

And use the information in client side. Create `front-page-php` and take contents from `index.php` and add following:

```php
<img src="<?php
            echo get_theme_mod(
              'landingpage_image',
              get_bloginfo('template_url').'/img/landing.jpg'
            );
          ?>"
   alt="">

<p>
  <?php
    echo get_theme_mod(
      'landingpage_heading',
      'Landingpage heading'
    );
  ?>
</p>
```

Import the file in functions.php
```php
<?php

// Include customizer.php File
require get_template_directory(). '/inc/customizer.php';

```





## Create main menu.

Add NavWalker to implement Bootstrap navigation menu with dropdown feature.
* download wp-bootstrap-navwalker plugin to theme folder
* create `functions.php` and require navwalker file and action to use it `after_setup_theme`.

```php
<?php
require_once('wp_bootstrap_navwalker.php')

function wpb_theme_setup() {
  register_nav_menus(array(
    'primary' => __('Primary Menu')
    ))
}

add_action('after_setup_theme', 'wpb_theme_setup');
```

Create main menu from the WP admin and create few pages.




## Add post loop to `index.php`
* Create blog posts with few categories

```php
<?php if(have_posts()) : ?>
  <?php while(have_posts()) : the_post(); ?>
```

Replace parts of the original index.html page with dynamic content f.ex.:
```php
  <?php the_permalink(); ?>
  <?php the_title(); ?>
  <?php the_date(); ?>
  <?php the_time('F j, Y g:i a'); ?>
  <?php the_author(); ?>
  <?php the_content(); ?>
  <?php the_excerpt(); ?>
```

## Enable thumbnail support
* To the functions.php
`add_theme_support('post-thumbnails')`

To index.php
```php
<?php if(has_post_thumbnail()) : ?>
  <div class="post-thumb">
    <?php the_post_thumbnail(); ?>
  </div>
<?php enfif; ?>
```


