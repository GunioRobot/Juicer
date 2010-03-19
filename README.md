# Getting Started #

Juicer helps you to develop front-end code as reusable components, with a clear and logically organized structure, and produce combined files optimized for delivery to the web browser.

Juicer's main goal is that what you see on the page "is what you get". Both in development and production, you can use a single combined file for all the javascript, and another combined file for all the stylesheets.

In development, comments and whitespace is preserved in the concatenated output to help with debugging.

Juicer purposely does not include a "minification" process, for the sake of flexibility. Any minifier (YUI Compressor, JsMin, Packer, ...) can be used as part of a deployment script, as well as other optimizations like Google Closure Compiler. See "Minification and Deployment"  for a simple example using YUI Compressor.


## Tutorial: How Juicer Works ##

Included in the package is a simple php page and an `.htaccess` file to help you understand Juicer and experiment with it.

Juicer is written in php, you will need the [php interpreter](http://www.php.net/downloads.php) to run the tutorial.

To better demonstrate the advantage of "modularized" javascript, the demo makes use of Yahoo's YUI 3 library.

1. Download the [YUI 3 Library](http://developer.yahoo.com/yui/3/). Make sure to select "Full Developer Kit". Unzip the archive somewhere in your local development folder.
2. Edit `config/juicer.config.php` and change the pathnames to point to the `/build` folder within the YUI 3 package.

Have a look at `web/css/demo.juicy.css` and `web/js/demo.juicy.js`. You will see there are include-like directives.

Let's compile the javascript file. Make sure that your current path is the root folder for the Juicer project. If the php folder is not set in your PATH variable (Windows), you'll have to replace "php" with the absolute path (eg. C:/Program Files/php/php.exe):

    $ php lib/juicer/JuicerCLI.php -v --webroot web --config config/juicer.config.php --infile web/js/demo.juicy.js

The `--webroot` option tells Juicer where your web document root is. The `-v` flag will produce a "verbose" report:

    Verbose: ON
    Constants: YUI3, MAPPINGS, VERSION
     =require from %YUI3% (C:\Dev\Frameworks\yui\build)"
     =require file "/yui/yui-min.js" (line 12 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/oop/oop-min.js" (line 13 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/event-custom/event-custom-min.js" (line 14 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/attribute/attribute-min.js" (line 15 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/event/event-base-min.js" (line 16 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/pluginhost/pluginhost-min.js" (line 17 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/dom/dom-min.js" (line 18 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/node/node-min.js" (line 19 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/event/event-delegate-min.js" (line 20 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/event/event-focus-min.js" (line 21 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/base/base-min.js" (line 22 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/classnamemanager/classnamemanager-min.js" (line 23 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/widget/widget-min.js" (line 25 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/dd/dd-ddm-base-min.js" (line 26 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/dd/dd-drag-min.js" (line 27 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/dd/dd-constrain-min.js" (line 28 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/slider/slider-min.js" (line 29 in C:\Dev\Juicer\web\js\demo.juicy.js)
     =require file "/slider/slider-min.js" (line 29 in /Users/faB/Dev/Github/Juicer/web/js/demo.juicy.js)
    Execution time: 0.062 seconds.
    Output file: "web/js/demo.juiced.js".
    Success!

Juicer has concatenated a bunch of javascript files, all coming from the YUI 3 package.

The "Constants" line shows constants defined in the config file. Constants are values that can be substituted in javascripts and stylesheets (see "Constant Substitution").

**WARNING!** CHEESY CONVENTIONS: Juicer currently requires a `*.juicy.*` file pattern for the input file. It will replace the "juicy" into "juiced" to determine the output filename. The next versions will have an option to name the output file... phew.

To compile the demo stylesheet:

    $ php lib/juicer/JuicerCLI.php -v --list --webroot web --config config/juicer.config.php --infile web/css/demo.juicy.css

**Note!** *If you get a "Could not create folder" error, check the permissions of the web folder (`chmod a+w web`).*

The `--list` option adds information about assets in the report, `-v` (verbose output) is required with `--list`. Here's what it looks like:

    Verbose: ON
    Constants: YUI3, MAPPINGS, VERSION, FOOBAR
     =require from %YUI3% (C:\Dev\Frameworks\yui\build)"
     =require file "/cssfonts/fonts-min.css" (line 13 in C:\Dev\Juicer\web\css\demo.juicy.css)
     =require file "/widget/assets/skins/sam/widget.css" (line 17 in C:\Dev\Juicer\web\css\demo.juicy.css)
     =require file "/slider/assets/skins/sam/slider.css" (line 18 in C:\Dev\Juicer\web\css\demo.juicy.css)
     =provide (absolute path): "C:\Dev\Frameworks\yui\build\slider\assets\skins"
    
    List of assets used by stylesheets:
    
     <WEB_PATH>/yui3/slider/assets/skins/sam/rail-classic-x.png
     <WEB_PATH>/yui3/slider/assets/skins/sam/rail-classic-y.png
    
     2 assets referenced in stylesheets.
    
    Execution time: 0.082 seconds.
    Output file: "web/css/demo.juiced.css".
    Success!

Here in addition to concatenating stylesheets, Juicer has copied all the required assets (gif, png, etc) to the web document root. This is why we need to set the web document root with `--webroot`. Juicer automatically adapts the relative urls in the source stylesheets to the new location of the assets, where they have been copied in the web document root.

After compiling the css file you will find a new directory in the web root, with some png images in it:

    /web/yui3/slider/assets/skins/sam/

Juicer has determined the destination path name from the source path name, and the `MAPPING` option in the config file let us customize into which subfolder the assets go. This is very helpful to avoid collision between assets of different libraries: our mapping says that any assets that come from the YUI 3 repository will go into a path within the web document root that starts with "yui3".

Add a `juicer.demo.localhost` entry into your `httpd.conf`and `hosts` file, and load up the page:

    http://juicer.demo.localhost/index.php

You should be seeing the [Slider demo from YUI 3](http://developer.yahoo.com/yui/3/examples/slider/slider_basic.html). 

If you activate [Firebug](http://getfirebug.com/) and select the *Net* tab, you will see these two entries:

    http://juicer.demo.localhost/version/cache.php?path=%2Fcss%2Fdemo.juicy.css
    http://juicer.demo.localhost/version/cache.php?path=%2Fjs%2Fdemo.juicy.js

Juicer is building the files at run time, through the `cache.php` script and an `.htaccess` redirection. This is great for development, no need to run the script on the command line each time you modify a source file.

The `web/version/cache.php` script sends the contents of the file through Juicer and returns the results directly to the browser (if the filename matches the `*.juicy.*` pattern). It also sets far future expire headers for maximum browser caching, so make sure to hit F5 (Refresh) after editing the source files. This is covered later in the "File Versioning" section.

See "Minification and Deployment" for the minification and deployment steps.


# Benefits #

The `provide` and `require` commands enable a better way of managing your front-end code. Whether it is javascript modules, or [Object Oriented CSS](http://www.slideshare.net/stubbornella/object-oriented-css), you can maintain a single repository for your code and for third party libraries. No headaches trying to remember in which project was the latest version of your javascript widget.. improving one widget on one site, and loosing changes to another widget on the next project..
 
Another big advantage is the ability to logically structure your components into subfolders, each one containing its own stylesheets, javascript, and source files (such as TXT documentation, HTML test page, PSD files, and so on).

Your source files no longer sit in the web document root. You can share javascript and stylesheets between multiple projects without duplicating your code. The web site specific code can also sit outside of its web document root, for example in a library folder. Front-end code is now a first class citizen ;)


# Using Juicer #

When you run a file through Juicer, it will simply parse the file line by line, and when it comes accross a recognised command, it will replace the command with the results of the command, and then continue with the rest of the file.

The first release of Juicer uses a simple (and rather cheesy) convention: source files are in the `*.juicy.(js|css)` pattern. Output files are called "juiced" files, and are in the format `*.juiced.(js|css)`.

The functionality itself is in `lib/juicer/Juicer.php`. When Juicer is run on the command line, the script `lib/Juicer/JuicerCLI.php` is the one that is run. It acts as a wrapper, reading command line arguments, and passing them to Juicer along with the input file name.

When you run Juicer on the command line, you will always have an output file, the output file by default is named after the conventions explained above.

When Juicer is called dynamically through `.htaccess`, there is no temporary output file, since the output of Juicer is directly returned to the browser along with the http response headers. The script called by htaccess (`web/version/cache.php` in this demo) will also gzip the output.

## The Require From Command ##

The `=require from` command specifies the current source path for including files. You can specify an absolute path. For example to start including files from YUI 3 you could use:

    /* =require from "D:/Development/Frameworks/yui_3.0.0/build" */

A more efficient way to do this is to define a constant for each path you want to use, and then use the `%CONST%` notation in the require from directive:

Add a path to the config file (see `config/juicer.config.php` as example):

    'FOOBARLIB'   =>  'D:/Dev/FoobarLib'

Then use the constant notation:

    /* =require from "%FOOBARLIB%" */


You can use forward and backward slashes both in the require commands, and in the config file. The directory separators will be normalized to the system's default.


## The Require Command ##

The require command includes the contents of the file. Pathnames always start with a forward slash:

    /* =require "/foo/bar.js" */

Juicer will look for the file by joining the `require from` path and the `require` path together, in other words the `require`path is relative to the last `require from` path.

Note how Juicer commands are formatted within a C style comment. This format was chosen because it is valid for both css and js files, and will avoid annoying validation messages in the editor. Between the end of the command and the closing */ you can add notes for example:

    /* =require "/dd/dd-ddm-base-min.js" (required for YUI3 slider) */

The require command will be replaced with the contents of the file.

The included file can also include more files! To avoid an issue with "looping" includes, Juicer never includes a file more than once.


## The Provide Command ##

The `=provide` command is used to copy assets associated with a script or stylesheet to the web document root. Juicer automatically resolves urls in the stylesheets and adapts them to point to the new location of the copied assets under the document root.

Let's say you have a folder structure like this (we'll assume you have a MYLIB constant defined in the Juicer config file, pointing a repository of your javascript widgets):

    %MYLIB%/widgets/dialog/
    %MYLIB%/widgets/dialog/dialog.js
    %MYLIB%/widgets/dialog/dialog.css
    %MYLIB%/widgets/dialog/assets/dialog_button.gif
    %MYLIB%/widgets/dialog/assets/dialog_bg.gif
    %MYLIB%/widgets/dialog/assets/dialog.PSD   (the original PSD file)

In the dialog.css file you would add the provide directive:

	/* =provide "assets" */

Juicer will traverse the `assets` subfolder, and will copy all files to the web document root.

If the file already exists in the web folder, Juicer will compare the file modified timestamps. If the timestamps are *identical*, the file copy is skipped in order to improve Juicer's speed when run from the htaccess file.

The Juicer constructor accepts a `WEB_EXCL` option that sets file patterns to *ignore* when copying files. It is currently hardcoded in the JuicerCLI.php and web/version/cache.php files as `*.psd,*.txt,*.bak,*.css,*.js`, you may want to edit those (TODO). So in our example above, the PSD file would not be copied to the web document root.

The provide directive accepts relative and (semi-)absolute paths. If the path starts with a slash, it will be *relative to the last `require from`* directive. If the path is relative, it is relative to the folder of the file being processed (the file containing the provide directive).

The (semi-) absolute paths are useful when you want to provide assets from a different path, such as assets shared between multiple modules. Another common scenario is to provide assets from a thrid party library. Because you can not edit the libary code directly (what if you want to update it?):

    /* =require from "%YUI3%" */
    /* =provide "/assets/skins/sam" */
    /* =provide "/slider/assets" */


### How Juicer Translates Paths to the Web Folder ###

Coming back to the "what you see is what you get" motto, Juicer computes the destination path for source assets in a very predictable way:

* Use the absolute path of the specified web document root (set with --webroot on the command line, or `WEB_PATH` in the Juicer class constructor)
* If a MAPPING is defined in the config file that matches the path where the asset comes from, create the mapping subfolder.
* Create any remaining subfolders that are between the current `require from` path the `provide` path.
* Duplicate all the subfolders and assets from the `provide` path to the computed destination path.

For example if you defined the following require path, and corresponding mapping:

    // shortcut for =require from
    'YUI3'   =>  'D:/Dev/Frameworks/yui_3.0.0/build'
    
    // mapping to organize assets in the web document root
    'MAPPINGS' => array
    (
      'D:/Dev/Frameworks/yui_3.0.0/build'     => 'yui3'
    ),

And provide assets from the YUI 3 slider like so:

    /* =require from "%YUI3%" */
	/* =provide "/slider/assets" */

This will copy files from => to:

    <yui3path>/slider/assets/skins/sam/rail-classic-x.png   =>   <webroot>/yui3/slider/assets/skins/sam/rail-classic-x.png

You should probably always use a mapping if you use more than one library, to avoid collision between similarly named paths or assets.

Using the `-v --list` flags on the command line will give more information about assets.

Some additional notes:

* Files are copied whether or not they are referenced in the stylesheet. If you use images dynamically from a javascript, the files will be copied, and because the destination path is predictable, you can write the urls in your javascript, simply based on the source structure and the named mapping.
* By default Juicer will not copy stylesheets and scripts from within the provide path. That is because these are normally included directly into the combined file. If you wanted however to do dynamic loading of scripts, or styles, edit the WEB_EXCL parameters so that it does not exclude the copy of css/js files (a command line option will be added in further releases).


## Constant Substitution ##

Juicer will substitute constants defined in the config file, whenever the %CONST% pattern can be matched.

This can be useful for inserting version strings, localized text; and so on.


## Stripping Method Calls ##

With the `--strip` option method calls can be removed from the output file.

For example with `--strip console.log` Firebug console.log() calls will be removed from the output file.



## Using Juicer with Non-Php Web Applications ##

In order to run Juicer dynamically during development, all you need is the htaccess file to redirect the css/js files to a php script that will run the source file through Juicer. An example file is provided in `web/version/cache.php`.

If you deploy the `.juiced.js` files (the Juicer output), or the minified `.min.js` files, the php interpreter is not required on the production server at all. Your application only has to handle how the urls are output in your html templates. For example in production you can use the ".min.js" extension, and in development point to the source ".juicy.js" files, so that you still have a readable output while you work on the code. 


# Minification and Deployment #

On the production environment, we can optimize the file further by stripping all the whitespace and comments. This is called "minification" and can be done with another tool such as YUI Compressor by Julien Lecomte.

YUI Compressor is included in this package under the `tools`folder, for your convenience, so that you can test the code below.

To run the YUI Compressor, make sure that you have the Java run time installed. You can download the Java Runtime Environment (JRE) at [http://java.sun.com/javase/downloads/index.jsp](http://java.sun.com/javase/downloads/index.jsp).

Here is how you would compress the demo javascript file on the command line:

    $ java -jar tools/yuicompressor/yuicompressor-2.4.2.jar --type js web/js/demo.juiced.js -o web/js/demo.min.js

A deployment script could include a Juicer step, followed by a minification step. Gzipping and setting expire headers for maximum client caching can be handled via a php script (`web/version/cache.php`) or the server configuration. An example deploy script (Windows):

    SET juicercli=php lib/juicer/JuicerCLI.php
    SET minifier=java -jar tools/yuicompressor/yuicompressor-2.4.2.jar
    SET config=config/juicer.config.php
    %juicercli% --webroot web --config %config% --infile web/css/demo.juicy.css
    %juicercli% --webroot web --config %config% --infile web/js/demo.juicy.js
    %minifier% --type css web/css/demo.juiced.css -o web/css/demo.min.css
    %minifier% --type js web/js/demo.juiced.js -o web/js/demo.min.js

# File Versioning #

You'll have to make sure that your application outputs javascript and stylesheet urls to point to the .min version while running in production. The included demo demonstrates this, if you edit APP_ENVIRONMENT constant in index.php and set it to 'prod'. If you run the page again you will see the urls now point to:

    http://juicer.demo.localhost/css/demo.min_v1260833240.css
    http://juicer.demo.localhost/js/demo.min_v1260833238.js

There are a few things happening here:

* The file pattern now includes a revision number, based on the file's last modified timestamp. Because of this, everytime you modify the source files and rebuild/minify the scripts, the revision number will change. This makes sure your users will always have the most up to date scripts and styles.
* The .htaccess file looks for "versioned" files with a rewrite rule, it removes the version string, and redirects these through the php script for gzipping and setting far future expire headers. This improves caching by the client's browser, and greatly reduces the download file size.

There are many ways to do file versioning. The included example code is provided as a starting point.

In the Symfony php framework, a better way to do this would be to override the addStylesheet() and addJavascript() methods in sfWebResponse.class.php, and call the revision functionality from there, for example:

    public function addStylesheet($css, $position = '', $options = array())
    {
      $this->validatePosition($position);
      $css = $this->getRevvedUrl($css);
      $this->stylesheets[$position][$css] = $options;
    }


# HowTo's #

See the [[HOWTO]] file for a few tips related to third party libraries, and common scenarios.


# Known Issues #

* Resource filenames in stylesheets cannot use single quotes eg. background: url("foo'bar.gif"). The regexp in translateAssetsUrls() needs improvement.
* The exclude file option (for asset copying) is currently hardcoded in `JuicerCLI.php` and `web/version/cache.php`


# Contribute #

Please see the [[TODOS]] file for a list of ideas and the "Known Issues" section for items that can be worked on.


# Acknowledgments #

These are resources that helped me build Juicer, many thanks to the authors:

* [Automatically Version Your CSS and JavaScript Files](http://particletree.com/notebook/automatically-version-your-css-and-javascript-files/) by Kevin Hale at particletree.com
* [Gzip Your Minified JavaScript Files](http://www.julienlecomte.net/blog/2007/08/13/) by Julien Lecomte
* [Sprockets](http://getsprockets.org/) by Sam Stephenson is the inspiration for Juicer.


# Changes #

Please see the CHANGES file.

