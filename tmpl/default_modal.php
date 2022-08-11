<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

$d['close'] = '<button type="button" class="'.$d['s']['c']['modal-btn-close'].'"'.$d['s']['a']['modal-btn-close'].' aria-label="'.Text::_('COM_PHOCACART_CLOSE').'" '.$d['s']['a']['data-bs-dismiss-modal'].' ></button>';


?>
<div id="phSVGSchemePopup" class="<?php echo $d['s']['c']['modal.zoom'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="<?php echo $d['s']['c']['modal-dialog'] ?>  modal-xl modal-dialog-centered">
      <div class="<?php echo $d['s']['c']['modal-content'] ?>">
        <div class="<?php echo $d['s']['c']['modal-header'] ?>"><?php
            /*
		    <h5 class="<?php echo $d['s']['c']['modal-title'] ?>"><?php echo PhocacartRenderIcon::icon($d['s']['i']['info-sign'], '', ' ') . $d['info_msg'] ?>TITLE</h5>
            */
            echo $categoryListO; ?>
            <?php echo $d['close'] ?>
        </div>
        <div class="<?php echo $d['s']['c']['modal-body'] ?>">
            <?php //echo $categoryListO;
            echo '<div id="phSVGSchemePopupBody"></div>'; ?>
        </div>
		<?php /* <div class="<?php echo $d['s']['c']['modal-footer'] ?>"></div> */ ?>
	   </div>
    </div>
</div>


