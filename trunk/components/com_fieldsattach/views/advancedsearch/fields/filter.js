//FILTER CHANGE

//OBJECT ARTICLES =========================================
function changefilter1()
{ 
   
     
    /*MOOTOOLS
     *$$('.filterfieldsattach select, .filterfieldsattach input').each(function(el){
        var name = el.name;
        var tmp = String(name).split("_");
        var id = tmp[1];
         
      //  alert(id);
        
        valor_filter += id+"_"+el.value+",";
    });
    
    valor_filter = valor_filter.substring(0, valor_filter.length-1);
    
        $('filterfields').value= valor_filter;;*/
     var valor_filter = "";
    var elem = document.getElementById('searchForm').elements;
     
    for(var i = 0; i < elem.length; i++)
    { 
        var name = elem[i].name;
        var tmp = String(name).split("_");
        var id = tmp[1]
        if(String(elem[i].name).indexOf("field_")==0){
            //alert(elem[i].value);
            valor_filter += id+"_"+elem[i].value+",";
        }
    } 
    valor_filter = valor_filter.substring(0, valor_filter.length-1);
    document.getElementById('searchForm').elements['filterfields'].value = valor_filter;
     
   
}

