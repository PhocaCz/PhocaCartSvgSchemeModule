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
use Joomla\Registry\Registry;
use Joomla\CMS\Image\Image;

defined('_JEXEC') or die;

class ModPhocaCartSVGSchemeHelper
{
	public static function getAjax() {

		jimport('joomla.application.module.helper');
		if (!JComponentHelper::isEnabled('com_phocacart')) {
			echo '<div class="alert alert-error alert-danger">'.JText::_('Phoca Cart Error') . ' - ' . JText::_('Phoca Cart is not installed on your system').'</div>';
			return;
		}

        JLoader::registerPrefix('Phocacart', JPATH_ADMINISTRATOR . '/components/com_phocacart/libraries/phocacart');
		$lang = Factory::getLanguage();
		$lang->load('com_phocacart');

		$module = JModuleHelper::getModule('phocacart_svg_scheme');

		if (!$module || (isset($module->id) && (int)$module->id < 1)) {
		    // Module is not published
            return "";
        }
        $params = $module->params;
        $paramsM                               = new Registry($module->params);
        $p['tooltip_product_parameters'] = $paramsM->get('tooltip_product_parameters', '');
        if ($p['tooltip_product_parameters'] != '') {
            $p['tooltip_product_parameters'] = array_map('trim', explode(',', $p['tooltip_product_parameters']));
        }

        $p['class_product_parameters'] = $paramsM->get('class_product_parameters', '');
        if ($p['class_product_parameters'] != '') {
            $p['class_product_parameters'] = array_map('trim', explode(',', $p['class_product_parameters']));
        }

        $p['tooltip_display_description'] = $paramsM->get('tooltip_display_description', 0);


        $app = Factory::getApplication();
        $id = $app->input->get('id', 0, 'int');

        $items = [];
        if ($id > 0) {

            $db 	= Factory::getDBO();
            $query 	=
                 ' SELECT a.id, a.title, a.alias, a.special_parameter, a.description,'
                .' c.id as category_id, c.alias as category_alias, c.special_image as category_special_image'
                .' FROM #__phocacart_products AS a'
                .' LEFT JOIN #__phocacart_product_categories AS pc ON pc.product_id = a.id'
                .' LEFT JOIN #__phocacart_categories AS c ON c.id = pc.category_id'
                .' WHERE c.id ='.(int)$id
                .' ORDER BY a.id';

            $db->setQuery($query);
            $items = $db->loadAssocList();


            $bgImage = '';
            $bgImageWidth = 0;
            $bgImageHeight = 0;

            if (isset($items[0]['category_special_image']) && $items[0]['category_special_image'] != '') {
                $imgClean = HTMLHelper::cleanImageURL($items[0]['category_special_image']);
                if ($imgClean->url != '') {
                    $bgImage = $imgClean->url;
                }
                if ($bgImage != '') {
                    $imgProp       = Image::getImageFileProperties(JPATH_BASE . '/' . $bgImage);
                    $bgImageWidth  = $imgProp->width;
                    $bgImageHeight = $imgProp->height;

                    /* Possible Base64
                    $bgImageMime = 'image/jpeg';
                    if (isset($imgProp->mime)) {
                        $bgImageMime = $imgProp->mime;
                    }
                    $imgContent = base64_encode(file_get_contents(JPATH_BASE . '/' . $bgImage));
                    $imgSrc = 'data:'.$bgImageMime.';base64,'.$imgContent;
                    */


                    echo '<div id="pcSvgBoxCategory">';
                    echo '<svg viewbox="0 0 '.$bgImageWidth.' '.$bgImageHeight.'" >';

                    echo '<image href="'.Juri::base().$bgImage.'" xlink:href="'.Juri::base().$bgImage.'"></image>';



                    if (!empty($items)) {

                        foreach ($items as $k => $v) {

                            $url = PhocacartRoute::getItemRoute((int)$v['id'], (int)$v['category_id'], $v['alias'], $v['category_alias']);


                            // Product parameters
                            $suffix = '';
                            $classSuffix = '';

                            if (!empty($p['tooltip_product_parameters']) || !empty($p['class_product_parameters'])){
                                $parameters = PhocacartParameter::getParameterValuesByProductId($v['id'], 1);

                                if (!empty($p['tooltip_product_parameters'])) {
                                    foreach($p['tooltip_product_parameters'] as $k2 => $v2){
                                        if (!empty($parameters[$v2])) {
                                            foreach ($parameters[$v2] as $k3 => $v3) {
                                                $suffix .= ' &bull; ' . $v3['title'];
                                            }
                                        }
                                    }
                                }

                                if (!empty($p['class_product_parameters'])) {
                                    foreach($p['class_product_parameters'] as $k2 => $v2){
                                        if (!empty($parameters[$v2])) {
                                            foreach ($parameters[$v2] as $k3 => $v3) {
                                                $classSuffix .= ' ' . $v3['alias'];
                                            }
                                        }
                                    }
                                }
                            }


                            if ((int)$p['tooltip_display_description'] > 0 && isset($v['description']) && $v['description'] != '') {
                                $v['description'] = strip_tags($v['description'], '<sup><b><i><strong>');
                                $suffix           .= ' &bull; ' . $v['description'];
                            }

                            echo '<a href="'.$url.'">';
                            echo '<polygon class="pcSVGSchemeProductItem'.$classSuffix.'" data-id="'.$v['id'].'" points="'.$v['special_parameter'].'" data-tippy-content="'.$v['title'].$suffix.'"></polygon>';
                            echo '</a>';
                        }

                    }

                    echo '</svg>';
                    echo '</div>';

                }
            } else {

                // There is no items in this category but the category is displayed, so try to display only image if exists

                $db 	= Factory::getDBO();
                $query 	=
                     ' SELECT c.id as category_id, c.alias as category_alias, c.special_image as category_special_image'
                    .' FROM #__phocacart_categories AS c'
                    .' WHERE c.id ='.(int)$id
                    .' ORDER BY c.id'
                    .' LIMIT 1';

                $db->setQuery($query);
                $category = $db->loadAssoc();

                if (isset($category['category_special_image']) && $category['category_special_image'] != '') {
                    $imgClean = HTMLHelper::cleanImageURL($category['category_special_image']);
                    if ($imgClean->url != '') {
                        $bgImage = $imgClean->url;
                    }
                    if ($bgImage != '') {
                        $imgProp       = Image::getImageFileProperties(JPATH_BASE . '/' . $bgImage);
                        $bgImageWidth  = $imgProp->width;
                        $bgImageHeight = $imgProp->height;

                        /* Possible Base64
                        $bgImageMime = 'image/jpeg';
                        if (isset($imgProp->mime)) {
                            $bgImageMime = $imgProp->mime;
                        }
                        $imgContent = base64_encode(file_get_contents(JPATH_BASE . '/' . $bgImage));
                        $imgSrc = 'data:'.$bgImageMime.';base64,'.$imgContent;
                        */


                        echo '<div id="pcSvgBoxCategory">';
                        echo '<svg viewbox="0 0 ' . $bgImageWidth . ' ' . $bgImageHeight . '" >';

                        echo '<image href="' . Juri::base() . $bgImage . '" xlink:href="' . Juri::base() . $bgImage . '"></image>';

                        echo '</svg>';
                        echo '</div>';

                    }
                }

            }
        }
	}
}
