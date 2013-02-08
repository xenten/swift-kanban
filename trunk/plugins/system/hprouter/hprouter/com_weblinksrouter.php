<?php
/**
* @version		$Id: router.php 10752 2008-08-23 01:53:31Z eddieajau $
* @package		Joomla
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

/**
 * This file has been modified by Hannes Papenberg for the HP Router plugin
 */

function WeblinksHPBuildRoute(&$query)
{
	static $items;

	$segments	= array();
	$itemid		= null;

	// Break up the newsfeed id into numeric and alias values.
	if (isset($query['id']) && strpos($query['id'], ':')) {
		list($query['id'], $query['alias']) = explode(':', $query['id'], 2);
	}

	// Break up the category id into numeric and alias values.
	if (isset($query['catid']) && strpos($query['catid'], ':')) {
		list($query['catid'], $query['catalias']) = explode(':', $query['catid'], 2);
	}

	// Get the menu items for this component.
	if (!$items) {
		$component	= &JComponentHelper::getComponent('com_weblinks');
		$menu		= &JSite::getMenu();
		$items		= $menu->getItems('componentid', $component->id);
	}

	// Search for an appropriate menu item.
	if (is_array($items))
	{
		// If only the option and itemid are specified in the query, return that item.
		if (!isset($query['view']) && !isset($query['id']) && !isset($query['catid']) && isset($query['Itemid'])) {
			$itemid = (int) $query['Itemid'];
		}

		// Search for a specific link based on the critera given.
		if (!$itemid) {
			foreach ($items as $item)
			{
				// Check if this menu item links to this view.
				if (isset($item->query['view']) && $item->query['view'] == 'weblink'
					&& isset($query['view']) && $query['view'] != 'category'
					&& isset($item->query['id']) && isset($query['id']) && $item->query['id'] == $query['id'])
				{
					$itemid	= $item->id;
				}
				elseif (isset($item->query['view']) && $item->query['view'] == 'category'
						&& isset($query['view']) && $query['view'] != 'weblink'
						&& isset($item->query['catid']) && $item->query['catid'] == $query['catid'])
				{
					$itemid	= $item->id;
				}
			}
		}

		// If no specific link has been found, search for a general one.
		if (!$itemid) {
			foreach ($items as $item)
			{
				if (isset($query['view']) && $query['view'] == 'weblink'
					&& isset($item->query['view']) && $item->query['view'] == 'category'
					&& isset($item->query['id']) && isset($query['catid'])
					&& $query['catid'] == $item->query['id'])
				{
					// This menu item links to the newsfeed view but we need to append the newsfeed id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['catalias']) ? $query['catalias'] : $query['catid'];
					$segments[]	= isset($query['alias']) ? $query['alias'] : $query['id'];
					break;
				}
				elseif (isset($query['view']) && $query['view'] == 'category'
					&& isset($item->query['view']) && $item->query['view'] == 'category'
					&& isset($item->query['id']) && isset($query['id']) && $item->query['id'] != $query['id'])
				{
					// This menu item links to the category view but we need to append the category id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['alias']) ? $query['alias'] : $query['id'];
					break;
				}

			}
		}

		// Search for an even more general link.
		if (!$itemid)
		{
			foreach ($items as $item)
			{
				if (isset($query['view']) && $query['view'] == 'weblink' && isset($item->query['view'])
					&& $item->query['view'] == 'categories' && isset($query['catid']) && isset($query['id']))
				{
					// This menu item links to the categories view but we need to append the category and newsfeed id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['catalias']) ? $query['catalias'] : $query['catid'];
					$segments[]	= isset($query['alias']) ? $query['alias'] : $query['id'];
					break;
				}
				elseif (isset($query['view']) && $query['view'] == 'category' && isset($item->query['view'])
					&& $item->query['view'] == 'categories' && !isset($query['catid']))
				{
					// This menu item links to the categories view but we need to append the category id to it.
					$itemid		= $item->id;
					$segments[]	= isset($query['alias']) ? $query['alias'] : $query['id'];
					break;
				}
			}
		}
	}

	// Check if the router found an appropriate itemid.
	if (!$itemid)
	{
		// Check if a id was specified.
		if (isset($query['id'])) {
			if(isset($query['catid'])) {
				$segments[] = isset($query['catalias']) ? $query['catid'].':'.$query['catalias'] : $query['catid'];
			}
			// Push the id onto the stack.
			$segments[] = isset($query['alias']) ? $query['id'].':'.$query['alias'] : $query['id'];
			unset($query['view']);
			unset($query['id']);
			unset($query['alias']);
			unset($query['view']);
			unset($query['catid']);
			unset($query['catalias']);
		} else {
			// Categories view.
			unset($query['view']);
		}
	} else {
		$query['Itemid'] = $itemid;

		// Remove the unnecessary URL segments.
		unset($query['view']);
		unset($query['id']);
		unset($query['alias']);
		unset($query['catid']);
		unset($query['catalias']);
	}

	return $segments;
}

function WeblinksHPParseRoute($segments)
{
	$vars	= array();

	// Get the active menu item.
	$menu	= &JSite::getMenu();
	$item	= &$menu->getActive();

	// Check if we have a valid menu item.
	if (is_object($item))
	{
		// Proceed through the possible variations trying to match the most specific one.
		if (isset($item->query['view']) && $item->query['view'] == 'category' && isset($segments[0]))
		{
			if(count($segments) == 2)
			{
				$vars['id']	= $segments[1];
			} else {
				$vars['id']	= $segments[0];
			}
			// Contact view.

			$vars['view']	= 'weblink';
		}
		elseif (isset($item->query['view']) && $item->query['view'] == 'categories' && count($segments) == 2)
		{
			// Weblink view.
			$vars['view']	= 'weblink';
			$vars['id']		= $segments[1];
			$vars['catid']	= $segments[0];
		}
		elseif (isset($item->query['view']) && $item->query['view'] == 'categories' && isset($segments[0]))
		{
			// Category view.
			$vars['view']	= 'category';
			$vars['id']		= $segments[0];
		}
	}
	else
	{
		// Count route segments
		$count = count($segments);

		// Check if there are any route segments to handle.
		if ($count)
		{
			if ($count == 2)
			{
				// We are viewing a weblink.
				$vars['view']	= 'weblink';
				$vars['catid']	= $segments[$count-2];
				$vars['id']		= $segments[$count-1];
			}
			else
			{
				// We are viewing a category.
				$vars['view']	= 'category';
				$vars['id']	= $segments[$count-1];
			}
		}
	}

	$alias = explode(':', $vars['id']);
	if((int) $alias[0] > 0)
	{
		$vars['id'] = $alias[0];
	} else {
		if(count($alias) > 1)
		{
			$vars['id'] = $alias[0].'-'.$alias[1];
		}

		$db =& JFactory::getDBO();
		if($vars['view'] == 'weblink')
		{
			$query = 'SELECT id FROM #__weblinks WHERE alias = '.$db->Quote($vars['id']);
		} elseif($vars['view'] == 'category') {
			$query = 'SELECT id FROM #__categories WHERE section = \'com_weblinks\' && alias = '.$db->Quote($vars['id']);
		}
		$db->setQuery($query);
		$vars['id'] = $db->loadResult();
	}

	return $vars;
}
