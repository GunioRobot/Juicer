<?php
/**
 * Sample Juicer configuration file.
 *
 * The configuration is given as a simple php array (note the return statement).
 *
 */

return array
(
  /*
   * We define some include paths. Simply declare a shortcut constant for
   * each path you want to include from.
   *
   * In this example configuration, we'll setup a shortcut for the YUI3
   * library, which we can use like so:
   *
   * =require from "%YUI3%"
   *
   */
  'YUI3'     => '/Users/faB/Dev/Frameworks/yui_3.0.0/build',

  /*
   * MAPPINGS is an associative array that lets you customize the destination
   * folder for assets. By default, the relative path _from_ the include path
   * in the require directive, becomes a path under the web document root.
   *
   * With a mapping you can map all assets from a source path to a specific
   * subfolder, this makes sure that multiple libraries do not mix their assets.
   *
   * The mapping value is the name of the extra subfolder to create in the web
   * document root.
   *
   */
  'MAPPINGS' => array
  (
    '/Users/faB/Dev/Frameworks/yui_3.0.0/build'     => 'yui3'
  ),

  /*
   * Define any number of constants here that we can use to substitute in the javascripts
   * and stylesheets.
   *
   * To insert a constant value in your javascript & stylesheets, use the pattern %CONST_NAME%,
   * for example:
   *
   *  console.log("FooBar version %VERSION% is loading... please wait...");
   *
   */
  'VERSION'  => '0.045.6 beta 1'
);
