<?php
/**
 *  @Copyright
 *  @package     SIGE - Simple Image Gallery Extended - Plugin Joomla 2.5
 *  @author      Viktor Vogel {@link http://www.kubik-rubik.de}
 *  @version     2.5-3 - 04-Jun-2012
 *  @link        http://joomla-extensions.kubik-rubik.de/sige-simple-image-gallery-extended
 *
 *  @license GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if($_GET['img'] == "")
{
    exit("No parameters!");
}

$_GET['img'] = str_replace('..', '', urldecode($_GET['img']));
$_image_ = rawurldecode('../../../..'.$_GET['img']);
$file = basename($_image_);

if((substr(strtolower($file), -3) == 'jpg') OR (substr(strtolower($file), -3) == 'gif') OR (substr(strtolower($file), -3) == 'png'))
{
    $size = filesize($_image_);
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=".$file);
    header("Content-Length:".$size);
    readfile($_image_);
}
else
{
    exit("$file is not an image type!");
}
