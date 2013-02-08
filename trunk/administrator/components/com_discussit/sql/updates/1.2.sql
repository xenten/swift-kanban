DROP TABLE IF EXISTS `#__di_langs`;
 
CREATE TABLE `#__di_langs` (
  `tag` varchar(2) NOT NULL,
  `language` varchar(30) NOT NULL,
   PRIMARY KEY  (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
INSERT INTO `#__di_langs` (`tag`, `language`) VALUES ('en', 'English');


--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `#__di_comments` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `name` text NOT NULL,
  `email` text,
  `website` text NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `discussit`
--

CREATE TABLE IF NOT EXISTS `#__di_settings` (
  `apiKey` text,
  `pluginType` tinyint(1) NOT NULL DEFAULT '1',
  `siteID` text,
  `widgetID` text,
  `clientID` text,
  `clientSecret` text,
  `refreshToken` text,
  `showViewAdd` tinyint(1) DEFAULT '0',
  `poweredBy` tinyint(1) NOT NULL DEFAULT '1',
  `moderationType` tinyint(1) NOT NULL DEFAULT '1',
  `sections` text,
  `lang` text,
  `widgetWidth` text,
  `widgetMargin` text,
  `disText` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `discussit`
--