<div class="blog-social-buttons">

    <div class="blog-google-plus">
        <g:plusone size="medium" annotation="none" href="<?php echo "http://www.swift-kanban.com/".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"></g:plusone>
    </div>
    <div class="blog-twitter-tweet">
        <a href="https://twitter.com/share" class="twitter-share-button"
           data-url="<?php echo "http://www.swift-kanban.com/".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
           data-via="swiftkanban" data-count="none" data-text="<?php echo $this->escape($this->item->title); ?>" data-hashtags="kanban">Tweet</a>

    </div>
    <div class="blog-pinterest-pin">
        <a href='<?php echo "http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.swift-kanban.com"
            .urlencode(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))).
            "&media=http%3A%2F%2Fwww.swift-kanban.com%2Fimages%2Fdevelopment-board.jpg&description=".$this->escape($this->item->title) ?>'
           class="pin-it-button" count-layout="none"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
    </div>
    <div class="blog-facebook-like">
        <div class="fb-like" data-href="<?php echo "http://www.swift-kanban.com/".(JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid))); ?>"
             data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
    </div>
</div>