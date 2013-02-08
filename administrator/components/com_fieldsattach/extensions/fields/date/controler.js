// TEXTAREA  ========================================================================
function controler_percha_date()
{ 
    var extras = $('jform_extras');
    extras.value  = $('jform_params_field_date').value
    extras.set({
				'value': extras.value
			});
    hide_date()
}

function hide_date()
{
    var nom =  'date-params';

    if($(nom)!=null)
    {
        
        $($(nom).getParent( )).setStyle('display','none');
    }
 

}
 


window.addEvent('domready', function() {
    var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var date=""; 
    
    if(tmp.length>=1){ date = tmp[0]; } 
    
    $('jform_params_field_date').value = date; 
  
    
});
