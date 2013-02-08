<?php
/**
 * JFUploader 2.15.x Freeware - for Joomla 1.6.x
 *
 * Copyright (c) 2004-2011 TinyWebGallery
 * written by Michael Dempfle
 * 
 * @license GNU / GPL   
 *
 * For the latest version please go to http://jfu.tinywebgallery.com
**/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_jfuploader {

function _EDIT() {
  $canDo = JFUHelper::getActions();
  JToolBarHelper::title( JText::_( 'Contact' ) .': <small><small>[ '. $text .' ]</small></small>', 'generic.png' );
  JToolBarHelper::title('JFUploader', 'jfu_toolbar_title' );
						if ($canDo->get('core.edit')) {
                                JToolBarHelper::save('saveConfig');
                              }
						JToolBarHelper::cancel('config');
						JToolBarHelper::divider();
						JToolBarHelper::help('tfu',true);	
        }
        
function _USER() {
                              $canDo = JFUHelper::getActions();
						JToolBarHelper::title('JFUploader', 'jfu_toolbar_title' );
                              if ($canDo->get('core.manage')) {
                                JToolBarHelper::custom( 'upload', 'upload','upload',T_UPLOAD,false);
						  JToolBarHelper::divider();
						}
		                    if ($canDo->get('core.admin')) {
                              JToolBarHelper::custom( 'config', 'options','options',T_CONFIG,false);
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
						JToolBarHelper::custom( 'plugins', 'plugins','plugins',T_PLUGINS,false);
					  	JToolBarHelper::divider();
					  	}
					  	if ($canDo->get('core.admin')) {
                                JToolBarHelper::preferences('com_jfuploader', '550','850', JACTION_ADMIN_JFU_PERMISSIONS );
                                JToolBarHelper::divider();
                              }
						JToolBarHelper::help('tfu',true);	
        }
        
function _HELP() {
$canDo = JFUHelper::getActions();
            JToolBarHelper::title('JFUploader', 'jfu_toolbar_title' );
						if ($canDo->get('core.manage')) {
                              JToolBarHelper::custom( 'upload', 'upload','upload',T_UPLOAD,false);
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
                              JToolBarHelper::custom( 'config', 'options','options',T_CONFIG,false);
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.edit.state') && $canDo->get('core.admin')) {
                              JToolBarHelper::custom( 'user', 'user','user',T_USER,false); 
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
						JToolBarHelper::custom( 'plugins', 'plugins','plugins',T_PLUGINS,false);
					 	JToolBarHelper::divider();
					 	}
					 	if ($canDo->get('core.admin')) {
                                JToolBarHelper::preferences('com_jfuploader', '550','850', JACTION_ADMIN_JFU_PERMISSIONS );
                                JToolBarHelper::divider();
                              }
						JToolBarHelper::help('tfu',true);		
        }     
        
function _PLUGINS() {
 $canDo = JFUHelper::getActions();
                              JToolBarHelper::title('JFUploader', 'jfu_toolbar_title' );
						if ($canDo->get('core.manage')) {
                              JToolBarHelper::custom( 'upload', 'upload','upload',T_UPLOAD,false);
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
                              JToolBarHelper::custom( 'config', 'options','options',T_CONFIG,false);
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.edit.state') && $canDo->get('core.admin')) {
                                JToolBarHelper::custom( 'user', 'user','user',T_USER,false);
						  JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
                                JToolBarHelper::preferences('com_jfuploader', '550','850', JACTION_ADMIN_JFU_PERMISSIONS );
                                JToolBarHelper::divider();
                              }
						JToolBarHelper::help('tfu',true);		
        }    
        
function _UPLOAD() {
                              $canDo = JFUHelper::getActions();
						JToolBarHelper::title('JFUploader', 'jfu_toolbar_title' );
                              if ($canDo->get('core.admin')) {
                                JToolBarHelper::custom( 'config', 'options','options',T_CONFIG,false);					
						  JToolBarHelper::divider();
                              }						
						if ($canDo->get('core.edit.state') && $canDo->get('core.admin')) {
                                JToolBarHelper::custom( 'user', 'user','user',T_USER,false);						
						  JToolBarHelper::divider();
                              }		
                              if ($canDo->get('core.admin')) {			
						JToolBarHelper::custom( 'plugins', 'plugins','plugins',T_PLUGINS,false);
						JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
                                JToolBarHelper::preferences('com_jfuploader', '550','850', JACTION_ADMIN_JFU_PERMISSIONS );
                                JToolBarHelper::divider();
                              }
						JToolBarHelper::help('tfu',true);
				}

function _CONFIG() {
                              $canDo = JFUHelper::getActions();
                              JToolBarHelper::title('JFUploader', 'jfu_toolbar_title' );
                              if ($canDo->get('core.manage')) {
                                JToolBarHelper::custom( 'upload', 'upload','upload',T_UPLOAD,false);
						  JToolBarHelper::divider();
						}
						if ($canDo->get('core.edit.state') && $canDo->get('core.admin')) {
                                JToolBarHelper::custom( 'user', 'user','user',T_USER,false);
						  JToolBarHelper::divider();
						}
						if ($canDo->get('core.admin')) {
						JToolBarHelper::custom( 'plugins', 'plugins','plugins',T_PLUGINS,false);
					     JToolBarHelper::divider();
					     }
					     if ($canDo->get('core.admin')) {
     					     if ($canDo->get('core.create')) {
     						  JToolBarHelper::addNew('newConfig');
     						}
     						if ($canDo->get('core.edit')) {
                                     JToolBarHelper::editList('edit');
                                   }
                                   if ($canDo->get('core.create')) {
     						  JToolBarHelper::custom( 'copyConfig', 'copy', 'copy', T_COPY, false );
     						}
     						if ($canDo->get('core.manage')) {
     						  JToolBarHelper::save('saveMainConfig');
     						}
     						if ($canDo->get('core.delete')) {
                                   JToolBarHelper::deleteList('','deleteConfig');
                                   }
                              }
						JToolBarHelper::divider();
						if ($canDo->get('core.admin')) {
                                JToolBarHelper::preferences('com_jfuploader', '550','850', JACTION_ADMIN_JFU_PERMISSIONS );
                                JToolBarHelper::divider();
                              }
						JToolBarHelper::help('tfu',true);	
        }
}
?>