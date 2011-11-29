CREATE TABLE IF NOT EXISTS `#__annotations` (
  `annotation_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page` varchar(125) DEFAULT NULL,
  `selector` varchar(255) DEFAULT NULL,
  `position` varchar(12) DEFAULT NULL,
  `text` text,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`annotation_id`),
  KEY `page` (`page`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



INSERT INTO `#__plugins` (`id`, `name`, `element`, `folder`, `access`, `ordering`, `published`, `iscore`, `client_id`, `checked_out`, `checked_out_time`, `params`) VALUES
(32, 'System - Annotate', 'annotate', 'system', 0, 0, 1, 0, 0, 0, '0000-00-00 00:00:00', '');


INSERT INTO `#__components` (`id`, `name`, `link`, `menuid`, `parent`, `admin_menu_link`, `admin_menu_alt`, `option`, `ordering`, `admin_menu_img`, `iscore`, `params`, `enabled`) VALUES
(33, 'Annotations', 'option=com_annotations', 0, 0, 'option=com_annotations', 'Annotations', 'com_annotations', 0, 'js/ThemeOffice/component.png', 0, '', 1);

