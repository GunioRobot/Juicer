<?php
/**
 * A simple demonstration of Juicer.
 * 
 * Load up a custom combo file of YUI 3 components and run some javascript!
 * 
 * See php function at end for an example of "revisioning" javascript and stylesheets.
 * 
 * @author   Fabrice Denis
 * @date     20 Nov 2009
 * @license  Please view the LICENSE file that was distributed with this source code.
 */

// path to the project's root
define('APP_ROOT_DIR', realpath(dirname(__FILE__).'/../'));

// find path to the web document root (often named "public_html" on web hosts)
define('APP_WEB_ROOT', APP_ROOT_DIR . '/web');

// set this to 'dev' or 'prod'
define('APP_ENVIRONMENT', 'dev');

?>
<html>
  <head>
    <title>Juicer demo</title>
    <link href="<?php echo getRevvedUrl('/css/demo.juicy.css') ?>" type="text/css" rel="stylesheet"/>
    <script src="<?php echo getRevvedUrl('/js/demo.juicy.js') ?>" type="text/javascript"></script>
  </head>
  <body>

    <h1>Juicer demo</h1>
    
<p>This is the <a href="http://developer.yahoo.com/yui/3/examples/slider/slider_basic_clean.html">slider demo from the YUI 3 website</a>.</p>

<p>Inspect the page with Firebug and you can see that the asset urls are now pointing to our web document root.
   You can also see how the relative urls in the source library package (here, yui3) have been remapped to the web document root.</p>

<!-- BEGIN YUI 3 EXAMPLE ( http://developer.yahoo.com/yui/3/examples/slider/slider_basic_clean.html ) -->

<div id="demo" class="yui-skin-sam" style="background:#eee;border:1px solid #ccc;padding:10px;">
    <h4>Vertical Slider</h4>
    <p id="vert_value">Value: 30</p>
    <div class="vert_slider"></div>

    <h4>Horizontal Slider</h4>
    <p id="horiz_value">Value: 0</p>
    <div class="horiz_slider"></div>

</div>
<script type="text/javascript">
// Create a YUI instance and request the slider module and its dependencies
YUI().use("*", function (Y) {

// store the node to display the vertical Slider's current value
var v_report = Y.one('#vert_value'),
    vert_slider;
    
// instantiate the vertical Slider.  Use the classic thumb provided with the
// Sam skin
vert_slider = new Y.Slider({
    axis: 'y', // vertical Slider
    value: 30, // initial value
    railSize: '10em', // range the thumb can move through
    thumbImage: '/yui3/slider/assets/skins/sam/thumb-classic-y.png'
});

// callback function to display Slider's current value
function reportValue(e) {
    v_report.set('innerHTML', 'Value: ' + e.newVal);
}

vert_slider.after('valueChange', reportValue);

// render the slider into the first element with class vert_slider
vert_slider.render('.vert_slider');



// instantiate the horizontal Slider, render it, and subscribe to its
// valueChange event via method chaining.  No need to store the created Slider
// in this case.
new Y.Slider({
        railSize: '200px',
        thumbImage: '/yui3/slider/assets/skins/sam/thumb-classic-x.png'
    }).
    render('.horiz_slider').
    after('valueChange',function (e) {
        Y.one('#horiz_value').set('innerHTML', 'Value: ' + e.newVal);
    });

});
</script>

<!-- END OF YUI 3 EXAMPLE ( http://developer.yahoo.com/yui/3/examples/slider/slider_basic_clean.html ) -->
    
  </body>
</html>
<?php

/**
 * Returns a versioned resource url. 
 * 
 * The .htaccess files redirects those "versioned" files to a php script that
 * will strip the version number to get the actual file, and return the file
 * gzipped if possible to minimized download size.
 * 
 * In DEVELOPMENT we show the "original" url including the php script to
 * simulate what we want with the htaccess redirection.
 * 
 * In PRODUCTION, the filename is modified to point to the minified file.
 * This assumes that the "juiced" files are minified before deployment.
 * 
 * @param  string $url  Asset file url, relative to the web document root
 * @return string  Asset url with version number inserted
 */
function getRevvedUrl($url)
{
  // leave absolute URLs (usually from CDNs like Google and Yahoo) unchanged
  if (stripos($url, 'http:')===0)
  {
    return $url;
  }
  
  // do not use minified javascripts in debug environment
  if (APP_ENVIRONMENT === 'dev')
  {
    // in development environment, show the url called by mod_rewrite
    $url = '/version/cache.php?path='.urlencode($url);
  }
  else
  {
    // in production environment, get the minified version
    $url = str_replace('.juicy.', '.min.', $url);

    // set version string based on the source file's timestamp 
    $ver =  '_v' . filemtime(APP_WEB_ROOT . DIRECTORY_SEPARATOR . $url);

    $path = pathinfo($url);
    preg_match('/(.+)(\\.[a-z]+)/', $path['basename'], $matches);
    $url =  $path['dirname'] . '/' . $matches[1] . $ver . $matches[2];
  }
  return $url;
}
