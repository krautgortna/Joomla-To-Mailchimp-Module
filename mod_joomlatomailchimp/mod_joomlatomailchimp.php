<?php

// No direct access
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/helper.php';
$doc = JFactory::getDocument();

$modID = $module->id;
$modTitle = $module->title;
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$headerTag = $params->get('header_tag', 'h3');

$apiKey = $params->get('api_key', '');
$dppLink = $params->get('data_protection_policy', '');

$js = <<<JS
(function (jQuery) {
    jQuery(document).on('click', '#newsletter-signup input[type=submit]', function () {
        var email   = jQuery('input[name=signup-email]').val(),
            fname   = jQuery('input[name=signup-fname]').val(),
            lname   = jQuery('input[name=signup-lname]').val(),
            request = {
                    'option' : 'com_ajax',
                    'module' : 'joomlatomailchimp',
                    'email'   : encodeURIComponent(email),
                    'fname'   : encodeURIComponent(fname),
                    'lname'   : encodeURIComponent(lname),
                    'format' : 'raw'
                };
               
        responseMsg = jQuery('#signup-response');
        
        if( ! jQuery( '#signup-agree' ).prop( "checked" )) {
          console.log("Agree to policy not checked")
          responseMsg.fadeOut(200,function(){
                    jQuery(this).text("You have to agree to our Data Privacy Policy.")
                           .fadeIn(200,function(){
                               setTimeout(function(){
                                  responseMsg.fadeOut(200);
                               },8000);
                            })

          });
          return false;
        } 
 
        responseMsg.hide()
                   .addClass('response-waiting')
                   .text('Please Wait...')
                   .fadeIn(200);
                   
        jQuery.ajax({
            type   : 'POST',
            data   : request,
            success: function (response) {
                console.log(response);
                var responseData = jQuery.parseJSON(response),
                                   klass = '',
				                           iconKlass = ''
                
                //response conditional
                switch(responseData.status){
                    case 'error':
                        klass = 'response-error';
						            iconKlass = ' <span class="fa fa-close"></span>';
                      break;
                    case 'success':
                        klass = 'response-success';
						            iconKlass = ' <span class="fa fa-heart"></span>';
                      break;  
                }
                
                //show reponse message
                responseMsg.fadeOut(200,function(){
                    jQuery(this).removeClass('response-waiting')
                           .addClass(klass)
                           .text(responseData.message)
                           .fadeIn(200,function(){
                               //set timeout to hide response message
                               setTimeout(function(){
                                   responseMsg.fadeOut(200,function(){
                                       jQuery(this).removeClass(klass);
                                   });
                               },8000);
                            })
							.append(iconKlass);
                 });
            }
        });
        return false;
    });
})(jQuery)
JS;

$doc->addScriptDeclaration($js);

require JModuleHelper::getLayoutPath('mod_joomlatomailchimp', $params->get('layout', 'default'));

?>
