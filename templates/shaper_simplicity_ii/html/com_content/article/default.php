<?php
/**
 * @version		$Id: default.php 20817 2011-02-21 21:48:16Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers');

JLoader::register('fieldattach', 'components/com_fieldsattach/helpers/fieldattach.php');
// Create shortcuts to some parameters.
$params		= $this->item->params;
$canEdit	= $this->item->params->get('access-edit');
$user		= JFactory::getUser();
?>
<div class="item-page<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
<?php endif; ?>
<?php if ($params->get('show_title')) : ?>
	<h2>
	<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
		<a href="<?php echo $this->item->readmore_link; ?>">
		<?php echo $this->escape($this->item->title); ?></a>
	<?php else : ?>
		<?php echo $this->escape($this->item->title); ?>
	<?php endif; ?>
	</h2>
<?php endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php 
if ($params->get('show_create_date') 
|| ($params->get('show_publish_date'))
|| ($params->get('show_author') && !empty($this->item->author ))
|| ($params->get('show_category')) 
|| ($params->get('show_parent_category'))	
|| ($params->get('show_print_icon')) 
|| ($params->get('show_email_icon'))
|| ($this->item->catid == 10)
|| ($params->get('show_hits'))
|| $canEdit): 
?>


    <div style="display: none;">
       <?php
        $socialimagelink_js = fieldattach::getValue( $this->item->id, 2, false) ;
        $socialimagelink = urlencode($socialimagelink_js) ;
        $socialdesc = urlencode(fieldattach::getValue( $this->item->id, 1, false)) ;
        $socialtwittertag = (fieldattach::getValue( $this->item->id, 3, false)) ;
        ?>
        <script type="text/javascript">
            var social_share_image =  "<?php  if ($socialimagelink_js!='' && $socialimagelink_js !=null) : echo $socialimagelink_js; endif; ?>";
        </script>

    </div>
<div class="article-tools clearfix">
	<dl class="article-info">
	<!-- <dt class="article-info-term"><?php  echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></dt>-->
		<?php if ($params->get('show_create_date')) : ?>
				<dd class="create">
				<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHTML::_('date',$this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
				</dd>
		<?php endif; ?>
		<?php if ($params->get('show_publish_date')) : ?>
				<dd class="published">
				<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE', JHTML::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
				</dd>
		<?php endif; ?>	

		<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<dd class="createdby"> 
				<?php $author =  $this->item->author; ?>
				<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>

					<?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
						<?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' , 
						 JHTML::_('link',JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid),$author)); ?>

					<?php else :?>
						<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
					<?php endif; ?>
			</dd>
		<?php endif; ?>	
	
		<?php if (($params->get('show_category')) || ($params->get('show_parent_category'))) : ?>
		
		<?php if ($params->get('show_parent_category')) : ?>
				<dd class="parent-category-name">
					<?php $title = $this->escape($this->item->parent_title);
						$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>'; ?>
					<?php if ($params->get('link_parent_category') AND $this->item->parent_slug) : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
						<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
					<?php endif; ?>
				</dd>
		<?php endif; ?>
		
		<?php if ($params->get('show_category')) : ?>
				<dd class="category-name">
					<?php $title = $this->escape($this->item->category_title);
						$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
					<?php if ($params->get('link_category') AND $this->item->catslug) : ?>
						<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
						<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
					<?php endif; ?>
				</dd>
		<?php endif; ?>
		
		<?php endif; ?>
		
		<?php if ($params->get('show_hits')) : ?>
				<dd class="hits">
				<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
				</dd>
		<?php endif; ?>			
	</dl>
    <div id='v-social-bar'>
        <div id='mini_social_bar'>

            <div id='gplus_img'></div>
            <div id='linkedin_img'></div>
            <div id='twitter_img'></div>
            <div id='facebook_img'></div>
        </div>
        <div id='macro_social_bar'>
            <div class="blog-v-social-buttons">

                <div class="blog-v-google-plus v-social-button">
                    <g:plusone size="medium"></g:plusone>
                </div>
                <div class="blog-v-linkedin-share v-social-button">
                    <script type="IN/Share" data-counter="right"></script>
                </div>
                <div class="blog-v-twitter-tweet v-social-button">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-via="swiftkanban" data-hashtags="kanban">&nbsp;</a>

                </div>
                <div class="blog-v-facebook-like  v-social-button">
                    <div class="fb-like" data-href="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
                         data-send="false" data-layout="button_count" data-width="60" data-show-faces="false"></div>
                </div>
                <div class="blog-v-facebook-share v-social-button">
                    <a rel="nofollow"
                       href="http://www.facebook.com/share.php?u=<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
                       class="fb_share_button" onclick="return fbs_click('<?php echo $this->escape($this->item->title); ?>',
                            '<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>')"
                       target="_blank" style="text-decoration:none;"><img src="/images/icons/FB-share-icon.png" title="Share this post on Facebook"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-social-buttons" style='display:block;'>
        <div class="blog-google-plus">
            <g:plusone size="medium" href="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"></g:plusone>
        </div>
        <div class="blog-linkedin-share">
            <script type="IN/Share" data-url="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>" data-counter="right"></script>
        </div>
        <div class="blog-twitter-tweet">
            <a href="https://twitter.com/share" class="twitter-share-button"
               data-url="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
               data-via="swiftkanban" data-text="<?php echo $this->escape($this->item->title); ?>" data-hashtags="kanban,<?php echo $socialtwittertag ?>">&nbsp;</a>

        </div>
        <div class="blog-facebook-like">
            <div class="fb-like" data-href="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
                 data-send="false" data-layout="button_count" data-width="60" data-show-faces="false"></div>
        </div>
        <div class="blog-facebook-share">
            <a rel="nofollow"
               href="http://www.facebook.com/share.php?u=<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
               class="fb_share_button" onclick="return fbs_click('<?php echo $this->escape($this->item->title); ?>',
                '<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>')"
               target="_blank" style="text-decoration:none;"><img src="/images/icons/FB-share-icon.png" title="Share this post on Facebook"/>
            </a>
        </div>
        <div class="blog-pinterest-pin">
            <a href='<?php echo "http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.swift-kanban.com"
                .urlencode(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))).
                "&media=".$socialimagelink."&description=".$this->escape($this->item->title) ?>'
               class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
        </div>

    </div>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
    <div id="fb-root"></div>

    <script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
	<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
		<ul class="buttonheading">
			<?php if ($params->get('show_print_icon')) : ?>
			<li class="print-icon">
				<?php echo JHtml::_('icon.print_popup', $this->item, $params); ?>
			</li>
			<?php endif; ?>
			<?php if ($params->get('show_email_icon')) : ?>
			<li class="email-icon">
				<?php echo JHtml::_('icon.email', $this->item, $params); ?>
			</li>
			<?php endif; ?>
			<?php if ($canEdit) : ?>
			<li class="edit-icon">
				<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
			</li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>
</div>
<?php endif; ?>

<?php  if (!$params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>

<?php if (isset ($this->item->toc)) : ?>
	<?php echo $this->item->toc; ?>
<?php endif; ?>

<?php if ($params->get('show_modify_date')) : ?>
		<dd class="modifydate">
		<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHTML::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
		</dd>
<?php endif; ?>

<?php if ($params->get('access-view')):?>
	<?php echo $this->item->text; ?>
	
	<?php //optional teaser intro text for guests ?>
<?php elseif ($params->get('show_noauth') == true AND  $user->get('guest') ) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JURI($link1);?>
		<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs);  ?> 
		<?php 
		if ($attribs->alternative_readmore == null) :
			echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		elseif ($readmore = $this->item->alternative_readmore) :
			echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
			    echo JHTML::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif;
		elseif ($params->get('show_readmore_title', 0) == 0) :
			echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');	
		else :
			echo JText::_('COM_CONTENT_READ_MORE');
			echo JHTML::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif; ?></a>
		</p>
	<?php endif; ?>
<?php endif; ?>
<?php
    if ($params->get('show_create_date')
        || ($params->get('show_publish_date'))
        || ($params->get('show_author') && !empty($this->item->author ))
        || ($params->get('show_category'))
        || ($params->get('show_parent_category'))
        || ($params->get('show_print_icon'))
        || ($params->get('show_email_icon'))
        || ($this->item->catid == 10)
        || ($params->get('show_hits'))
        || $canEdit):
        ?>
<div class="blog-social-buttons social-footer" style='display:block;' >
    <div class="blog-google-plus">
        <g:plusone size="medium" href="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"></g:plusone>
    </div>
    <div class="blog-linkedin-share">
        <script type="IN/Share" data-url="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>" data-counter="right"></script>
    </div>
    <div class="blog-twitter-tweet">
        <a href="https://twitter.com/share" class="twitter-share-button"
           data-url="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
           data-via="swiftkanban" data-text="<?php echo $this->escape($this->item->title); ?>" data-hashtags="kanban,<?php echo $socialtwittertag ?>">&nbsp;</a>

    </div>
    <div class="blog-facebook-like">
        <div class="fb-like" data-href="<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
             data-send="false" data-layout="button_count" data-width="60" data-show-faces="false"></div>
    </div>
    <div class="blog-facebook-share">
        <a rel="nofollow"
           href="http://www.facebook.com/share.php?u=<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
           class="fb_share_button" onclick="return fbs_click('<?php echo $this->escape($this->item->title); ?>',
                '<?php echo "https://www.swift-kanban.com".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>')"
           target="_blank" style="text-decoration:none;"><img src="/images/icons/FB-share-icon.png" title="Share this post on Facebook"/>
        </a>
    </div>
    <div class="blog-pinterest-pin">
        <a href='<?php echo "http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.swift-kanban.com"
            .urlencode(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))).
            "&media=".$socialimagelink."&description=".$this->escape($this->item->title) ?>'
           class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
    </div>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    <script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<br/>
</div>
        <?php endif; ?>
<script>
    <!--  Google +1 Code starts here -->
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
    <!--  Google +1 Code ends here -->

    <!--  Facebook Like Code starts here -->
    (function(d, s, id)
    {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    <!--  Facebook Like Code ends here -->
    <!--  Facebook Share Code starts here -->
    function fbs_click(sharetitle, shareurl)
    {
        u=shareurl;
        t=sharetitle;
        window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer',
                'toolbar=0,status=0,width=660,height=360,resizable=0,scrollbars=0');
        return false;
    }
    if(social_share_image!='')
    {
        $('#social_image')[0].content = social_share_image;
    }
    <!--  Facebook Share Code ends here -->
    var mini_bar = $('#mini_social_bar');
    var macro_bar = $('#macro_social_bar');
    var v_bar = $('#v-social-bar');

    $(window).load($(function()
    {
        var idealtop = 395;
        var max_top = 20;
        var top_buffer = idealtop - max_top;
        var elem = $("#v-social-bar");
        var top_value = 0;
        var scrollHandler = function()
        {
            esc_bar();
            var scrollTop = $(window).scrollTop();
            if (scrollTop>1 && scrollTop<top_buffer) {
                top_value = idealtop-scrollTop;
                elem.css({position:"fixed",top:(top_value+"px")});
            } else if(scrollTop>top_buffer)
            {
                elem.css({position:"fixed",top:(max_top+"px")});
            }
            else if(scrollTop==0)
            {
                elem.css({position:"fixed",top:(idealtop+"px")});
            }
        }
        $(window).scroll(scrollHandler);scrollHandler();

    }));

    mini_bar.mouseenter(function() {
        mini_bar.css({display:"none"});
        macro_bar.css({display:"block"});
        v_bar.css({width:"95px",height:"120px"});
    });

    var esc_bar = function(){
        mini_bar.css({display:"block"});
        macro_bar.css({display:"none"});
        v_bar.css({width:"20px",height:"95px"});
    };
    $("body").click(function() {
        esc_bar();
    });

    $(document).keyup(function(e) {
        if (e.keyCode == 27) { esc_bar();}   // esc
    });
</script>

<?php echo $this->item->event->afterDisplayContent; ?>
</div>