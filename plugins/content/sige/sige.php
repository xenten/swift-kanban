<?php
/**
 *  @Copyright
 *  @package     SIGE - Simple Image Gallery Extended - Plugin Joomla 2.5
 *  @author      Viktor Vogel {@link http://www.kubik-rubik.de}
 *  @version     2.5-4 - 16-Aug-2012
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
defined('_JEXEC') or die('Restricted access');

class plgContentSige extends JPlugin
{
    protected $_absolute_path;
    protected $_live_site;
    protected $_rootfolder;
    protected $_images_dir;
    protected $_syntax_parameter;
    protected $_params;
    protected $_article_title;
    protected $_thumbnail_max_height;
    protected $_thumbnail_max_width;
    protected $_turbo_html_read_in;
    protected $_turbo_css_read_in;

    function __construct(&$subject, $config)
    {
        $this->loadLanguage('plg_content_sige', JPATH_ADMINISTRATOR);

        $version = new JVersion();

        if($version->PRODUCT == 'Joomla!' AND $version->RELEASE != '2.5')
        {
            JError::raiseWarning(100, JText::_('PLG_CONTENT_SIGE_NEEDJ25'));
            return;
        }

        parent::__construct($subject, $config);

        if(isset($_SESSION["sigcount"]))
        {
            unset($_SESSION["sigcount"]);
        }

        if(isset($_SESSION["sigcountarticles"]))
        {
            unset($_SESSION["sigcountarticles"]);
        }

        $this->_absolute_path = JPATH_SITE;
        $this->_live_site = JURI::base();

        if(substr($this->_live_site, -1) == "/")
        {
            $this->_live_site = substr($this->_live_site, 0, -1);
        }

        $this->_params = array();
    }

    function onContentPrepare($context, &$article, &$params, $limitstart)
    {
        if(!preg_match("@{gallery}(.*){/gallery}@Us", $article->text))
        {
            return;
        }

        if(function_exists("gd_info"))
        {
            $gdinfo = gd_info();
            $gdsupport = array();
            $version = intval(preg_replace('/[[:alpha:][:space:]()]+/', '', $gdinfo['GD Version']));

            if($version != 2)
            {
                $gdsupport[] = '<div class="message">GD Bibliothek nicht vorhanden</div>';
            }

            if(substr(phpversion(), 0, 3) < 5.3)
            {
                if(!$gdinfo['JPG Support'])
                {
                    $gdsupport[] = '<div class="message">GD JPG Bibliothek nicht vorhanden</div>';
                }
            }
            else
            {
                if(!$gdinfo['JPEG Support'])
                {
                    $gdsupport[] = '<div class="message">GD JPG Bibliothek nicht vorhanden</div>';
                }
            }

            if(!$gdinfo['GIF Create Support'])
            {
                $gdsupport[] = '<div class="message">GD GIF Bibliothek nicht vorhanden</div>';
            }

            if(!$gdinfo['PNG Support'])
            {
                $gdsupport[] = '<div class="message">GD PNG Bibliothek nicht vorhanden</div>';
            }

            if(count($gdsupport))
            {
                foreach($gdsupport as $k => $v)
                {
                    echo $v;
                }
            }
        }

        if(!isset($_SESSION["sigcountarticles"]))
        {
            $_SESSION["sigcountarticles"] = -1;
        }

        if(preg_match_all("@{gallery}(.*){/gallery}@Us", $article->text, $matches, PREG_PATTERN_ORDER) > 0)
        {
            $_SESSION["sigcountarticles"]++;

            if(!isset($_SESSION["sigcount"]))
            {
                $_SESSION["sigcount"] = -1;
            }

            $this->_params['lang'] = JFactory::getLanguage()->getTag();

            foreach($matches[0] as $match)
            {
                $_SESSION["sigcount"]++;
                $sige_code = preg_replace("@{.+?}@", "", $match);
                $sige_array = explode(",", $sige_code);
                $this->_images_dir = $sige_array[0];

                unset($this->_syntax_parameter);
                $this->_syntax_parameter = array();

                if(count($sige_array) >= 2)
                {
                    for($i = 1; $i < count($sige_array); $i++)
                    {
                        $parameter_temp = explode("=", $sige_array[$i]);
                        if(count($parameter_temp) >= 2)
                        {
                            $this->_syntax_parameter[strtolower(trim($parameter_temp[0]))] = trim($parameter_temp[1]);
                        }
                    }
                }

                unset($sige_array);

                $this->setParams();

                if(!$this->_params['root'])
                {
                    $this->_rootfolder = '/images/';
                }
                else
                {
                    $this->_rootfolder = '/';
                }

                $this->_turbo_html_read_in = false;
                $this->_turbo_css_read_in = false;

                if($this->_params['turbo'])
                {
                    if($this->_params['turbo'] == 'new')
                    {
                        $this->_turbo_html_read_in = true;
                        $this->_turbo_css_read_in = true;
                    }
                    else
                    {
                        if(!file_exists($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/sige_turbo_html-'.$this->_params['lang'].'.txt'))
                        {
                            $this->_turbo_html_read_in = true;
                        }

                        if(!file_exists($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/sige_turbo_css-'.$this->_params['lang'].'.txt'))
                        {
                            $this->_turbo_css_read_in = true;
                        }
                    }
                }

                if(!$this->_params['turbo'] OR ($this->_params['turbo'] AND $this->_turbo_html_read_in))
                {
                    unset($images);
                    $noimage = 0;

                    if($dh = @opendir($this->_absolute_path.$this->_rootfolder.$this->_images_dir))
                    {
                        while(($f = readdir($dh)) !== false)
                        {
                            if(substr(strtolower($f), -3) == 'jpg' OR substr(strtolower($f), -3) == 'gif' OR substr(strtolower($f), -3) == 'png')
                            {
                                $images[] = array('filename' => $f);
                                $noimage++;
                            }
                        }

                        closedir($dh);
                    }

                    if($noimage)
                    {
                        if(!file_exists($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/index.html'))
                        {
                            file_put_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/index.html', '');
                        }

                        $jview = JRequest::getWord('view');

                        if($jview != 'featured' AND isset($article->title))
                        {
                            $this->_article_title = preg_replace("@\"@", "'", $article->title);
                        }

                        if($this->_params['random'] == 1)
                        {
                            shuffle($images);
                        }
                        elseif($this->_params['random'] == 2)
                        {
                            sort($images);
                        }
                        elseif($this->_params['random'] == 3)
                        {
                            rsort($images);
                        }
                        elseif($this->_params['random'] == 4 OR $this->_params['random'] == 5)
                        {
                            for($a = 0; $a < count($images); $a++)
                            {
                                $images[$a]['timestamp'] = filemtime($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                            }

                            if($this->_params['random'] == 4)
                            {
                                usort($images, array($this, 'timeasc'));
                            }
                            elseif($this->_params['random'] == 5)
                            {
                                usort($images, array($this, 'timedesc'));
                            }
                        }

                        $noimage_rest = 0;
                        $single_yes = false;

                        if($this->_params['single'])
                        {
                            $count = count($images);

                            if($images[0]['filename'] == $this->_params['single'])
                            {
                                if($this->_params['single_gallery'])
                                {
                                    $noimage_rest = $noimage;
                                    $this->_params['limit_quantity'] = 1;
                                }

                                $noimage = 1;
                                $single_yes = true;
                            }
                            else
                            {
                                for($a = 1; $a < $noimage; $a++)
                                {
                                    if($images[$a]['filename'] == $this->_params['single'])
                                    {
                                        if($this->_params['single_gallery'])
                                        {
                                            $noimage_rest = $noimage;
                                            $this->_params['limit_quantity'] = 1;
                                        }

                                        $noimage = 1;
                                        $images[$count] = $images[0];
                                        $images[0] = array('filename' => $this->_params['single']);
                                        unset($images[$a]);
                                        $images[$a] = $images[$count];
                                        unset($images[$count]);
                                        $single_yes = true;

                                        break;
                                    }
                                }
                            }
                        }

                        if($this->_params['fileinfo'])
                        {
                            $file_info = $this->getFileInfo();
                        }
                        else
                        {
                            $file_info = false;
                        }

                        if($this->_params['calcmaxthumbsize'])
                        {
                            $this->calculateMaxThumbnailSize($images);
                        }
                        else
                        {
                            $this->_thumbnail_max_height = $this->_params['height'];
                            $this->_thumbnail_max_width = $this->_params['width'];
                        }

                        $sige_css = '';

                        if($this->_params['caption'])
                        {
                            $caption_height = 20;
                        }
                        else
                        {
                            $caption_height = 0;
                        }

                        if($this->_params['salign'])
                        {
                            if($this->_params['salign'] == 'left')
                            {
                                $sige_css .= ".sige_cont_".$_SESSION["sigcount"]." {width:".($this->_thumbnail_max_width + $this->_params['gap_h'])."px;height:".($this->_thumbnail_max_height + $this->_params['gap_v'] + $caption_height)."px;float:left;display:inline-block;}\n";
                            }
                            elseif($this->_params['salign'] == 'right')
                            {
                                $sige_css .= ".sige_cont_".$_SESSION["sigcount"]." {width:".($this->_thumbnail_max_width + $this->_params['gap_h'])."px;height:".($this->_thumbnail_max_height + $this->_params['gap_v'] + $caption_height)."px;float:right;display:inline-block;}\n";
                            }
                            elseif($this->_params['salign'] == 'center')
                            {
                                $sige_css .= ".sige_cont_".$_SESSION["sigcount"]." {width:".($this->_thumbnail_max_width + $this->_params['gap_h'])."px;height:".($this->_thumbnail_max_height + $this->_params['gap_v'] + $caption_height)."px;display:inline-block;}\n";
                            }
                        }
                        else
                        {
                            $sige_css .= ".sige_cont_".$_SESSION["sigcount"]." {width:".($this->_thumbnail_max_width + $this->_params['gap_h'])."px;height:".($this->_thumbnail_max_height + $this->_params['gap_v'] + $caption_height)."px;float:left;display:inline-block;}\n";
                        }

                        $this->loadHeadData($sige_css);

                        if($this->_params['resize_images'])
                        {
                            $this->resizeImages($images);
                        }

                        if($this->_params['watermark'])
                        {
                            $this->watermark($images, $single_yes);
                        }

                        if($this->_params['limit'] AND (!$this->_params['single'] OR !$this->_params['single_gallery']))
                        {
                            $noimage_rest = $noimage;

                            if($noimage > $this->_params['limit_quantity'])
                            {
                                $noimage = $this->_params['limit_quantity'];
                            }
                        }

                        if($this->_params['thumbs'] AND !$this->_params['list'] AND !$this->_params['word'])
                        {
                            $this->thumbnails($images, $noimage);
                        }

                        if($this->_params['word'])
                        {
                            $noimage_rest = $noimage;
                            $this->_params['limit_quantity'] = 1;
                            $noimage = 1;
                        }

                        $html = '<!-- Simple Image Gallery Extended - Plugin Joomla! 2.5 by Kubik-Rubik.de Viktor Vogel -->';

                        if($this->_params['single'] AND $single_yes AND !$this->_params['word'])
                        {
                            $html .= '<ul class="sige_single">';
                        }
                        elseif(!$this->_params['list'] AND !$this->_params['word'])
                        {
                            $html .= '<ul class="sige">';
                        }

                        if($this->_params['list'] AND !$this->_params['word'])
                        {
                            $html .= '<ul>';
                        }

                        for($a = 0; $a < $noimage; $a++)
                        {
                            $html .= $this->htmlImage($images[$a]['filename'], $html, 0, $file_info, $a);
                        }

                        if($this->_params['list'] AND !$this->_params['word'])
                        {
                            $html .= '</ul>';
                        }

                        if(!$this->_params['list'] AND !$this->_params['word'])
                        {
                            $html .= "</ul>\n<span class=\"sige_clr\"></span>";
                        }

                        if(!empty($noimage_rest) AND !$this->_params['image_link'])
                        {
                            for($a = $this->_params['limit_quantity']; $a < $noimage_rest; $a++)
                            {
                                $html .= $this->htmlImage($images[$a]['filename'], $html, 1, $file_info, $a);
                            }
                        }

                        if($this->_params['copyright'])
                        {
                            if((!$this->_params['single'] OR ($this->_params['single'] AND !$single_yes)) AND !$this->_params['list'] AND !$this->_params['word'])
                            {
                                $html .= '<p class="sige_small"><a href="http://joomla-extensions.kubik-rubik.de" title="SIGE - Simple Image Gallery Extended - Joomla! Extensions by Kubik-Rubik.de - Viktor Vogel" target="_blank">Simple Image Gallery Extended</a></p>';
                            }
                        }
                    }
                    else
                    {
                        $html = '<strong>'.JText::_('NOIMAGES').'</strong><br /><br />'.JText::_('NOIMAGESDEBUG').' '.$this->_live_site.$this->_rootfolder.$this->_images_dir;
                    }

                    if($this->_turbo_html_read_in)
                    {
                        file_put_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/sige_turbo_html-'.$this->_params['lang'].'.txt', $html);
                    }
                }
                else
                {
                    $this->loadHeadData(1);

                    $html = file_get_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/sige_turbo_html-'.$this->_params['lang'].'.txt');
                }

                $article->text = preg_replace("@(<p>)?{gallery}".$sige_code."{/gallery}(</p>)?@s", $html, $article->text);
            }

            $this->loadHeadData();
        }
    }

    private function setParams()
    {
        $params = array('width', 'height', 'ratio', 'gap_v', 'gap_h', 'quality', 'quality_png', 'displaynavtip', 'navtip', 'limit', 'displaymessage', 'message', 'thumbs', 'thumbs_new', 'view', 'limit_quantity', 'noslim', 'caption', 'iptc', 'iptcutf8', 'print', 'salign', 'connect', 'download', 'list', 'crop', 'crop_factor', 'random', 'single', 'thumbdetail', 'watermark', 'encrypt', 'image_info', 'image_link', 'image_link_new', 'single_gallery', 'column_quantity', 'css_image', 'css_image_half', 'copyright', 'word', 'watermarkposition', 'watermarkimage', 'watermark_new', 'root', 'js', 'calcmaxthumbsize', 'fileinfo', 'turbo', 'resize_images', 'width_image', 'height_image', 'ratio_image', 'images_new', 'scaption');

        foreach($params as $value)
        {
            $this->_params[$value] = $this->getParams($value);
        }

        $count = $this->getParams('count', 1);

        if(!empty($count))
        {
            $_SESSION["sigcount"] = $count;
        }
    }

    private function getParams($param, $syntax_only = 0)
    {
        if($syntax_only == 1)
        {
            if(array_key_exists($param, $this->_syntax_parameter) AND $this->_syntax_parameter[$param] != "")
            {
                return $this->_syntax_parameter[$param];
            }
        }
        else
        {
            if(array_key_exists($param, $this->_syntax_parameter) AND $this->_syntax_parameter[$param] != "")
            {
                return $this->_syntax_parameter[$param];
            }
            else
            {
                return $this->params->get($param);
            }
        }
    }

    private function iptcinfo($image)
    {
        $info = array();
        $data = array();

        $size = getimagesize(JPATH_SITE.$this->_rootfolder.$this->_images_dir.'/'.$image, $info);

        if(isset($info['APP13']))
        {
            $iptc_php = iptcparse($info['APP13']);

            if(is_array($iptc_php))
            {
                if(isset($iptc_php["2#120"][0]))
                {
                    $data['caption'] = $iptc_php["2#120"][0];
                }
                else
                {
                    $data['caption'] = '';
                }

                if(isset($iptc_php["2#005"][0]))
                {
                    $data['title'] = $iptc_php["2#005"][0];
                }
                else
                {
                    $data['title'] = '';
                }

                if($this->_params['iptcutf8'] == 1)
                {
                    $iptctitle = html_entity_decode($data['title'], ENT_NOQUOTES);
                    $iptccaption = html_entity_decode($data['caption'], ENT_NOQUOTES);
                }
                else
                {
                    $iptctitle = utf8_encode(html_entity_decode($data['title'], ENT_NOQUOTES));
                    $iptccaption = utf8_encode(html_entity_decode($data['caption'], ENT_NOQUOTES));
                }
            }
            else
            {
                $iptctitle = '';
                $iptccaption = '';
            }
        }
        else
        {
            $iptctitle = '';
            $iptccaption = '';
        }
        $ret = array($iptctitle, $iptccaption);
        return $ret;
    }

    private function timeasc($a, $b)
    {
        return strcmp($a["timestamp"], $b["timestamp"]);
    }

    private function timedesc($a, $b)
    {
        return strcmp($b["timestamp"], $a["timestamp"]);
    }

    private function encrypt($imagename)
    {
        if($this->_params['encrypt'] == 0)
        {
            $image_hash = str_rot13($imagename);
        }
        elseif($this->_params['encrypt'] == 1)
        {
            $image_hash = md5($imagename);
        }
        elseif($this->_params['encrypt'] == 2)
        {
            $image_hash = sha1($imagename);
        }
        return $image_hash;
    }

    private function watermark($images, $single_yes)
    {
        if(!is_dir($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm'))
        {
            mkdir($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm', 0755);
            file_put_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/index.html', '');
        }

        if(empty($this->_params['single_gallery']) AND $single_yes)
        {
            $num = 1;
        }
        else
        {
            $num = count($images);
        }

        for($a = 0; $a < $num; $a++)
        {
            if(!empty($images[$a]['filename']))
            {
                $imagename = substr($images[$a]['filename'], 0, -4);
                $type = substr(strtolower($images[$a]['filename']), -3);
                $image_hash = $this->encrypt($imagename).'.'.$type;

                $filenamewm = $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash;

                if(!file_exists($filenamewm) OR $this->_params['watermark_new'] != 0)
                {
                    if($this->_params['watermarkimage'])
                    {
                        $watermarkimage = imagecreatefrompng($this->_absolute_path.'/plugins/content/sige/plugin_sige/'.$this->_params['watermarkimage']);
                    }
                    else
                    {
                        $watermarkimage = imagecreatefrompng($this->_absolute_path.'/plugins/content/sige/plugin_sige/watermark.png');
                    }

                    $width_wm = imagesx($watermarkimage);
                    $height_wm = imagesy($watermarkimage);

                    if(substr(strtolower($images[$a]['filename']), -3) == 'gif')
                    {
                        if($this->_params['resize_images'])
                        {
                            $origimage = imagecreatefromgif($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename']);
                        }
                        else
                        {
                            $origimage = imagecreatefromgif($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        }

                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);

                        $t_image = imagecreatetruecolor($width_ori, $height_ori);
                        imagecopy($t_image, $origimage, 0, 0, 0, 0, $width_ori, $height_ori);
                        $origimage = $t_image;

                        if($this->_params['watermarkposition'] == 1)
                        {
                            imagecopy($origimage, $watermarkimage, 0, 0, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 2)
                        {
                            imagecopy($origimage, $watermarkimage, $width_ori - $width_wm, 0, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 3)
                        {
                            imagecopy($origimage, $watermarkimage, 0, $height_ori - $height_wm, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 4)
                        {
                            imagecopy($origimage, $watermarkimage, $width_ori - $width_wm, $height_ori - $height_wm, 0, 0, $width_wm, $height_wm);
                        }
                        else
                        {
                            imagecopy($origimage, $watermarkimage, ($width_ori - $width_wm) / 2, ($height_ori - $height_wm) / 2, 0, 0, $width_wm, $height_wm);
                        }

                        imagegif($origimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash);
                    }
                    elseif(substr(strtolower($images[$a]['filename']), -3) == 'jpg')
                    {
                        if($this->_params['resize_images'])
                        {
                            $origimage = imagecreatefromjpeg($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename']);
                        }
                        else
                        {
                            $origimage = imagecreatefromjpeg($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        }

                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);

                        if($this->_params['watermarkposition'] == 1)
                        {
                            imagecopy($origimage, $watermarkimage, 0, 0, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 2)
                        {
                            imagecopy($origimage, $watermarkimage, $width_ori - $width_wm, 0, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 3)
                        {
                            imagecopy($origimage, $watermarkimage, 0, $height_ori - $height_wm, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 4)
                        {
                            imagecopy($origimage, $watermarkimage, $width_ori - $width_wm, $height_ori - $height_wm, 0, 0, $width_wm, $height_wm);
                        }
                        else
                        {
                            imagecopy($origimage, $watermarkimage, ($width_ori - $width_wm) / 2, ($height_ori - $height_wm) / 2, 0, 0, $width_wm, $height_wm);
                        }

                        imagejpeg($origimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash, $this->_params['quality']);
                    }
                    elseif(substr(strtolower($images[$a]['filename']), -3) == 'png')
                    {
                        if($this->_params['resize_images'])
                        {
                            $origimage = imagecreatefrompng($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename']);
                        }
                        else
                        {
                            $origimage = imagecreatefrompng($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        }

                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);

                        if($this->_params['watermarkposition'] == 1)
                        {
                            imagecopy($origimage, $watermarkimage, 0, 0, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 2)
                        {
                            imagecopy($origimage, $watermarkimage, $width_ori - $width_wm, 0, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 3)
                        {
                            imagecopy($origimage, $watermarkimage, 0, $height_ori - $height_wm, 0, 0, $width_wm, $height_wm);
                        }
                        elseif($this->_params['watermarkposition'] == 4)
                        {
                            imagecopy($origimage, $watermarkimage, $width_ori - $width_wm, $height_ori - $height_wm, 0, 0, $width_wm, $height_wm);
                        }
                        else
                        {
                            imagecopy($origimage, $watermarkimage, ($width_ori - $width_wm) / 2, ($height_ori - $height_wm) / 2, 0, 0, $width_wm, $height_wm);
                        }

                        imagepng($origimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash, $this->_params['quality_png']);
                    }

                    imagedestroy($origimage);
                    imagedestroy($watermarkimage);
                }
            }
        }
    }

    private function thumbnails($images, $noimage)
    {
        if(!is_dir($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs'))
        {
            mkdir($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs', 0755);
            file_put_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/index.html', '');
        }

        for($a = 0; $a < $noimage; $a++)
        {
            if(!empty($images[$a]['filename']))
            {
                $imagename = substr($images[$a]['filename'], 0, -4);
                $type = substr(strtolower($images[$a]['filename']), -3);
                $image_hash = $this->encrypt($imagename).'.'.$type;

                if($this->_params['watermark'])
                {
                    $filenamethumb = $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$image_hash;
                }
                else
                {
                    $filenamethumb = $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$images[$a]['filename'];
                }

                if(!file_exists($filenamethumb) OR $this->_params['thumbs_new'] != 0)
                {
                    list($new_h, $new_w) = $this->calculateSize($images[$a]['filename'], 1);

                    if(substr(strtolower($filenamethumb), -3) == 'gif')
                    {
                        if($this->_params['watermark'])
                        {
                            $origimage = imagecreatefromgif($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash);
                        }
                        else
                        {
                            $origimage = imagecreatefromgif($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        }

                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);
                        $thumbimage = imagecreatetruecolor($new_w, $new_h);

                        if($this->_params['crop'] AND ($this->_params['crop_factor'] > 0 AND $this->_params['crop_factor'] < 100))
                        {
                            list($crop_width, $crop_height, $x_coordinate, $y_coordinate) = $this->crop($width_ori, $height_ori);
                            imagecopyresampled($thumbimage, $origimage, 0, 0, $x_coordinate, $y_coordinate, $new_w, $new_h, $crop_width, $crop_height);
                        }
                        else
                        {
                            if($this->_params['thumbdetail'] == 1)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 2)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, $width_ori - $new_w, 0, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 3)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, $height_ori - $new_h, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 4)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, $width_ori - $new_w, $height_ori - $new_h, $new_w, $new_h, $new_w, $new_h);
                            }
                            else
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $width_ori, $height_ori);
                            }
                        }

                        if($this->_params['watermark'])
                        {
                            imagegif($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$image_hash);
                        }
                        else
                        {
                            imagegif($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$images[$a]['filename']);
                        }
                    }
                    elseif(substr(strtolower($filenamethumb), -3) == 'jpg')
                    {
                        if($this->_params['watermark'])
                        {
                            $origimage = imagecreatefromjpeg($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash);
                        }
                        else
                        {
                            $origimage = imagecreatefromjpeg($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        }

                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);
                        $thumbimage = imagecreatetruecolor($new_w, $new_h);

                        if($this->_params['crop'] AND ($this->_params['crop_factor'] > 0 AND $this->_params['crop_factor'] < 100))
                        {
                            list($crop_width, $crop_height, $x_coordinate, $y_coordinate) = $this->crop($width_ori, $height_ori);
                            imagecopyresampled($thumbimage, $origimage, 0, 0, $x_coordinate, $y_coordinate, $new_w, $new_h, $crop_width, $crop_height);
                        }
                        else
                        {
                            if($this->_params['thumbdetail'] == 1)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 2)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, $width_ori - $new_w, 0, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 3)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, $height_ori - $new_h, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 4)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, $width_ori - $new_w, $height_ori - $new_h, $new_w, $new_h, $new_w, $new_h);
                            }
                            else
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $width_ori, $height_ori);
                            }
                        }

                        if($this->_params['watermark'])
                        {
                            imagejpeg($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$image_hash, $this->_params['quality']);
                        }
                        else
                        {
                            imagejpeg($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$images[$a]['filename'], $this->_params['quality']);
                        }
                    }
                    elseif(substr(strtolower($filenamethumb), -3) == 'png')
                    {
                        if($this->_params['watermark'])
                        {
                            $origimage = imagecreatefrompng($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash);
                        }
                        else
                        {
                            $origimage = imagecreatefrompng($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        }

                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);
                        $thumbimage = imagecreatetruecolor($new_w, $new_h);

                        if($this->_params['crop'] AND ($this->_params['crop_factor'] > 0 AND $this->_params['crop_factor'] < 100))
                        {
                            list($crop_width, $crop_height, $x_coordinate, $y_coordinate) = $this->crop($width_ori, $height_ori);
                            imagecopyresampled($thumbimage, $origimage, 0, 0, $x_coordinate, $y_coordinate, $new_w, $new_h, $crop_width, $crop_height);
                        }
                        else
                        {
                            if($this->_params['thumbdetail'] == 1)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 2)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, $width_ori - $new_w, 0, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 3)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, $height_ori - $new_h, $new_w, $new_h, $new_w, $new_h);
                            }
                            elseif($this->_params['thumbdetail'] == 4)
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, $width_ori - $new_w, $height_ori - $new_h, $new_w, $new_h, $new_w, $new_h);
                            }
                            else
                            {
                                imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $width_ori, $height_ori);
                            }
                        }

                        if($this->_params['watermark'])
                        {
                            imagepng($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$image_hash, $this->_params['quality_png']);
                        }
                        else
                        {
                            imagepng($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$images[$a]['filename'], $this->_params['quality_png']);
                        }
                    }

                    imagedestroy($origimage);
                    imagedestroy($thumbimage);
                }
            }
        }
    }

    private function resizeImages($images)
    {
        if(!is_dir($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages'))
        {
            mkdir($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages', 0755);
            file_put_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/index.html', '');
        }

        $num = count($images);

        for($a = 0; $a < $num; $a++)
        {
            if(!empty($images[$a]['filename']))
            {
                $filenamethumb = $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename'];

                if(!file_exists($filenamethumb) OR $this->_params['images_new'] != 0)
                {
                    list($new_h, $new_w) = $this->calculateSize($images[$a]['filename'], 0);

                    if(substr(strtolower($filenamethumb), -3) == 'gif')
                    {
                        $origimage = imagecreatefromgif($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);
                        $thumbimage = imagecreatetruecolor($new_w, $new_h);
                        imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $width_ori, $height_ori);
                        imagegif($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename']);
                    }
                    elseif(substr(strtolower($filenamethumb), -3) == 'jpg')
                    {
                        $origimage = imagecreatefromjpeg($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);
                        $thumbimage = imagecreatetruecolor($new_w, $new_h);
                        imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $width_ori, $height_ori);
                        imagejpeg($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename'], $this->_params['quality']);
                    }
                    elseif(substr(strtolower($filenamethumb), -3) == 'png')
                    {
                        $origimage = imagecreatefrompng($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$images[$a]['filename']);
                        $width_ori = imagesx($origimage);
                        $height_ori = imagesy($origimage);
                        $thumbimage = imagecreatetruecolor($new_w, $new_h);
                        imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $new_w, $new_h, $width_ori, $height_ori);
                        imagepng($thumbimage, $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$images[$a]['filename'], $this->_params['quality_png']);
                    }

                    imagedestroy($origimage);
                    imagedestroy($thumbimage);
                }
            }
        }
    }

    private function loadHeadData($sige_css = 0)
    {
        if(!empty($sige_css))
        {
            if(!$this->_params['turbo'] OR ($this->_params['turbo'] AND $this->_turbo_css_read_in))
            {
                $head = "<style type='text/css'>\n".$sige_css."</style>";

                if($this->_turbo_css_read_in)
                {
                    file_put_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/sige_turbo_css-'.$this->_params['lang'].'.txt', $head);
                }
            }
            else
            {
                $head = file_get_contents($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/sige_turbo_css-'.$this->_params['lang'].'.txt');
            }
        }
        else
        {
            $head = array();

            if($_SESSION["sigcountarticles"] == 0)
            {
                $head[] = '<link rel="stylesheet" href="'.$this->_live_site.'/plugins/content/sige/plugin_sige/sige.css" type="text/css" media="screen" />';

                if($this->_params['js'] == 0)
                {
                    if($this->_params['lang'] == "de-DE")
                    {
                        $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/slimbox.js"></script>';
                    }
                    else
                    {
                        $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/slimbox_en.js"></script>';
                    }

                    $head[] = '<link rel="stylesheet" href="'.$this->_live_site.'/plugins/content/sige/plugin_sige/slimbox.css" type="text/css" media="screen" />';
                }
                elseif($this->_params['js'] == 1)
                {
                    if($this->_params['lang'] == "de-DE")
                    {
                        $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/lytebox.js"></script>';
                    }
                    else
                    {
                        $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/lytebox_en.js"></script>';
                    }
                    $head[] = '<link rel="stylesheet" href="'.$this->_live_site.'/plugins/content/sige/plugin_sige/lytebox.css" type="text/css" media="screen" />';
                }
                elseif($this->_params['js'] == 2)
                {
                    if($this->_params['lang'] == "de-DE")
                    {
                        $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/shadowbox.js"></script>';
                    }
                    else
                    {
                        $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/shadowbox_en.js"></script>';
                    }

                    $head[] = '<link rel="stylesheet" href="'.$this->_live_site.'/plugins/content/sige/plugin_sige/shadowbox.css" type="text/css" media="screen" />';
                    $head[] = '<script type="text/javascript">Shadowbox.init();</script>';
                }
                elseif($this->_params['js'] == 3)
                {
                    $head[] = '<script type="text/javascript" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/milkbox.js"></script>';
                    $head[] = '<link rel="stylesheet" href="'.$this->_live_site.'/plugins/content/sige/plugin_sige/milkbox.css" type="text/css" media="screen" />';
                }
            }

            $head = "\n".implode("\n", $head)."\n";
        }

        $document = JFactory::getDocument();

        if($document instanceof JDocumentHTML)
        {
            $document->addCustomTag($head);
        }
    }

    private function htmlImage($image, &$html, $noshow, $file_info, $a)
    {
        if(!empty($image))
        {
            $imagename = substr($image, 0, -4);
            $type = substr(strtolower($image), -3);
            $image_hash = $this->encrypt($imagename).'.'.$type;

            $file_info_set = false;

            if(!empty($file_info))
            {
                foreach($file_info as $value)
                {
                    if($value[0] == $image)
                    {
                        $image_title = $value[1];

                        if(isset($value[2]))
                        {
                            $image_description = $value[2];
                        }
                        else
                        {
                            $image_description = false;
                        }
                        $file_info_set = true;

                        break;
                    }
                }
            }

            if(!$file_info_set)
            {
                $image_title = $imagename;
                $image_description = false;
            }

            if($this->_params['iptc'] == 1)
            {
                list($title_iptc, $caption_iptc) = $this->iptcinfo($image);

                if(!empty($title_iptc))
                {
                    $image_title = $title_iptc;
                }

                if(!empty($caption_iptc))
                {
                    $image_description = $caption_iptc;
                }
            }

            if(empty($noshow))
            {
                if($this->_params['list'] AND !$this->_params['word'])
                {
                    $html .= '<li>';
                }
                elseif($this->_params['word'])
                {
                    $html .= '<span>';
                }
                else
                {
                    $html .= '<li class="sige_cont_'.$_SESSION["sigcount"].'"><span class="sige_thumb">';
                }
            }

            if($this->_params['image_link'] AND empty($noshow))
            {
                $html .= '<a href="http://'.$this->_params['image_link'].'" title="'.$this->_params['image_link'].'" ';

                if($this->_params['image_link_new'])
                {
                    $html .= 'target="_blank"';
                }

                $html .= '>';
            }
            elseif($this->_params['noslim'] AND $this->_params['css_image'] AND empty($noshow))
            {
                $html .= '<a class="sige_css_image" href="#sige_thumbnail">';
            }
            elseif(!$this->_params['noslim'])
            {
                if($this->_params['watermark'])
                {
                    if(empty($noshow))
                    {
                        $html .= '<a href="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash.'"';
                    }
                    else
                    {
                        $html .= '<span style="display: none"><a href="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash.'"';
                    }
                }
                else
                {
                    if($this->_params['resize_images'])
                    {
                        if(empty($noshow))
                        {
                            $html .= '<a href="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$image.'"';
                        }
                        else
                        {
                            $html .= '<span style="display: none"><a href="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$image.'"';
                        }
                    }
                    else
                    {
                        if(empty($noshow))
                        {
                            $html .= '<a href="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/'.$image.'"';
                        }
                        else
                        {
                            $html .= '<span style="display: none"><a href="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/'.$image.'"';
                        }
                    }
                }

                if(empty($noshow))
                {
                    if($this->_params['css_image'])
                    {
                        $html .= ' class="sige_css_image"';
                    }
                }

                if($this->_params['connect'])
                {
                    if($this->_params['view'] == 0)
                    {
                        $html .= ' rel="lightbox.sig'.$this->_params['connect'].'"';
                    }
                    elseif($this->_params['view'] == 1)
                    {
                        $html .= ' rel="lytebox.sig'.$this->_params['connect'].'"';
                    }
                    elseif($this->_params['view'] == 2)
                    {
                        $html .= ' rel="lyteshow.sig'.$this->_params['connect'].'"';
                    }
                    elseif($this->_params['view'] == 3)
                    {
                        $html .= ' rel="shadowbox[sig'.$this->_params['connect'].']"';
                    }
                    elseif($this->_params['view'] == 4)
                    {
                        $html .= ' data-milkbox="milkbox-'.$this->_params['connect'].'"';
                    }
                }
                else
                {
                    if($this->_params['view'] == 0)
                    {
                        $html .= ' rel="lightbox.sig'.$_SESSION["sigcount"].'"';
                    }
                    elseif($this->_params['view'] == 1)
                    {
                        $html .= ' rel="lytebox.sig'.$_SESSION["sigcount"].'"';
                    }
                    elseif($this->_params['view'] == 2)
                    {
                        $html .= ' rel="lyteshow.sig'.$_SESSION["sigcount"].'"';
                    }
                    elseif($this->_params['view'] == 3)
                    {
                        $html .= ' rel="shadowbox[sig'.$_SESSION["sigcount"].']"';
                    }
                    elseif($this->_params['view'] == 4)
                    {
                        $html .= ' data-milkbox="milkbox-'.$_SESSION["sigcount"].'"';
                    }
                }

                $html .= ' title="';

                if($this->_params['displaynavtip'] AND !empty($this->_params['navtip']))
                {
                    $html .= $this->_params['navtip'].'&lt;br /&gt;';
                }

                if($this->_params['displaymessage'] AND isset($this->_article_title))
                {
                    if(!empty($this->_params['message']))
                    {
                        $html .= $this->_params['message'].': ';
                    }

                    $html .= '&lt;em&gt;'.$this->_article_title.'&lt;/em&gt;&lt;br /&gt;';
                }

                if($this->_params['image_info'])
                {
                    $html .= '&lt;strong&gt;&lt;em&gt;'.$image_title.'&lt;/em&gt;&lt;/strong&gt;';

                    if($image_description)
                    {
                        $html .= ' - '.$image_description;
                    }
                }

                if($this->_params['print'] == 1)
                {
                    if($this->_params['watermark'])
                    {
                        $html .= ' &lt;a href=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/print.php?img='.rawurlencode($this->_live_site.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash).'&amp;name='.rawurlencode($image_title).'&quot; title=&quot;Drucken / Print&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/print.png&quot; /&gt;&lt;/a&gt;';
                    }
                    else
                    {
                        if($this->_params['resize_images'])
                        {
                            $html .= ' &lt;a href=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/print.php?img='.rawurlencode($this->_live_site.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$image).'&amp;name='.rawurlencode($image_title).'&quot; title=&quot;Drucken / Print&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/print.png&quot; /&gt;&lt;/a&gt;';
                        }
                        else
                        {
                            $html .= ' &lt;a href=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/print.php?img='.rawurlencode($this->_live_site.$this->_rootfolder.$this->_images_dir.'/'.$image).'&amp;name='.rawurlencode($image_title).'&quot; title=&quot;Drucken / Print&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/print.png&quot; /&gt;&lt;/a&gt;';
                        }
                    }
                }

                if($this->_params['download'] == 1)
                {
                    if($this->_params['watermark'])
                    {
                        $html .= ' &lt;a href=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/download.php?img='.rawurlencode($this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash).'&quot; title=&quot;Download&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/download.png&quot; /&gt;&lt;/a&gt;';
                    }
                    else
                    {
                        if($this->_params['resize_images'])
                        {
                            $html .= ' &lt;a href=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/download.php?img='.rawurlencode($this->_rootfolder.$this->_images_dir.'/resizedimages/'.$image).'&quot; title=&quot;Download&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/download.png&quot; /&gt;&lt;/a&gt;';
                        }
                        else
                        {
                            $html .= ' &lt;a href=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/download.php?img='.rawurlencode($this->_rootfolder.$this->_images_dir.'/'.$image).'&quot; title=&quot;Download&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;'.$this->_live_site.'/plugins/content/sige/plugin_sige/download.png&quot; /&gt;&lt;/a&gt;';
                        }
                    }
                }

                if(empty($noshow))
                {
                    $html .= '" >';
                }
                else
                {
                    $html .= '"></a></span>';
                }
            }

            if(empty($noshow))
            {
                if(!$this->_params['list'] AND !$this->_params['word'])
                {
                    if($this->_params['thumbs'])
                    {
                        $html .= '<img alt="'.$image_title.'" title="'.$image_title;

                        if($image_description)
                        {
                            $html .= ' - '.$image_description;
                        }

                        if($this->_params['watermark'])
                        {
                            $html .= '" src="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$image_hash.'" />';
                        }
                        else
                        {
                            $html .= '" src="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/thumbs/'.$image.'" />';
                        }
                    }
                    else
                    {
                        $html .= '<img alt="'.$image_title.'" title="'.$image_title;

                        if($image_description)
                        {
                            $html .= ' - '.$image_description;
                        }

                        if($this->_params['watermark'])
                        {
                            $html .= '" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/showthumb.php?img='.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash.'&amp;width='.$this->_params['width'].'&amp;height='.$this->_params['height'].'&amp;quality='.$this->_params['quality'].'&amp;ratio='.$this->_params['ratio'].'&amp;crop='.$this->_params['crop'].'&amp;crop_factor='.$this->_params['crop_factor'].'&amp;thumbdetail='.$this->_params['thumbdetail'].'" />';
                        }
                        else
                        {
                            if($this->_params['resize_images'])
                            {
                                $html .= '" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/showthumb.php?img='.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$image.'&amp;width='.$this->_params['width'].'&amp;height='.$this->_params['height'].'&amp;quality='.$this->_params['quality'].'&amp;ratio='.$this->_params['ratio'].'&amp;crop='.$this->_params['crop'].'&amp;crop_factor='.$this->_params['crop_factor'].'&amp;thumbdetail='.$this->_params['thumbdetail'].'" />';
                            }
                            else
                            {
                                $html .= '" src="'.$this->_live_site.'/plugins/content/sige/plugin_sige/showthumb.php?img='.$this->_rootfolder.$this->_images_dir.'/'.$image.'&amp;width='.$this->_params['width'].'&amp;height='.$this->_params['height'].'&amp;quality='.$this->_params['quality'].'&amp;ratio='.$this->_params['ratio'].'&amp;crop='.$this->_params['crop'].'&amp;crop_factor='.$this->_params['crop_factor'].'&amp;thumbdetail='.$this->_params['thumbdetail'].'" />';
                            }
                        }
                    }
                }
                elseif($this->_params['list'] AND !$this->_params['word'])
                {
                    $html .= $image_title;

                    if($image_description)
                    {
                        $html .= ' - '.$image_description;
                    }
                }
                elseif($this->_params['word'])
                {
                    $html .= $this->_params['word'];
                }

                if($this->_params['css_image'] AND !$this->_params['image_link'])
                {
                    $html .= '<span>';

                    if($this->_params['watermark'])
                    {
                        $html .= '<img src="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/wm/'.$image_hash.'"';
                    }
                    else
                    {
                        if($this->_params['resize_images'])
                        {
                            $html .= '<img src="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/resizedimages/'.$image.'"';
                        }
                        else
                        {
                            $html .= '<img src="'.$this->_live_site.$this->_rootfolder.$this->_images_dir.'/'.$image.'"';
                        }
                    }

                    if($this->_params['css_image_half'] AND !$this->_params['list'])
                    {
                        $imagedata = getimagesize($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$image);
                        $html .= ' width="'.($imagedata[0] / 2).'" height="'.($imagedata[1] / 2).'"';
                    }

                    $html .= ' alt="'.$image_title.'" title="'.$image_title;

                    if($image_description)
                    {
                        $html .= ' - '.$image_description;
                    }

                    $html .= '" /></span>';
                }

                if(!$this->_params['noslim'] OR $this->_params['image_link'] OR $this->_params['css_image'])
                {
                    $html .= '</a>';
                }

                if($this->_params['caption'] AND !$this->_params['list'] AND !$this->_params['word'])
                {
                    if($this->_params['single'] AND !empty($this->_params['scaption']))
                    {
                        $html .= '</span><span class="sige_caption">'.$this->_params['scaption'].'</span></li>';
                    }
                    else
                    {
                        $html .= '</span><span class="sige_caption">'.$image_title.'</span></li>';
                    }
                }

                if($this->_params['list'] AND !$this->_params['word'])
                {
                    $html .= '</li>';
                }
                elseif($this->_params['word'])
                {
                    $html .= '</span>';
                }
                elseif(!$this->_params['caption'])
                {
                    $html .= '</span></li>';
                }
            }
        }

        if($this->_params['column_quantity'] AND empty($noshow))
        {
            if(($a + 1) % $this->_params['column_quantity'] == 0)
            {
                $html .= '<br class="sige_clr"/>';
            }
        }
    }

    private function calculateSize($image, $thumbnail)
    {
        if($this->_params['resize_images'] AND !$thumbnail)
        {
            $new_w = $this->_params['width_image'];

            if($this->_params['ratio_image'])
            {
                $imagedata = getimagesize($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$image);

                $new_h = (int) ($imagedata[1] * ($new_w / $imagedata[0]));
                if($this->_params['height_image'] AND ($new_h > $this->_params['height_image']))
                {
                    $new_h = $this->_params['height_image'];
                    $new_w = (int) ($imagedata[0] * ($new_h / $imagedata[1]));
                }
            }
            else
            {
                $new_h = $this->_params['height_image'];
            }
        }
        else
        {
            $new_w = $this->_params['width'];

            if($this->_params['ratio'])
            {
                $imagedata = getimagesize($this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/'.$image);

                $new_h = (int) ($imagedata[1] * ($new_w / $imagedata[0]));
                if($this->_params['height'] AND ($new_h > $this->_params['height']))
                {
                    $new_h = $this->_params['height'];
                    $new_w = (int) ($imagedata[0] * ($new_h / $imagedata[1]));
                }
            }
            else
            {
                $new_h = $this->_params['height'];
            }
        }

        $ret = array((int) $new_h, (int) $new_w);
        return ($ret);
    }

    private function calculateMaxThumbnailSize($images)
    {
        $max_height = array();
        $max_width = array();

        foreach($images as $image)
        {
            list($max_height[], $max_width[]) = $this->calculateSize($image['filename'], 1);
        }

        rsort($max_height);
        rsort($max_width);

        $this->_thumbnail_max_height = $max_height[0];
        $this->_thumbnail_max_width = $max_width[0];
    }

    private function getFileInfo()
    {
        $file_info = false;

        $captions_lang = $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/captions-'.$this->_params['lang'].'.txt';
        $captions_txtfile = $this->_absolute_path.$this->_rootfolder.$this->_images_dir.'/captions.txt';

        if(file_exists($captions_lang))
        {
            $captions_file = file($captions_lang);
            $count = 0;

            foreach($captions_file as $value)
            {
                $captions_line = explode('|', $value);
                $file_info[$count] = $captions_line;
                $count++;
            }
        }
        elseif(file_exists($captions_txtfile) AND !file_exists($captions_lang))
        {
            $captions_file = file($captions_txtfile);
            $count = 0;

            foreach($captions_file as $value)
            {
                $captions_line = explode('|', $value);
                $file_info[$count] = $captions_line;
                $count++;
            }
        }

        return $file_info;
    }

    private function crop($width_ori, $height_ori)
    {
        if($width_ori > $height_ori)
        {
            $biggest_side = $width_ori;
        }
        else
        {
            $biggest_side = $height_ori;
        }

        $crop_percent = (1 - ($this->_params['crop_factor'] / 100));

        if(!$this->_params['ratio'] AND ($this->_params['width'] == $this->_params['height']))
        {
            $crop_width = $biggest_side * $crop_percent;
            $crop_height = $biggest_side * $crop_percent;
        }
        elseif(!$this->_params['ratio'] AND ($this->_params['width'] != $this->_params['height']))
        {
            if(($width_ori / $this->_params['width']) < ($height_ori / $this->_params['height']))
            {
                $crop_width = $width_ori * $crop_percent;
                $crop_height = ($this->_params['height'] * ($width_ori / $this->_params['width'])) * $crop_percent;
            }
            else
            {
                $crop_width = ($this->_params['width'] * ($height_ori / $this->_params['height'])) * $crop_percent;
                $crop_height = $height_ori * $crop_percent;
            }
        }
        else
        {
            $crop_width = $width_ori * $crop_percent;
            $crop_height = $height_ori * $crop_percent;
        }

        $x_coordinate = ($width_ori - $crop_width) / 2;
        $y_coordinate = ($height_ori - $crop_height) / 2;

        $ret = array($crop_width, $crop_height, $x_coordinate, $y_coordinate);
        return $ret;
    }
}
