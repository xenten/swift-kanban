// TEXTAREA  ========================================================================
function controler_percha_file()
{ 
    var extras = $('jform_extras');  
    //contenr = $('jform_params_field_width').value+"|"+$('jform_params_field_height').value;
    contenr  = $('jform_params_field_selectable').value;
    extras.value = contenr;
    hide_file()
}

function hide_file()
{
    var nom =  'file-params'; 
    if($(nom)!=null)
    { 
        $($(nom).getParent( )).setStyle('display','none');
    } 

}
 


window.addEvent('domready', function() {
    var extras = $('jform_extras');  
    var tmp = String(extras.value).split("|"); 
    var selectable=""; 
    
    if(tmp.length>=1){ selectable = tmp[0]; } 
    
    $('jform_params_field_selectable').value = selectable; 
  
    
});
