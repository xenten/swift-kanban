DROP TABLE IF EXISTS `#__di_langs`;

CREATE TABLE IF NOT EXISTS `#__di_langs` (
  `tag` varchar(2) NOT NULL,
  `language` varchar(30) NOT NULL,
   PRIMARY KEY  (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
INSERT INTO `#__di_langs` (`tag`, `language`) VALUES ('en', 'English');

CREATE TABLE IF NOT EXISTS `#__di_comments` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `did` text,
  `pid` int(11) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `name` text NOT NULL,
  `email` text,
  `website` text NOT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'h',
  `date` datetime NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__di_settings`;

CREATE TABLE IF NOT EXISTS `#__di_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `label` text,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `val` text,
  `desc` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;


INSERT INTO `#__di_settings` (`id`, `name`, `label`, `type`, `val`, `desc`) VALUES
(1, 'apiKey', NULL, 5, NULL, ''),
(2, 'showViewAdd', 'View Comments Link', 0, 'Comments', 'An optional link to display under articles listed on category pages.'),
(3, 'showCount', 'Show comment Count', 1, '1', 'Shows the number of comments underneath an articles summary on category pages.'),
(4, 'sections', 'Categories', 3, '19,22', 'Highlight the categories you wish comments to be enabled in. use ''ctrl'' key to select multiple entries.'),
(5, 'poweredBy', 'Show DiscussIt Link.', 1, '1', 'Shows DiscussIt Link underneath comments.'),
(6, 'langs', 'Default Languages', 4, 'en', '(N.B. Plugin will try to detect page language automatically.)\r\n\r\nLanguage to use if content-language is not automatically detected by the plugin.'),
(7, 'widgetWidth', 'Widget Width', 6, '0', 'Set the width in pixels of the comments widget or set to 0 for AUTO. (Must be a number)'),
(8, 'widgetMargin', 'Widget top Margin.', 6, '0', 'Sets the top margin of the comments widget in pixels.'),
(9, 'disabledText', 'Comments Disabled Message.', 0, '', 'Optional text to display under articles which have comments disabled.'),
(10, 'siteID', NULL, 5, '', ''),
(11, 'widgetID', NULL, 5, '', ''),
(12, 'clientID', NULL, 5, '', ''),
(13, 'clientSecret', NULL, 5, '', ''),
(14, 'refreshToken', NULL, 5, '', ''),
(15, 'anonView', 'Log-in to view comments', 1, '0', 'If set to ''Yes'' users must be logged into your site to view comments.'),
(16, 'anonPost', 'Log-in to post comments', 1, '0', 'If set to ''Yes'' users must be logged into your site to add comments.'),
(17, 'closeComments', 'Close comments after 14 days?', 1, '0', 'Comment posting will be disabled on articles over 14 days old if this option is set to ''Yes''.');
