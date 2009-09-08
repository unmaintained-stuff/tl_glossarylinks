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
 * @copyright	CyberSpectrum 2009
 * @author		Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		GlossaryLinks
 * @license		LGPL 
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_glossary_term']['palettes']['default'] = str_replace(',author', ',author,glossarytype', $GLOBALS['TL_DCA']['tl_glossary_term']['palettes']['default']);

$GLOBALS['TL_DCA']['tl_glossary_term']['fields']['glossarytype']	= array
(
		'label'			=> $GLOBALS['TL_LANG']['tl_glossary_term']['glossarytype'],
		'exclude'		=> true,
		'inputType'		=> 'select',
		'options'		=> array('dfn' => $GLOBALS['TL_LANG']['glossarylinks']['dfn'], 'abbr' => $GLOBALS['TL_LANG']['glossarylinks']['abbr']),
		'eval'			=> array('submitOnChange' => true, 'tl_class'=>'w50')
);

?>