// TEXTAREA  ========================================================================
function controler_percha_select()
{ 
    var extras = $('jform_extras');
    extras.value  =  extras.value + "\n"+ $('jform_params_field_select_name').value+"|"+$('jform_params_field_select_value').value
    if($('jform_params_field_selectplus_default').checked == true) extras.value += "|true";
    
    extras.set({
				'value': extras.value
			});
    hide_input()
}

function hide_checkbox()
{
    var nom =  'select-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 



