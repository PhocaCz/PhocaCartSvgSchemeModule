<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Image\Image;

defined('_JEXEC') or die;// no direct access
$app = Factory::getApplication();

if (!JComponentHelper::isEnabled('com_phocacart', true)) {

	$app->enqueueMessage(JText::_('Phoca Cart Error'), JText::_('Phoca Cart is not installed on your system'), 'error');
	return;
}

$document	= Factory::getDocument();

JLoader::registerPrefix('Phocacart', JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart');

$lang = Factory::getLanguage();
//$lang->load('com_phocacart.sys');
$lang->load('com_phocacart');

$media = new PhocacartRenderMedia();
$media->loadBase();
$media->loadBootstrap();
$media->loadSpec();

$d['s'] = PhocacartRenderStyle::getStyles();
//JHtml::_('bootstrap.tooltip');
$wa = $document->getWebAssetManager();
$wa->registerAndUseStyle('mod_phocacart_svg_scheme', 'media/mod_phocacart_svg_scheme/css/main.css');
$wa->registerAndUseScript('mod_phocacart_svg_scheme.popper', 'media/mod_phocacart_svg_scheme/js/popper.min.js');
$wa->registerAndUseScript('mod_phocacart_svg_scheme.tippy', 'media/mod_phocacart_svg_scheme/js/tippy-bundle.umd.min.js');

$wa->registerAndUseScript('mod_phocacart_svg_scheme', 'media/mod_phocacart_svg_scheme/js/phocacartsvgscheme.js');

$moduleclass_sfx 			= htmlspecialchars((string)$params->get('moduleclass_sfx', ''), ENT_COMPAT, 'UTF-8');

$pCom						= PhocacartUtils::getComponentParameters();
//$pc['display_webp_images']	= $pCom->get( 'display_webp_images', 0 );

$p = [];
$p['main_background_image']			            = $params->get( 'main_background_image', '' );
$p['catid_multiple']				            = $params->get( 'catid_multiple', '' );
$p['tooltip_prefix_count_products_text']		= $params->get( 'tooltip_prefix_count_products_text', '' );

$p['display_view'] 			= $params->get( 'display_view', '');
$p['display_option'] 		= $params->get( 'display_option', '');
$p['display_id'] 			= $params->get( 'display_id', '');
$view 						= $app->input->get('view', '');
$option 					= $app->input->get('option', '');
$idCom						= $app->input->get('id', '');
$optionA 	= array_map('trim', explode(',', $p['display_option']));// Remove spaces
$viewA 		= array_map('trim', explode(',', $p['display_view']));
$idA 		= array_map('trim', explode(',', $p['display_id']));
$optionA	= array_filter($optionA);// Remove empty values from array
$viewA 		= array_filter($viewA);
$idA 		= array_filter($idA);



if (empty($optionA) && empty($viewA) && empty($idA)) {
	// OK - all parameters are not set
} else if (!empty($optionA) && in_array($option, $optionA) && empty($viewA) && empty($idA)) {
	// OK - only option is set and it meets the criteria
} else if (!empty($optionA) && in_array($option, $optionA) && !empty($viewA) && in_array($view, $viewA) && empty($idA)) {
	// OK - option and view is set and it meets the criteria
} else if (!empty($optionA) && in_array($option, $optionA) && !empty($viewA) && in_array($view, $viewA) && !empty($idA) && in_array($idCom, $idA) ) {
	// OK - option and view and ID is set and it meets the criteria
} else {
	return '';
}

$categories = [];
if (!empty($p['catid_multiple'])) {

	$catString = implode(',', $p['catid_multiple']);
	$db 	= Factory::getDBO();
		$query 	=
			 ' SELECT c.id, c.title, c.alias, c.special_parameter, count_products'
			.' FROM #__phocacart_categories AS c'
			.' WHERE c.id IN ('.$catString.')'
			//.' ORDER BY FIELD(c.id, '.$catString.')';
			.' ORDER BY c.id';

		$db->setQuery($query);
		$categories = $db->loadAssocList();

}

$baseUrl = Juri::base();
$document->addScriptOptions('phVarsModPhocacartSVGScheme', array('baseUrl' => $baseUrl ));

$bgImage = '';
$bgImageWidth = 0;
$bgImageHeight = 0;

$imgClean = HTMLHelper::cleanImageURL($p['main_background_image']);
if ($imgClean->url != '') {
   $bgImage =  $imgClean->url;
}
if ($bgImage != '') {
	$imgProp = Image::getImageFileProperties(JPATH_BASE . '/' . $bgImage);
	$bgImageWidth = $imgProp->width;
	$bgImageHeight = $imgProp->height;

	/* Possible Base64
	$bgImageMime = 'image/jpeg';
	if (isset($imgProp->mime)) {
		$bgImageMime = $imgProp->mime;
	}
	$imgContent = base64_encode(file_get_contents(JPATH_BASE . '/' . $bgImage));
   	$imgSrc = 'data:'.$bgImageMime.';base64,'.$imgContent;
	*/


}

require(JModuleHelper::getLayoutPath('mod_phocacart_svg_scheme'));
?>
