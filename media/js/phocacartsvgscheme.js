function pcSVGSchemeRequest(id, modal) {
    
    var phVars 		= Joomla.getOptions('phVarsModPhocacartSVGScheme');
    var data = [];
    var url = phVars['baseUrl'] + 'index.php?option=com_ajax&module=phocacart_svg_scheme&format=raw&id='+id;

    jQuery.ajax({
        type: "POST",
        url: url,
        async: "false",
        cache: "false",
        data: data,
        dataType:"HTML",
        success: function(data){
            jQuery("#phSVGSchemePopupBody").html(data);
            if (modal !== '') {
                phShowModal(document.getElementById(modal), {});
            }
        }
    })
}

document.addEventListener("DOMContentLoaded", function(e) { 
    
    
    document.querySelectorAll('*').forEach(node => {
        if (node._tippy) {
          node._tippy.destroy();
        }
    });
    tippy.delegate('#pcSvgBox', {
        target: '[data-tippy-content]',
        animation: 'fade',
        followCursor: true,
        allowHTML: true
    });

    jQuery(document).on('click', '.pcSVGSchemeCategoryItem', function() {
        var id = jQuery(this).data('id');
        jQuery('.pcSVGSchemeCategoryListItem').removeClass('active');
        jQuery('#' + 'pcSVGSchemeCategoryListItem' + id).addClass('active');
        pcSVGSchemeRequest(id, "phSVGSchemePopup");
    })


    jQuery(document).on('click', '.pcSVGSchemeCategoryListItem', function() {
        var id = jQuery(this).data('id');
        jQuery('.pcSVGSchemeCategoryListItem').removeClass('active');
        jQuery('#' + 'pcSVGSchemeCategoryListItem' + id).addClass('active');
        pcSVGSchemeRequest(id, '');
    })    
});

