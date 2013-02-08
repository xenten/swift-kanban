// TEXTAREA  ========================================================================
function controler_percha_selectmultiple()
{ 
    var extras = $('jform_extras');

    extras.value  =  extras.value + $('jform_params_field_selectmultiple_name').value+"|"+$('jform_params_field_selectmultiple_value').value + "\n";
    extras.set({
				'value': extras.value
			});
    hide_selectmultiple(); 
}

function hide_selectmultiple()
{
   
    var nom =  'select-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 



