// TEXTAREA  ========================================================================
function controler_percha_vimeo()
{  
    var extras = $('jform_extras'); 
    //id="jform_params_field_width"
    var width = $('jform_params_field_vimeo_width');
    var height = $('jform_params_field_vimeo_height');
     
    contenr = width.value+"|"+height.value; 
    extras.value = contenr; 
    hide_vimeo()
}

function hide_vimeo()
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
    var width="";
    var height  ="";
    
    if(tmp.length>=1){ width = tmp[0]; }
    if(tmp.length>=2){ height = tmp[1]; }
    
    $('jform_params_field_vimeo_width').value = width;
    $('jform_params_field_vimeo_height').value = height; 
  
    
});




