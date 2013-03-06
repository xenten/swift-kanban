<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
require_once ( dirname(__FILE__).DS.'CssJsCompress'.DS.'js_merge_php4.php' );

/**
 * Joomla! Css and JS aggregation and compression Plugin
 *
 * @author		Joe <joomlatags@gmail.com>
 * @package		JoomlaTag
 * @subpackage	System
 */
class  plgSystemCssJsCompress extends JPlugin
{

	function plgSystemCssJsCompress(& $subject, $config)
	{
		parent::__construct($subject, $config);

	}

	function onAfterRender()
	{

		$isDebug=false;
		$css=$this->param('css');
		$javascript=$this->param('javascript');
		if(!$css&&!$javascript){
			return true;
		}
		// Only render for the front site
	 $app =& JFactory::getApplication();
	 if($app->getName() != 'site') {
	 	return true;
	 }
		$excludeComponents=$this->param('excludeComponents');
		//gzip checking
	 $isGZ=$this->param('gzip');
	 if($isGZ){
	 	$encoding = JResponse::_clientEncoding();
			if (!$encoding){
				$isGZ=false;
			}
				$isGZ=false;
			}
	 }
	 $body = JResponse::getBody();

	 	$cssRegex="/([^\"\'=]+\.(css))[\"\']/i";

	 	preg_match_all($linksRegex, $body, $matches);

	 	$links=@implode('',$matches[0]);

	 	preg_match_all($cssRegex,$links,$matches);


	 	$cssLinks= array();
	 	//$uri =& JURI::getInstance();

	 	foreach($matches[1] as $link){
	 		if(isInternal($link)){
	 			$parts=@explode( JURI::base(),$link);

	 			if(count($parts)>1&&strpos($parts[1],'.css')){
	 				$link=JPATH_ROOT.DS.$parts[1];
	 				$link=replaceSperator($link);
	 				$cssLinks[]=$link;
	 			}else if(strpos($link,'.css')){

	 				$link=$_SERVER['DOCUMENT_ROOT'].DS.$link;
	 				$link=replaceSperator($link);
	 					$cssLinks[]=$link;
	 			}
	 		}
	 	}
	 	$cssLinks=array_unique($cssLinks);
	 	// print_r($cssLinks);

	 	$excludeCss=array();
	 	$exclude=$this->param('excludeCss');
	 	if(isset($exclude)&& $exclude){
	 		$excludeCss=@explode(',',$exclude);
	 	foreach($cssLinks as $css){
	 		$shouldIgnore=false;
	 		foreach($excludeCss as $exd){
	 			if(endwith($css,$exd)){
	 				$shouldIgnore=true;
	 				break;
	 			}
	 		}
	 		if(!$shouldIgnore){
	 			$orderedCss[]=$css;
	 		}
	 	}
	 	$cssLinks=$orderedCss;

	 	//print_r($cssLinks);
	 		$singlecssFileName=md5(JURI::base().@implode('',$cssLinks)).'.css';
	 		if($isGZ){
	 			$singlecssFileName.='.gz';
	 		}
	 		$cssBaseDir=JPATH_CACHE.DS.'css';
	 		if(!file_exists($cssBaseDir)){
	 			mkdir($cssBaseDir);
	 			file_put_contents($cssBaseDir.DS.'index.html',$this->indexContent());
	 		}

	 		$cssSingle=$cssBaseDir.DS.$singlecssFileName;

	 		//print_r(JURI::base().'</br>');
	 		// print_r(JPATH_ROOT);
	 		if($isDebug||!file_exists($cssSingle)){
	 			//$isok=$compressor->makeCssOld($baseUrl,JPATH_ROOT,$cssLinks, $cssSingle,$isGZ);
	 		}


	 		if($isok){
	 			replaceCss(NULL,$excludeCss);
	 			$body=preg_replace_callback($linksRegex,'replaceCss',$body);
	 			$newImportCss='
	 			$body = preg_replace('/<\/head>/i',$newImportCss , $body,1);
	 			JResponse::setBody($body);
	 		}
	 }//if($css)

	 //done css
	  

	}



	function param($name){
		static $plugin,$pluginParams;
		if (!isset( $plugin )){
			$plugin =& JPluginHelper::getPlugin('system', 'CssJsCompress');
			$pluginParams = new JParameter( $plugin->params );
		}
		return $pluginParams->get($name);
	}

	function indexContent(){
		return "<html><body bgcolor='#FFFFFF'></body></html>";
	}


}
//end class

function isInternal($url) {
	$uri =& JURI::getInstance($url);
	$base = $uri->toString(array('scheme', 'host', 'port', 'path'));
	$host = $uri->toString(array('scheme', 'host', 'port'));
	if(stripos($base, JURI::base()) !== 0 && !empty($host)) {
		return false;
	}
	return true;
}

//end class
function replaceCss($matches,$exclude = NULL){

	static $_exclude;
	// Store exclude css for preg_replace_callback.
	if (isset($exclude)) {
		$_exclude = $exclude;
	}else if(isset($_exclude)){
		$cssRegex="/([^\"\'=]+\.(css))[\"\']/i";
		preg_match_all($cssRegex, $matches[0], $m);
		if(isset($m[1])&&count($m[1])){
			$cssFile=$m[1][0];
			if(count($_exclude)){
				foreach($_exclude as $exd){
					if($exd&&endwith($cssFile, $exd)){
						return $matches[0];
					}
				}
			}
			$ignore= count($m[0])&&endwith( $cssFile,'.css')&&!endwith( $cssFile,'.css.php')&&isInternal( $cssFile);
			if($ignore){
				return ' ';
			} else{
				return $matches[0];
			}
		}else{
			return $matches[0];
		}
	}

}

function replaceJs($matches,$exclude = NULL){
	static $_exclude;
	// Store exclude javascripts for preg_replace_callback.
	if (isset($exclude)) {
		$_exclude = $exclude;
	}else if(isset($_exclude)){
		$jsRegex="/src=[\"\']([^\"\']+)[\"\']/i";
		preg_match_all($jsRegex, $matches[0], $m);
		if(isset($m[1])&&count($m[1])){
			$scriptFile=$m[1][0];
			if(count($_exclude)){
				foreach($_exclude as $exd){
					if($exd&&endwith($scriptFile, $exd)){
						return $matches[0];
					}
				}
			}

			$ignore= count($m[0])&&endwith( $scriptFile,'.js')&&!endwith( $scriptFile,'.js.php')&&isInternal( $scriptFile);
			if($ignore){
				return ' ';
			} else{
				return $matches[0];
			}
		}else{
			return $matches[0];
		}
	}
}

function endwith($FullStr, $EndStr)  {
	$StrLen = strlen($EndStr);
	$FullStrEnd = substr($FullStr, strlen($FullStr) - $StrLen);
	if($FullStrEnd == $EndStr){
		return true;
	}
	return false;
}

function fileName($whole){
	$file=strrchr($whole,'/');
	$file=substr($file,1);
	if(isset($file)){
		return trim($file);
	}else{
		return $whole;
	}
}

function replaceSperator($link){
	$link=str_replace("\\\\",DS,$link);
	$link=str_replace("/\\/",DS,$link);
	$link=str_replace("\\/\\",DS,$link);
	$link=str_replace("\\",DS,$link);
	$link=str_replace("//",DS,$link);
	$link=str_replace("/",DS,$link);
	return $link;
}