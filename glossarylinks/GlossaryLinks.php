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

/**
 * Class GlossaryLinks - scrape the template content for glossar keywords and return the result.
 *
 * @copyright	2009 CyberSpectrum
 * @author		Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @package		Controller
 */
class GlossaryLinks extends Frontend
{

	// Tags, in which no replacement will be done.
	private $arrProtectedTags = array('html', 'title', 'meta', 'style', 'script', 'textarea', 'a', 'label', 'dfn class="glossarydescription"', 'abbr class="glossarydescription"');
	private $cachedProtectedPlain = NULL;
	private $cachedProtectedDOMs = NULL;
	private $cachedAllow = NULL;
	
	protected function buildProtectedSelectors()
	{
		global $objPage;
		$cachedDisallow=array();
		$cachedProtectedPlain=array();
		$cachedProtectedPlain['global']=array();
		$cachedAllow=array();
		foreach($this->arrProtectedTags as $tagEntry)
		{
			// if we do not have a space in the selector, there is no attribute specified, 
			// we can savely continue then
			if(strpos($tagEntry, ' ') === false)
			{
				$cachedProtectedPlain['global'][]=$tagEntry;
				continue;
			}
			$dummy='<test><'.$tagEntry.'/></test>';
			$cachedDisallow['global'][] = str_get_html($dummy);
		}
		$obj = $this->Database->prepare("SELECT g.id, g.glossarylinks_disallowintags AS disallow, g.glossarylinks_allowtagsindesc AS allow FROM tl_glossary AS g WHERE g.glossarylinks=1 AND FIND_IN_SET(?, g.glossarylinks_pages) ORDER BY g.glossarylinks_template")
									->execute($objPage->id);
		if ($obj->numRows)
		{
			if($obj->disallow != '')
			{
				$notAllowedTags = explode(',', preg_replace('([<|>])', '', $obj->disallow));
				$cachedProtectedPlain[$obj->id]=array_merge($cachedProtectedPlain['global'], $notAllowedTags);
				$cachedDisallow[$obj->id]=$cachedDisallow['global'];
				foreach($notAllowedTags as $tagEntry)
				{
					// if we do not have a space in the selector, there is no attribute specified, 
					// we can savely continue then
					if(strpos($tagEntry, ' ') === false)
					{
						$cachedProtectedPlain[$obj->id][]=$tagEntry;
						continue;
					}
					$dummy='<test><'.$tagEntry.'/></test>';
					$cachedDisallow[$obj->id][] = str_get_html($dummy);
				}
			} else {
				$cachedProtectedPlain[$obj->id]=$cachedProtectedPlain['global'];
				$cachedDisallow[$obj->id]=$cachedDisallow['global'];
			}
			if($obj->allow != '')
			{
				$cachedAllow[$obj->id]=implode(explode(',', $obj->allow));
			}
		}
		$this->cachedProtectedDOMs=$cachedDisallow;
		$this->cachedProtectedPlain=$cachedProtectedPlain;
		$this->cachedAllow=$cachedAllow;
	}
	
	protected function isForbiddenTag($node, $pid)
	{
		$parentTag=$node->parent()->tag;
		// short exit way, the tag is disabled in total.
		if(in_array($parentTag, $this->cachedProtectedPlain[$pid]))
			return true;
		$parent=$node->parent();
		// now we have to check for tags with given selectors.
		foreach($this->cachedProtectedDOMs[$pid] as $tagEntry)
		{
			$forbidden=true;
			if($tagEntry->root->firstChild()->firstChild()->tag != $parentTag)
				continue;
			foreach(($tagEntry->root->firstChild()->firstChild()->getAllAttributes()) as $key=>$attrib)
			{
				// attribute not specified? continue with next tag.
				if(!$parent->hasAttribute($key))
				{
					$forbidden=false;
					break;
				}
				if($parent->getAttribute($key) != $attrib)
				{
					$forbidden=false;
					break;
				}
			}
			if($forbidden)
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Called from parseFrontendTemplate HOOK
	 * @param string
	 * @param object
	 * @return string
	 */
	public function HookFETemplate($strBuffer, $strTemplate)
	{
		// Include SimpleHtmlDom
		if (!function_exists('file_get_html'))
			require_once(TL_ROOT . '/system/modules/glossarylinks/simple_html_dom.php');
		$this->buildProtectedSelectors();
		global $objPage;
		$obj = $this->Database->prepare("SELECT gt.*, g.glossarylinks_template FROM tl_glossary AS g RIGHT JOIN tl_glossary_term AS gt ON (gt.pid=g.id) WHERE g.glossarylinks=1 AND FIND_IN_SET(?, g.glossarylinks_pages) ORDER BY g.glossarylinks_template")
									->execute($objPage->id);
		if ($obj->numRows)
		{
			$lasttpl='glossarylinks_default';
			$objTemplate=new FrontendTemplate($lasttpl);
			while($obj->next())
			{
				$html = str_get_html($strBuffer);
				foreach($html->find('text') as $text ) {
					if ($this->isForbiddenTag($text, $obj->pid))
					{
						$text->parent()->nextSibling();
						continue;
					} else {
						if(strpos($text->innertext, $obj->term)===false)
						{
							continue;
						}
						if($lasttpl != $obj->glossarylinks_template)
						{
							$lasttpl=$obj->glossarylinks_template;
							$objTemplate=new FrontendTemplate($lasttpl);
						}
						$objTemplate->id = $obj->id;
						$objTemplate->author = $obj->author;
						$objTemplate->term = $obj->term;
						$objTemplate->definition = trim(strip_tags($obj->definition, $this->cachedAllow[$obj->pid]));
						$objTemplate->addImage = $obj->addImage;
						$objTemplate->singleSRC = $obj->singleSRC;
						$objTemplate->size = $obj->size;
						$objTemplate->alt = $obj->alt;
						$objTemplate->caption = $obj->caption;
						$objTemplate->floating = $obj->floating;
						$objTemplate->imagemargin = $obj->imagemargin;
						$objTemplate->fullsize = $obj->fullsize;
						$objTemplate->addEnclosure = $obj->addEnclosure;
						$objTemplate->enclosure = $obj->enclosure;
						$objTemplate->glossarytype = $obj->glossarytype;
						$objTemplate->cssId = 'glossary_' . $obj->pid;
						$text->innertext = preg_replace ( "/\b(".trim($obj->term).")\b/uis", $objTemplate->parse(), $text->innertext); 
					}
				}
				$strBuffer = $html->save();
				$html->clear();
				unset($html);
			}
			unset($obj);
		}
		return $strBuffer;
	}
}

?>