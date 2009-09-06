CREATE TABLE `tl_glossary` (
  `glossarylinks` char(1) NOT NULL default '',
  `glossarylinks_pages` blob NULL,
  `glossarylinks_template` varchar(64) NOT NULL default ''
  `glossarylinks_disallowintags` text NULL,
  `glossarylinks_allowtagsindesc` text NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
