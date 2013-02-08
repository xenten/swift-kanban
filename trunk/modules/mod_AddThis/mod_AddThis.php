<?php
/*
 * +--------------------------------------------------------------------------+
 * | Copyright (c) 2010 Add This, LLC                                         |
 * +--------------------------------------------------------------------------+
 * | This program is free software; you can redistribute it and/or modify     |
 * | it under the terms of the GNU General Public License as published by     |
 * | the Free Software Foundation; either version 3 of the License, or        |
 * | (at your option) any later version.                                      |
 * |                                                                          |
 * | This program is distributed in the hope that it will be useful,          |
 * | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 * | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 * | GNU General Public License for more details.                             |
 * |                                                                          |
 * | You should have received a copy of the GNU General Public License        |
 * | along with this program.  If not, see <http://www.gnu.org/licenses/>.    |
 * +--------------------------------------------------------------------------+
 */

	/**
	 *
	 * Creates AddThis sharing button and appends it to the user selected pages.
	 * Reads the user settings and creates the button accordingly.
	 *
	 * @author angel
	 * @version 1.1.3
	 */

    // no direct access
	defined('_JEXEC') or die('Restricted access');

    //Adds AddThis script to page
	appendAddThisScript($params);

	/**
	 * appendAddThisScript
	 *
	 * Reads button settings, creates corresponding AddThis button, reads AddThis configuration values,
	 * creates configuration object and Adds the resultant code to pages.
	 *
	 * @param object $params
	 * @return void
	 *
	 */
	function appendAddThisScript($params)
	{
		$outputValue = "";
		//Creating div elements for AddThis
		$outputValue = " <div class='joomla_add_this'>";
		$outputValue .= "<!-- AddThis Button BEGIN -->\r\n";

		//AddThis configuration script creation
		$outputValue .= "<script type='text/javascript'>\r\n";
		$outputValue .= "var addthis_product = 'jlp-1.2';\r\n";
		$outputValue .= "var addthis_config =\r\n{";

		//Get AddThis configuration values from parameter
		$configParams = populateParams($params);
		$configValue = prepareConfigValues($configParams);

		//Removing the last comma and end of line characters
		if(trim($configValue) != "")
		{
		   	$outputValue .= implode( ',', explode( ',', $configValue, -1 ));
		}
		$outputValue .= "}</script>\r\n";

		//Creates the button code depending on the button style chosen
    	$buttonValue = "";
		//Generates the button code for toolbox
	    if("toolbox" == $configParams["button_style"])
	    {
	       	 $buttonValue .= getToolboxScript($configParams["toolbox_services"], $configParams["icon_dimension"], $configParams["toolbox_more_services_mode"]);
	    }
	    //Generates button code for rest of the button styles
	    else
		{
			$buttonValue .= "<a  href='http://www.addthis.com/bookmark.php' onmouseover=\"return addthis_open(this, '', '[URL]', '[TITLE]'); \"   onmouseout='addthis_close();' onclick='return addthis_sendto();'>";
		    $buttonValue .= "<img src='";
		    //Custom image for button
			if ("custom" == trim($configParams["button_style"]))
		   	{
		        if ("" == trim($configParams["custom_url"]))
			    {
			           $buttonValue .= "http://s7.addthis.com/static/btn/v2/" .  getButtonImage('lg-share',$configParams["addthis_language"]);
		        }
		       	else $buttonValue .= trim($configParams["custom_url"]);
		    }
		   //Pointing to addthis button images
		    else
		    {
				$buttonValue .= "http://s7.addthis.com/static/btn/v2/" . getButtonImage($configParams["button_style"],$configParams["addthis_language"]);
			}
			$buttonValue .= "' border='0' alt='AddThis Social Bookmark Button' />";
			$buttonValue .= "</a>\r\n";
		}
		//Adding AddThis script to the page
		$outputValue .= $buttonValue;
		$outputValue .= "<script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js'></script>\r\n";
		$outputValue .= "<!-- AddThis Button END -->\r\n";
		$outputValue .= "</div>";

	    echo $outputValue;
	}

	/**
     * getToolboxScript
     *
     * Used for preparing the toolbox script
     *
     * @param string $services - comma seperated list of services
     * @param string $dimension - Icon dimensions (16 | 32)
     * @param string $mode - Toolbox mode (expanded | compact)
     * @return string - Returns the script for rendering the selected services
    */
    function getToolboxScript($services, $dimension, $mode)
    {
    	$dimensionStyle = ($dimension == "16") ? '' : ' addthis_32x32_style';
    	$toolboxScript  = "<div class='addthis_toolbox" . $dimensionStyle . " addthis_default_style'>";
    	$serviceList = explode(",", $services);
    	for ( $i = 0, $max_count = sizeof( $serviceList ); $i < $max_count; $i++ )
    	{
			$toolboxScript .= "<a class='addthis_button_" . $serviceList[$i] . "'></a>";
		}
		$toolboxScript .= ("expanded" == $mode || "compact" == $mode) ? "<a class='addthis_button_" . $mode ."'>Share</a>" : "<a class='addthis_" . $mode ." addthis_pill_style'></a>";
		//$toolboxScript .= "<a class='addthis_button_" . $mode ."'>Share</a>";
		$toolboxScript .= "</div>";
		return $toolboxScript;
    }

    /**
     * getButtonImage
     *
     * This is used for preparing the image button name.
     *
     * @param string $name - Button style of addthis button selected
     * @param string $language - The language selected for addthis button
     * @return string returns the button image file name
     */
    function getButtonImage($name, $language)
    {
       $buttonImage = "";
       if ("sm-plus" == $name)
            $buttonImage = $name . '.gif';
       elseif ($language != 'en')
       {
            if (in_array($name, array("lg-share", "lg-bookmark", "lg-addthis")))
                $buttonImage = 'lg-share-' . $language . '.gif';
            elseif(in_array($name, array("sm-share", "sm-bookmark")))
                $buttonImage = 'sm-share-' . $language . '.gif';
       }
       else
            $buttonImage = $name . '-' . $language . '.gif';
       return $buttonImage;
    }

    /**
     * populateParams
     *
     * Gets the plugin parameters and holds them as a collection
     *
     * @return Array of user selected AddThis configuration values
     */
     function populateParams($params)
     {
        $arrParams = array("profile_id", "button_style", "custom_url", "toolbox_services", "icon_dimension",
        				   "addthis_brand", "addthis_header_color", "addthis_header_background", "addthis_services_compact",
        				   "addthis_services_exclude", "addthis_services_expanded", "addthis_services_custom", "addthis_offset_top",
        				   "addthis_offset_left", "addthis_hover_delay", "addthis_click", "addthis_hover_direction",
        				   "addthis_use_addressbook", "addthis_508_compliant", "addthis_data_track_clickback",
        				   "addthis_hide_embed", "addthis_language", "show_frontpage", "toolbox_more_services_mode",
        				   "addthis_use_css", "addthis_ga_tracker");
        foreach ( $arrParams as $key => $value ) {
			$arrParamValues[$value] = $params->get($value);
		}
		return $arrParamValues;
     }

    /**
     * prepareConfigValues
     *
     * Prepares configuration values for AddThis button from user saved settings
     *
     * @return void
     */
    function prepareConfigValues($arrParamValues)
    {
    	$configValue = "";
		$arrConfigs = array("profile_id" => "pubid", "addthis_brand" => "ui_cobrand", "addthis_header_color" => "ui_header_color",
							"addthis_header_background" => "ui_header_background", "addthis_services_compact" => "services_compact",
							"addthis_services_exclude" => "services_exclude", "addthis_services_expanded" => "services_expanded",
							"addthis_services_custom" => "services_custom", "addthis_offset_top" => "ui_offset_top",
							"addthis_offset_left" => "ui_offset_left", "addthis_hover_delay" => "ui_delay", "addthis_click" => "ui_click",
							"addthis_hover_direction" => "ui_hover_direction", "addthis_use_addressbook" => "ui_use_addressbook",
							"addthis_508_compliant" => "ui_508_compliant", "addthis_data_track_clickback" => "data_track_clickback",
							"addthis_hide_embed" => "ui_hide_embed", "addthis_language" => "ui_language",
							"addthis_use_css" => "ui_use_css", "addthis_ga_tracker" => "data_ga_tracker");

		foreach ( $arrConfigs as $key => $value ) {
		   if(in_array($value, array("pubid", "ui_cobrand", "ui_header_color", "ui_header_background", "services_compact",
		               "services_exclude", "services_expanded", "ui_language")) && ($arrParamValues[$key] != ""))
		           $configValue .= $value . ":'" . $arrParamValues[$key] . "'," . PHP_EOL;
		   elseif(in_array($value, array("ui_offset_top", "ui_offset_left", "ui_delay", "ui_hover_direction", "data_ga_tracker",
		               "services_custom")) && ($arrParamValues[$key] != ""))
				   $configValue .= $value . ":" . $arrParamValues[$key] . "," .  PHP_EOL;
		   elseif(in_array($value, array("ui_click", "ui_use_addressbook", "ui_508_compliant", "data_track_clickback", "ui_hide_embed",
		               "ui_use_css", )) && ($arrParamValues[$key] != ""))
				   $configValue .= "1" == $arrParamValues[$key]? $value . ":true," . PHP_EOL : (("ui_use_css" == $value || "data_track_clickback" == $value) ? $value . ":false," . PHP_EOL : "");
		}
		return $configValue;
    }

