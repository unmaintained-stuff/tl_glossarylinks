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

$GLOBALS['TL_DCA']['tl_glossary']['palettes']['default'] .= ';{glossarylinks_legend:hide},glossarylinks';

$GLOBALS['TL_DCA']['tl_glossary']['fields']['glossarylinks']	= array
(
		'label'			=> $GLOBALS['TL_LANG']['tl_glossary']['glossarylinks'],
		'exclude'		=> true,
		'inputType'		=> 'checkbox',
		'eval'			=> array('submitOnChange' => true, 'tl_class'=>'w50')
);

// Template selection
$GLOBALS['TL_DCA']['tl_glossary']['fields']['glossarylinks_template'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_template'], 
			'default'                 => 'glossarylinks_default',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => $this->getTemplateGroup('glossarylinks_'),
			'eval'                    => array('tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_glossary']['fields']['glossarylinks_pages']	= array
(
		'label'			=> $GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_pages'],
		'explanation'   => 'glossarylinks_pages',
		'exclude'		=> true,
		'inputType'		=> 'pageTree',
		'eval'			=> array('fieldType'=>'checkbox', 'helpwizard'=>true, 'tl_class'=>'clr'),
		'save_callback'	=> array(array('tl_glossary_links', 'save_pages')),
		'load_callback'	=> array(array('tl_glossary_links', 'load_pages')),
);

$GLOBALS['TL_DCA']['tl_glossary']['fields']['glossarylinks_disallowintags']	= array
(
		'label'			=> $GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_disallowintags'],
		'exclude'		=> true,
		'inputType'		=> 'textarea',
		'eval'			=> array('tl_class'=>'clr', 'decodeEntities'=>true, 'preserveTags'=>true, 'class'=>'monospace'),
		'save_callback'	=> array(array('tl_glossary_links', 'save_tags')),
		'load_callback'	=> array(array('tl_glossary_links', 'load_tags')),
);

$GLOBALS['TL_DCA']['tl_glossary']['fields']['glossarylinks_allowtagsindesc']	= array
(
		'label'			=> $GLOBALS['TL_LANG']['tl_glossary']['glossarylinks_allowtagsindesc'],
		'exclude'		=> true,
		'inputType'		=> 'textarea',
		'eval'			=> array('tl_class'=>'clr', 'decodeEntities'=>true, 'preserveTags'=>true, 'class'=>'monospace'),
		'save_callback'	=> array(array('tl_glossary_links', 'save_tags')),
		'load_callback'	=> array(array('tl_glossary_links', 'load_tags')),
);


$GLOBALS['TL_DCA']['tl_glossary']['config']['onload_callback'][]=array('tl_glossary_links', 'modifyPalette');

// manipulate dca dynamically.
class tl_glossary_links extends Backend
{
	public function modifyPalette(DataContainer $dc) {
		if (!$dc->id)
			return;
		$obj = $this->Database->prepare("SELECT * FROM tl_glossary WHERE id=?")
									->limit(1)
									->execute($dc->id);
		if ($obj->numRows)
		{
			if($obj->glossarylinks)
			{
				$GLOBALS['TL_DCA']['tl_glossary']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_glossary']['palettes']['default'] . ',glossarylinks_template,glossarylinks_pages,glossarylinks_disallowintags,glossarylinks_allowtagsindesc';
			}
		}
	}
	/**
	 * Callback from database.
	 * @param string
	 * @param object
	 * @return string
	 */
	public function save_pages($varValue, DataContainer $dc){		
		return implode(',', deserialize($varValue));
	}

	/**
	 * Callback from database.
	 * @param string
	 * @param object
	 * @return string
	 */
	public function load_pages($varValue, DataContainer $dc){
		return explode(',', $varValue);
	}
	
	/**
	 * Callback from database.
	 * @param string
	 * @param object
	 * @return string
	 */
	public function save_tags($varValue, DataContainer $dc){
		return $varValue;
		return str_replace(' ', '', $varValue);
	}
	/**
	 * Callback from database.
	 * @param string
	 * @param object
	 * @return string
	 */
	public function load_tags($varValue, DataContainer $dc){
		return $varValue;
	}
}

?>