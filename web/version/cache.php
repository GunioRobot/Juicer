<?php
/**
 * Outputs resource with gzip compression and far future expire headers.
 * 
 * The client's cache is automatically refreshed because javascript and stylesheet
 * filenames are "revved" with the file modified timestamp.
 * 
 * In development these files are also run through Juicer for automatic building
 * and copying of assets in the web folder, which saves running the build script
 * while working on those files.
 * 
 * Query parameters (set by htaccess redirection):
 * 
 *   path   Absolute path from the web root to resource file (starts with leading slash)
 * 
 * 
 * To do:
 * - going up the web folder is not safe? Could use realpath
 *   and make sure realpath of dest file contains the web realpath base
 * 
 * @author   Fabrice Denis
 * @license  Please view the LICENSE file that was distributed with this source code.
 */

class CacheResource
{
  const
    RELATIVE_PATH_TO_WEB = '../.',
    USE_GZIP_ENCODING    = true;
  
  function __construct()
  {
  }
  
  function execute()
  {
    $filepath = $this->getParameter('path');

    // on web server the path doesn't come with a leading slash, go figure
    if (strpos($filepath, DIRECTORY_SEPARATOR) !== 0) {
      $filepath = DIRECTORY_SEPARATOR . $filepath;
    }
    
    // ignore paths with a '..'
    if (preg_match('!\.\.!', $filepath)) {
      $this->throw404('error1');
    }

    // does the file exist?
    if (!file_exists(self::RELATIVE_PATH_TO_WEB . $filepath)) {
      $this->throw404('error3');
    }

    header("Expires: ".gmdate("D, d M Y H:i:s", time()+315360000)." GMT");
    header("Cache-Control: max-age=315360000");
    
    // output a mediatype header
    $extension = substr(strrchr($filepath, '.'), 1);
    switch ($extension)
    {
      case 'css':
        header("Content-type: text/css");
        break;
      case 'js':
        header("Content-type: text/javascript");
        break;
      // script should be called only for js and css files!  
      default:
        $this->throw404('error4');
        break;
    }

    // don't use gzip compression on IE6 SP1 (hotfix  http://support.microsoft.com/default.aspx?scid=kb;en-us;823386&Product=ie600)
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $IE6bug = (strpos($ua, 'MSIE 6') && strpos($ua, 'Opera') == -1);
    
    // For some very odd reason, "Norton Internet Security" unsets this
    $_SERVER['HTTP_ACCEPT_ENCODING'] = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';

    if (self::USE_GZIP_ENCODING && !$IE6bug && extension_loaded('zlib') && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false || strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false))
      ob_start('ob_gzhandler');
    else
      ob_start();

    // handle dynamic "juicing" of files here
    if (false !== strstr($filepath, '.juicy.'))
    {
      $webPath = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . self::RELATIVE_PATH_TO_WEB);
      $infile  = $webPath . $filepath;

      $options = array(
        'VERBOSE'  => false,
        'WEB_PATH' => $webPath,
        'WEB_EXCL' => '*.psd,*.txt,*.bak,*.css,*.js'
      );

      try {
        $config = $this->getJuicerConfig();
        $juicer = new Juicer($options, $config);
        $contents = $juicer->juice($infile);
        echo $contents;
      }
      catch (Exception $e) {
        $this->throw404('***EXCEPTION*** ' . $e->getMessage());
      }
    }
    else {
      // include the file as is (fastest)
      echo file_get_contents(self::RELATIVE_PATH_TO_WEB . $filepath);
    }
    
    ob_end_flush();
  }
  
  private function getJuicerConfig()
  {
    // require the file only in local environment
    $CORE_ROOT_DIR = realpath(dirname(__FILE__) . '/../..');
    require_once($CORE_ROOT_DIR.'/lib/juicer/Juicer.php');
    
    // get the config file  
    $configFile =  $CORE_ROOT_DIR . '/config/juicer.config.php';
    $config = require($configFile);

    return $config; 
  }
  
  private function getParameter($name, $default = null)
  {
    $value = isset($_GET[$name]) ? $_GET[$name] : $default;
    if ($value === null) {
      $this->throw404('Missing required parameter "%s"', $name);      
    }
    return $value;
  }
  
  private function throw404()
  {
    header("HTTP/1.0 500 Error");
    $args = func_get_args(); 
    $message = call_user_func_array('sprintf', $args);
    echo('Error HTTP 500: ' . $message);
    exit;
  }
}

$o = new CacheResource();
$o->execute();
