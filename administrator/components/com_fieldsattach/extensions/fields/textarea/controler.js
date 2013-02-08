// TEXTAREA  ========================================================================
function controler_percha_textarea()
{ 
    var extras = $('jform_extras');
    extras.set({
				'value':$('jform_params_field_textarea').value
			});
    hide_input()
}

function hide_textarea()
{
    var nom =  'input-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 
window.addEvent('domready', function() {
    var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var textarea=""; 
    
    if(tmp.length>=1){ textarea = tmp[0]; } 
    
    $('jform_params_field_textarea').value = textarea; 
  
    
});



