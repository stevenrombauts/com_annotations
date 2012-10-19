CREATE TABLE IF NOT EXISTS `#__annotations` (
  `annotation_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `identifier` varchar(125) NOT NULL,
  `package` varchar(125) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `position` varchar(12) NULL,
  `text` text,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`annotation_id`),
  KEY `identifier` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- Installation into Nooku Server:

INSERT INTO `#__extensions_components` (`extensions_component_id`, `title`, `name`, `params`, `enabled`)
VALUES
	('', 'Annotations', 'com_annotations', '', 1);

SET @component_id = (SELECT `extensions_component_id` FROM `#__extensions_components` WHERE `name` = 'com_annotations');

INSERT INTO `#__pages` (`pages_page_id`, `pages_menu_id`, `users_group_id`, `title`, `slug`, `link_url`, `link_id`, `type`, `published`, `hidden`, `home`, `extensions_component_id`, `created_by`, `created_on`, `modified_by`, `modified_on`, `locked_by`, `locked_on`, `access`, `params`)
VALUES
	('', 2, 0, 'Annotations', 'annotations', 'index.php?option=com_annotations&view=annotations', NULL, 'component', 1, 0, 0, @component_id, 1, NOW(), NULL, NULL, NULL, NULL, 0, NULL);
	
SET @menu_id = (SELECT `pages_page_id` FROM `#__pages` WHERE `extensions_component_id` = @component_id);
	
INSERT INTO `#__pages_closures` (`ancestor_id`, `descendant_id`, `level`)
VALUES
	(4, @menu_id, 1), (@menu_id, @menu_id, 0);