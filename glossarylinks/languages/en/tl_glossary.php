<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 * 
 * PHP version 5
 * @copyright	Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		GlossaryLinks
 * @license		LGPL 
 * @filesource
 */

$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_legend'] = 'Glossarylinks';
$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks'] = array('Enable glossary links' , 'Tick this checkbox to enable the glossary links in this glossary.');
$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_template'] = array('Template' , 'Please select the template to use for formatting.');
$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_pages'] = array('Page selection' , 'Please select the pages on which the glossary links shall appear. You can select as many as you wish.');
$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_disallowintags'] = array('Do not replace word within these tags' , 'Please provide a list of tags, in which no replacing shall be done. i.e.: &quot;&lt;a&gt;,&lt;script&gt;&lt;span class="active first"&gt;&quot;');
$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_allowtagsindesc'] = array('Do not remove these tags from the description' , 'By default all tags will be stripped from the description to generate valid HTML. Here you can specify a list of tags that shall not be stripped from the description. i.e.: &quot;&lt;em&gt;,&lt;strong&gt;&quot;&lt;span&gt;&lt;br&gt;&quot;');

?>