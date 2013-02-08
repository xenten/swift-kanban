<?php
/**
 * @version		$Id: router.php 7380 2007-05-06 21:26:03Z eddieajau $
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

function ContactHPBuildRoute(&$query)
{
	static $items;

	$segments	= array();
	$itemid		= null;

	// Get the menu items for this component.
	if (!$items) {
		$component	= &JComponentHelper::getComponent('com_contact');
		$menu		= &JSite::getMenu();
		$titems		= $menu->getItems('componentid', $component->id);
		if(is_array($titems))
		{
			foreach($titems as $titem)
			{
				$items[$titem->id] = $titem;
			}
		}
	}

	// Break up the contact id into numeric and alias values.
	if (isset($query['id']) && strpos($query['id'], ':')) {
		list($query['id'], $query['alias']) = explode(':', $query['id'], 2);
	}

	// Break up the category id into numeric and alias values.
	if (isset($query['catid']) && strpos($query['catid'], ':')) {
		list($query['catid'], $query['catalias']) = explode(':', $query['catid'], 2);
	}

	// Search for an appropriate menu item.
	if (is_array($items))
	{
		// If only the option and itemid are specified in the query, return that item.
		if (!isset($query['view']) && !isset($query['id']) && !isset($query['catid']) && isset($query['Itemid'])) {
			$itemid = (int) $query['Itemid'];
		}

		// Search for a specific link based on the critera given.
		if (!$itemid)
		{
			foreach ($items as $item)
			{
				// Check if this menu item links to this view.
				if (isset($item->query['view']) 
					&& ($item->query['view'] == 'contact' || $item->query['view'] == 'category') 
					&& isset($query['view']) 
					&& ($query['view'] == 'contact'	|| $query['view'] == 'category')
					&& (((isset($item->query['id']) && isset($query['id']) && $item->query['id'] == $query['id'])
					|| (isset($item->query['catid']) && isset($query['catid']) && $item->query['catid'] == $query['catid']))))
				{
					$itemid	= $item->id;
					break;
				}
			}
		}
	}
	// If no specific link has been found, search for a general one.
	if ($itemid)
	{
		if(isset($query['view']) && $query['view'] == 'contact' && $items[$itemid]->query['view'] != 'contact' && isset($query['id']))
		{
			$segments[] = isset($query['alias']) ? $query['alias'] : $query['id'];
		}
	} else {
		if(isset($query['catid'])) {
			$segments[] = isset($query['catalias']) ? $query['catalias'] : $query['catid'];
		}
		if(isset($query['id'])) {
			$segments[] = isset($query['alias']) ? $query['alias'] : $query['id'];
		}
	}
	unset($query['view']);
	unset($query['catid']);
	unset($query['catalias']);
	unset($query['id']);
	unset($query['alias']);
	
	return $segments;
}

function ContactHPParseRoute($segments)
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
			// Contact view.
			$vars['view']	= 'contact';
			/**
			 * This needs to be added to route
			 * the old URLs that have been wrongly
			 * used up till 1.5.5
			 */
			if(count($segments) == 1)
			{
				$vars['id']		= $segments[0];
			} else {
				$vars['id']		= $segments[1];
			}
			if (isset($item->query['catid'])){
				$vars['catid']	= $item->query['catid'];
			}
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
				// We are viewing a contact.
				$vars['view']	= 'contact';
				$vars['id']		= $segments[$count-1];
				$vars['catid']	= $segments[$count-2];
			}
			else
			{
				// We are viewing a category.
				$vars['view']	= 'category';
				$vars['catid']	= $segments[$count-1];
			}
		}
	}

	if(isset($vars['view']) && $vars['view'] == 'category')
	{
		$alias = explode(':', $vars['catid']);
		if((int) $alias[0] > 0)
		{
			$vars['catid'] = $alias[0];
		} else {
			if(count($alias) > 1)
			{
				$vars['catid'] = $alias[0].'-'.$alias[1];
			}

			$db =& JFactory::getDBO();
			$query = 'SELECT id FROM #__categories WHERE section = \'com_contact_details\' && alias = '.$db->Quote($vars['catid']);
			$db->setQuery($query);
			$vars['catid'] = $db->loadResult();
		}
	} else {
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
			$query = 'SELECT id FROM #__contact_details WHERE alias = '.$db->Quote($vars['id']);
			$db->setQuery($query);
			$vars['id'] = $db->loadResult();
		}
	}

	return $vars;
}
