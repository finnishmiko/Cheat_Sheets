# Wordpress

## Security tweaks

### Hide real admin names

There seems to be bots trying to log in with real admin name. One fix is to change `user_nicename` in database to something else. That is used as user's slug. F.ex. to find user's posts slug is used like this: `mysite.com/author/<user_slug>`.

## DevOps pipeline

To prevent /uploads folder removal during site update add to Azure App Service deploy task additional argument:

```
-skip:Directory=\\wp-content\\uploads
```

## Site speed

Compare Wordpress site speed in different servers with speed tests:

- [Pagespeed](https://pagespeed.web.dev/)
- Lighthouse in Edge browser

Servers to test: 

- Server 1: Azure Web App (production P2V2 (7GB)) + Azure MySQL server (Basic, 2 vCore)
- Server 2: Lounea

Test runs: 4

Test | Azure | Lounea | mobile/web | tester
---|---|---|---|---
Performance | 88-93 | 87-91 | mobile | pagespeed
Performance | 79-81 | 81-83 | mobile | Lighthouse
First Contentful Paint | 1.4-1.4s | 1.2-1.2s | mobile | pagespeed
First Contentful Paint | 1.4-1.5s | 1.2-1.3s | mobile | Lighthouse
Time to Interactive | 3.0-3.1s | 1.2-3.3s | mobile | pagespeed
Time to Interactive | 1.4-3.3s | 1.2-3.2s | mobile | Lighthouse
Speed Index | 1.6-3.1s | 1.5-5.4s | mobile | pagespeed
Speed Index | 2.8-4.5s | 1.6-4.0s | mobile | Lighthouse
||||
Performance | 99 | 98-99 | web | pagespeed
First Contentful Paint | 0.3-0.4s | 0.3-0.4s | web | pagespeed
Time to Interactive | 0.3-0.4s | 0.3-0.4s | web | pagespeed
Speed Index | 0.6-1.1s | 0.7-1.3s | web | pagespeed
||||




## Create custom block with @wordpress/create-block

Install library:

```
npm i -D @wordpress/create-block
```

Create new plugin:

```
npm i @wordpress/create-block
cd wordpress/wp-content/plugins
npx @wordpress/create-block my-card-block
cd my-card-block
npm start
```

Go to wp-admin and enable plugin that was just created. Then the block is awailable via block editor.

### Attribute types

- null
- boolean
- object
- array
- number
- string
- integer

## Load fonts

It is recommended that you preconnect, then preload, and then finally load as follows:

```html
<!-- Add this line to header template -->
<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>

<!-- These two are set to functions.php as follows -->
<link rel='preload' as='style' href='https://fonts.googleapis.com/css2?family=Open+Sans|Roboto:wght@700&display=swap'>
<link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Open+Sans|Roboto:wght@700&display=swap'>
```

Enqueue fonts

https://stackoverflow.com/questions/50824181/preloading-google-fonts

```php
function your_theme_scripts() {
	wp_enqueue_style( 'Fonts-preload', 'https://fonts.googleapis.com/css2?family=Open+Sans|Roboto:wght@700&display=swap', array(), '1.0.0', 'all' );
	wp_enqueue_style( 'Fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans|Roboto:wght@700&display=swap', array(), '1.0.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'your_theme_scripts' );

# Change first stylesheet to preload:
add_filter('style_loader_tag', 'your_theme_loader_tag_filter', 10, 2);

function your_theme_loader_tag_filter($html, $handle) {
    if ($handle === 'Fonts-preload') {
        return str_replace("rel='stylesheet'",
            "rel='preload' as='style'", $html);
    }
    return $html;
}
```


## Check used and unused templates

Add following snippet to functions.php and then visit the site with query parameter: `?template_report`. [Reference](https://wordpress.stackexchange.com/a/311066).

```php
/**
 * Theme Template Usage Report
 *
 * Displays a data dump to show you the pages in your WordPress
 * site that are using custom theme templates.
 */
function theme_template_usage_report( $file = false ) {
    if ( ! isset( $_GET['template_report'] ) ) return;

    $templates = wp_get_theme()->get_page_templates();
    $report = array();

    echo '<h1>Page Template Usage Report</h1>';
    echo "<p>This report will show you any pages in your WordPress site that are using one of your theme's custom templates.</p>";

    foreach ( $templates as $file => $name ) {
        $q = new WP_Query( array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            'meta_query' => array( array(
                'key' => '_wp_page_template',
                'value' => $file
            ) )
        ) );

        $page_count = sizeof( $q->posts );

        if ( $page_count > 0 ) {
            echo '<p style="color:green">' . $file . ': <strong>' . sizeof( $q->posts ) . '</strong> pages are using this template:</p>';
            echo "<ul>";
            foreach ( $q->posts as $p ) {
                echo '<li><a href="' . get_permalink( $p, false ) . '">' . $p->post_title . '</a></li>';
            }
            echo "</ul>";
        } else {
            echo '<p style="color:red">' . $file . ': <strong>0</strong> pages are using this template, you should be able to safely delete it from your theme.</p>';
        }

        foreach ( $q->posts as $p ) {
            $report[$file][$p->ID] = $p->post_title;
        }
    }

    exit;
}
add_action( 'wp', 'theme_template_usage_report' );
```

## Some often used functions

| Function                   | Definition                                          |
| -------------------------- | --------------------------------------------------- |
| get_template_directory()   | Retrieves template directory path for current theme |
| get_stylesheet_directory() | As above but use this with Child theme              |

## Implement some logic before Wordpress loads

First file to load when page is accessed: `functions.php`.

```php
<?php
/**
 * If some function or check needs to be run at the beginning of page
 * visit, it can be in different file and called at the beginning of
 * functions.php:
 */
require get_template_directory() . '/inc/run-this-first.php';

/**
 * Some function can also be triggered with query parameter f.ex.:
 * www.example.com?reload=all
 */
$reload_check = filter_input(INPUT_GET, 'reload', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ( $reload_check == 'all' ) {
  //
}
```

## Docker: Wordpress and database in separate containers

Wordpress uploads folder
Copy production site's uploads-folder content to Docker container's volume:

docker cp uploads/ project_devcontainer_wp_app_1:/workspaces/project-wordpress/wordpress/wp-content

New Wordpress

```sh
# Set up MariaDB
docker run --name some-mariadb -e MYSQL_ROOT_PASSWORD=my-password -d mariadb:latest

# Logs of running container
docker logs some-mariadb

# Container's IPAddress can be found with this command. This is needed during Wordpress installation.
docker inspect some-mariadb

# Set up PhpMyAdmin
docker run --name some-phpmyadmin -d --link some-mariadb:db -p 8080:80 phpmyadmin/phpmyadmin

# Then go to http://localhost:8080 and log in with username _root_ and password _my-password_. Then create database table for wordpress.

# Set up Wordpress
docker run --name some-wordpress --link some-mariadb:mariadb -p 5000:80 -d wordpress

# Then go to http://localhost:5000 and start Wordpress with created database name, username _root_, password _my-password_ and database host _IPAddress_.

```

If existing database and Wordpress uploads, theme, plugin, etc. content are used, copy these files to local PC.

- Database can be imported to Docker SQL with PhpMyAdmin. Also update `wp_options` table's `siteurl` and `home` to contain `http://localhost:5000`
- `Uploads` folder can be set as a volume to Docker container. Note that in Windows local admin rights are needed to do this (share C-drive from the Docker UI).

```sh
# Use local files inside Docker container with --volume option. Use as many volumes as is needed.
docker run --name some-wordpress --link some-mariadb:mariadb -p 5000:80 -v C:/Path/to/local/Wordpress/wp-content/uploads:/var/www/html/wp-content/uploads -d wordpress
```

## Support multiple environments

Take server and DB specific settings from `wp-config.php` file and move them to separate files:

- MySQL settings
- Authentication Unique Keys and Salts
- WordPress debugging mode
- Site URLs

Then load the correct settings based on WP_ENV environmental variable.

```php
// set config file based on current environment
if ( strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ) {
    $config_file = 'config/wp-config.local.php';
} else if ( (strpos(getenv('WP_ENV'), 'staging') !== false) || (strpos(getenv('WP_ENV'))) ) {
    $config_file = 'config/wp-config.staging.php';
} else {
    $config_file = 'config/wp-config.production.php';
}

$path = dirname(__FILE__) . '/';
if ( file_exists($path . $config_file) ) {
    require_once $path . $config_file;
}
```

Duplicate DB for staging and update its `siteurl` and `home` settings from wp_options table.

## Database connection examples in different environments

```php
/*
  Example 1: Hard code connection info.
  Can be used for testing in local MySQL database.
*/
$connectstr_dbhost = 'localhost';
$connectstr_dbname = 'wp_databasename';
$connectstr_dbusername = 'root';
$connectstr_dbpassword = '';

/*
  Example 2: Use env variables for each variable.
  These could be for Azure server for MySQL database.

  In local WAMP-server set env variables to .htaccess file. F.ex:
  SetEnv ENV_NAME env_value
*/
if ( getenv('connectstr_dbhost') ) {
    $connectstr_dbhost = getenv('connectstr_dbhost');
}
if ( getenv('connectstr_dbname') ) {
    $connectstr_dbname = getenv('connectstr_dbname');
}
if ( getenv('connectstr_dbusername') ) {
    $connectstr_dbusername = getenv('connectstr_dbusername');
}
if ( getenv('connectstr_dbpassword') ) {
    $connectstr_dbpassword = getenv('connectstr_dbpassword');
}

/*
  Example 3: Use MySQL-type connection string with Azure Web App
*/
foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
        continue;
    }
    $connectstr_dbhost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $connectstr_dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $connectstr_dbusername = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $connectstr_dbpassword = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $connectstr_dbname);

/** MySQL database username */
define('DB_USER', $connectstr_dbusername);

/** MySQL database password */
define('DB_PASSWORD', $connectstr_dbpassword);

/** MySQL hostname */
define('DB_HOST', $connectstr_dbhost);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
```

## Planning example:

- SPA type front page with multiple sections and navigation between these parts
- New blog post type for some special use like listing company's customers
- Each product has own page and front page has section for these
- Blog page has grid of few latest blog posts with pagination
- Each blog can have comments

## Usually used files:

`style.css`,
`functions.php`,
`header.php`,
`footer.php`,
`screenshot.png` or `.jpg`.

| file                   | path                              | comment                                                                                      |
| ---------------------- | --------------------------------- | -------------------------------------------------------------------------------------------- |
| `front-page.php`       | `/`                               | Front page                                                                                   |
| `index.php`            | `/<page>`                         | Individual page for `/product-X` and `/blog`. Templates can be used based on **post_format** |
|                        |
| `content.php`          | `/*`                              | Template if not type speficied                                                               |
| `content-posttype.php` | `/*`                              | Post format specific template                                                                |
|                        |
| `single.php`           | `/<blog-post-X>`                  | Blog post format without any dedicated post format type                                      |
| `single-customer.php`  | `/customer/<customer-post-X>`     | Customer post specific format                                                                |
| `404.php`              | `/<any page that does not exist>` | Page at not found path                                                                       |
| `archive.php`          | `/category/<category-X>`          | List all posts of some category or tag or time etc.                                          |
| `archive-customer.php` | `/customer`                       | List of Customer posts of some type, if specified                                            |
| `comments.php`         | `/*`                              | Blog post commenting part                                                                    |

## Start Wordpress Theme development from scratch

Create a theme folder to `/wp-content/themes` folder.

Create files:

- `style.css` - Theme information in comment field
- `index.php` - primary template for site content
- `screenshot.jpg` - Theme image (1200x900) to present the theme
- `404.php` - template for not found page

And then enable this theme from Wordpress admin menu. If you visit the webpage you should see an empty page.

Create these two files with dynamic information:

- `header.php`:

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

- `footer.php`:

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

- download wp-bootstrap-navwalker plugin to theme folder
- create `functions.php` and require navwalker file and action to use it `after_setup_theme`.

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

- Create blog posts with few categories

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

- To the functions.php
  `add_theme_support('post-thumbnails')`

To index.php

```php
<?php if(has_post_thumbnail()) : ?>
  <div class="post-thumb">
    <?php the_post_thumbnail(); ?>
  </div>
<?php enfif; ?>
```



## Azure Linux Web app that uses Nginx

```sh
# Copy over the default nginx configuration file to your home directory:
cp /etc/nginx/sites-available/default /home/site/nginx.conf

# Modify the copied over nginx configuration file /home/site/nginx.conf and include your custom configuration.

# vi /home/site/nginx.conf

location / {
    index index.php index.html index.htm;
    try_files $uri $uri/ /index.php?$args;
}


# Create a bash file /home/site/startup.sh with the following contents:

cp /home/site/nginx.conf /etc/nginx/sites-available/default
service nginx reload

#Set your app service startup command to /home/site/startup.sh.
```

## Azure Windows web app

To convert high resolution images to the WebP format in an Azure Windows Web App server, you can use a third-party tool such as cwebp or WebPconv.

Here are the steps to convert images to WebP format using cwebp:

Download cwebp from the WebP website: https://developers.google.com/speed/webp/docs/precompiled

Extract the downloaded archive and copy the cwebp.exe file to a directory on your Azure Windows Web App server.

Open a command prompt and navigate to the directory where you copied the cwebp.exe file.

Use the following command to convert an image to the WebP format:

```sh
cwebp -q 80 input.jpg -o output.webp
```
Here, "input.jpg" is the name of the high resolution image file, and "output.webp" is the name of the output WebP file. The "-q" option specifies the quality level (in this case, 80).

Repeat the above command for each image you want to convert.

## Disable XML-RPC

```sh

<system.webServer>
  <rewrite>
    <rules>
      <rule name="Disable XML-RPC" stopProcessing="true">
        <match url="xmlrpc\.php" />
        <action type="CustomResponse" statusCode="403" statusReason="Forbidden: Access is denied" statusDescription="XML-RPC is disabled on this site" />
      </rule>
    </rules>
  </rewrite>
</system.webServer>
```

```sh
# Forbid xmlrpc.php requests
<Files xmlrpc.php>
  order deny,allow
  deny from all
  
  allow from XXX.XXX.XXX.XXX
</Files>

```

## Wordpress update asks FTP credentials

```sh
# Add following to wp-config.php

/** Update requires FTP-credentials */
define('FS_METHOD', 'direct');
```
