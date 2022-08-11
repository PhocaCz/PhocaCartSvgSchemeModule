<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

echo '<div class="ph-svg-scheme-module-box'.$moduleclass_sfx .'">';

echo '<div id="pcSvgBox">';
echo '<svg viewbox="0 0 '.$bgImageWidth.' '.$bgImageHeight.'" >';

if ($bgImage != '') {
    echo '<image href="'.Juri::base().$bgImage.'" xlink:href="'.Juri::base().$bgImage.'"></image>';
    //echo '<image xlink:href="'.$imgSrc.'"></image>';
}



$categoryListO = '';
if (!empty($categories)) {

    $categoryListO .= '<div class="pc-svg-scheme-category-list">';
    foreach ($categories as $k => $v) {


        $categoryListO .= '<button id="pcSVGSchemeCategoryListItem'.$v['id'].'" class="pcSVGSchemeCategoryListItem" data-id="'.$v['id'].'">';
        $categoryListO .= $v['title'];
        $categoryListO .= '</button>';

        $prefix = '';
        if ($p['tooltip_prefix_count_products_text'] != '') {
            $prefix = $p['tooltip_prefix_count_products_text'] . ' ';
        }
        $suffix = ' &bullet; ' . $prefix . $v['count_products']. ' ' . Text::plural('MOD_PHOCACART_SVG_SCHEME_N_PRODUCTS', $v['count_products']) . '';
        echo '<polygon class="pcSVGSchemeCategoryItem" data-id="'.$v['id'].'" points="'.$v['special_parameter'].'" data-tippy-content="'.$v['title'].$suffix.'"></polygon>';
    }


    $categoryListO .= '</div>';

    require ModuleHelper::getLayoutPath('mod_phocacart_svg_scheme', 'default_modal');
}

echo '</svg>';
echo '</div>';

echo '</div>';
?>


