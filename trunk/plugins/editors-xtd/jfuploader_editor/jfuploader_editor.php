<?php
/**
 * JFUploader editor button 2.15.x Freeware - for Joomla 1.6.x
 *
 * Copyright (c) 2004-2011 TinyWebGallery
 * written by Michael Dempfle
 *
 * @license GNU / GPL 
 *   
 * For the latest version please go to http://jfu.tinywebgallery.com
**/
 
 // no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
class plgButtonJFUploader_Editor extends JPlugin {
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param     object $subject The object to observe
     * @param     array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function plgJFUploader(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }
    function onDisplay($name)
    {
        $application = JFactory::getApplication();
        $my_first_daughters_name = "Anna";
        $user =& JFactory::getUser();
        $prefix = '';
        if ($application->isAdmin()) {
          $prefix = '../';
        }
        
        // needed because of user friendly urls!! Relative urls for the css does not work then.
        $relative_dir = parse_url(JURI::base());
	   $relative_dir = $relative_dir['path'];
        $relative_dir = rtrim($relative_dir,"\\/.") . '/'; // we replace to get a consistent output with different php versions!
        
          $css = ".button2-left .jfuButton {
                    background: transparent url(".$relative_dir.$prefix."plugins/editors-xtd/jfuploader_editor/jfuploader_editor.png) no-repeat 100% 0px;
                }";
                
        // we need to use the right part to get the right user!!    
        if ($application->isAdmin()) {
           // front and admin do not have the sames session. I have to create a secret token to check that the request is not modified
           $jConfig = new JConfig();
           $secret = $jConfig->secret;
           $ts =time();
           $token = md5($user->username . $my_first_daughters_name . $secret . $ts);
           $stub =  $prefix. "index.php";  
           $link = $stub . '?option=com_jfuploader&tmpl=component&type=jfuploader_editor&e_name='.$name .'&ts='.$ts.'&myid=' . $user->username . '&mytoken=' . $token;    
        } else {
           $stub =  "index.php";
           $link = $prefix.$stub . '?option=com_jfuploader&tmpl=component&type=jfuploader_editor&e_name='.$name;     
        }
                       
        $popup_width =  680;
        $popup_height = 520;
        
        if(@$this->params){	
			if(@$this->params->get('popup_width') > 0){
				$popup_width = (int)@$this->params->get('popup_width');
			}
			
			if(@$this->params->get('popup_height') > 0){
				$popup_height = (int)@$this->params->get('popup_height');
			}						
		}
                        
        $doc = & JFactory::getDocument();
        if ($application->isAdmin()) {
          $doc->addStyleDeclaration($css);
        }
        $button = new JObject();
        $button->set('modal', true);
        $button->set('text', JText::_('JFUploader'));
        $button->set('name', 'jfuButton');
        $button->set('options', "{handler: 'iframe',size: {x: $popup_width, y: $popup_height}}");
        $button->set('link', $link);
        return $button;
    }
}
?>