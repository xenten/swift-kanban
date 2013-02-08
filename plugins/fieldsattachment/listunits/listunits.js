
function editRow(idobj)
{
        //alert(idobj);
	// define a function to run when form is submitted
         
        
        $$(".updaterow"+idobj).setStyle("display",'none');
         
        
        var events_edit = function (idobj){
             
                $$(".editrow"+idobj).addEvent( 'click' ,  function(e) {
                        //alert('edit');
                        e.stop();  // stop the default submission of the form


                        //alert( $(this).getParent( ).getParent( ) );
                        //$(this).getParent( ).getParent( ).destroy();

                        //Print input

                        var nombre = $(this).getParent('tr').get("id");
                        var nombre2 = $(this).getParent('tr').get('class') ;

                        nombre_int= nombre2.substring(3);
                        idobj = nombre_int.substring(6);

                        numorden= String(nombre).split("_");
                        numorden = numorden[1];
                        search_values(idobj, numorden); 
                }); // End handling of the 'submit event'


                $$(".updaterow"+idobj).addEvent( 'click' ,  function(e) {
                        //alert('edit');
                        e.stop();  // stop the default submission of the form



                        //Print input

                        var nombre = $(this).getParent('tr').get("id");
                        var nombre2 = $(this).getParent('tr').get('class') ;

                        nombre_int= nombre2.substring(3);
                        idobj = nombre_int.substring(6);

                        numorden= String(nombre).split("_");
                        numorden = numorden[1];
                        update_values(idobj, numorden);





                }); // End handling of the 'submit event'
                
                $("addRow"+idobj).addEvent( 'click' ,  function(e) {
                        //alert('test');
                        e.stop();  // stop the default submission of the form

                        //alert($('this'));

                        // Using Selectors

                        //var myNewElements = new Element('input.json');
                        var myNewElements = $$('input.json_'+idobj);

                        //alert(myNewElements);
                        var linia_json = "";
                        myNewElements.each(function(link)  {
                                //alert(link.name+" = "+link.value);
                                linia_json += '"'+link.name+'" : "'+link.value+'"';
                                linia_json += ',';
                        });

                        linia_json = linia_json.substring(0,linia_json.length-1) ;
                        linia_json = '{'+ linia_json + '}';





                        //Pasamos nuestro string a un objeto
                        var row = '<tr>';
                        var objeto = JSON.decode(linia_json);
                        myNewElements.each(function(link)  {
                            //alert(eval("objeto."+link.name));
                            row += '<td style="font-size:11px; padding:7px; color:#333;" class="'+link.name+'">'+link.value+'</td>';
                        });

                        row += '<td style="font-size:11px; padding:7px; color:#333;">First save article</td>';
                        row += '<td style="font-size:11px; padding:7px; color:#333;"><a href="#"  class="deleterow" >Delete</a></td>';
                        row += '<tr>';


                        inject_row( $('table_result_body_'+idobj), row );
                        events_remove();
                        
                        // alert("sds");

                        //create_input('table_result_'+idobj, this.get("class"));
                        name_input = "field_"+idobj;
                         create_input(idobj, name_input);
                        //var destino = this.get("class");
                        //$(destino).value = String(input_dest).substring(0,  String(input_dest).length-1);


                        // Validate our form. Make sure no fields are blank
                        /*var valid_form = true;
                        $$('input.json_'+idobj).each(function(item){
                                if( item.value == '' ) valid_form = false;
                        });

                        // If our form is valid submit to table_form.php
                        // Else show an error message.
                        if( valid_form ) {
                                //this.send();
                        } else {
                                alert('Fill in all fields');
                        }*/



                }); // End handling of the 'submit event'
        
        }
        
        
        
        
        var update_values = function (idobj, numorden){
                // Using Selectors
                 //var myNewElements = new Element('input.json');
                 var myJson = $('field_'+idobj);
                  
                 
                 var objeto = JSON.decode(myJson.get("value"));
                 
                var text = myJson.get("value");
                //alert(text);
                var lineas = String(text).split("},");
                
                //var num = (numorden+1);
                $$(".editrow"+idobj).setStyle("display",'block');   
                $("editrow_"+idobj+"_"+numorden).setStyle("display",'block');
                $("updaterow_"+idobj+"_"+numorden).setStyle("display",'none');
                $$(".deleterow"+idobj).setStyle("display",'block');
                $$(".deleterow").setStyle("display",'block');
                
                
                
                
                $$('table.table_result_'+idobj+' #tr_'+numorden).each(function(el) {
                      

                    el.getChildren('td').each(function(el2) {
                        //alert(el.get("html")+" - "+el.get("class"));
                        //if(el.get("class")) linea += '"'+el.get("class")+'":'+'"'+el.get("html")+'",';
                        var valor = el2.getChildren('input').get("value");
                        
                        if( el2.getChildren('input').length > 0) {
                                el2.set("text", valor);
                                
                            
                        }
                        //alert("SSS:"+valor);
                    });
                    
                   //alert("-- "+linea);
                });
                
                name_input = "field_"+idobj;
                
                create_input(idobj, name_input);
                 
        }
    
    
        var search_values = function( idobj, numorden ){
            
                
                // Using Selectors
                //var myNewElements = new Element('input.json');
                var myJson = $('field_'+idobj); 
                 
                var objeto = JSON.decode(myJson.get("value"));
                 
                var text = myJson.get("value");
                //alert(text);
                var lineas = String(text).split("},");
                
                //alert("updaterow_"+idobj+"_"+numorden);
                
                //var num = (numorden+1);
                $$(".editrow"+idobj).setStyle("display",'none');  
                $("editrow_"+idobj+"_"+numorden).setStyle("display",'none');
               // $("updaterow_"+numorden).setStyle("display",'block'); 
                $("updaterow_"+idobj+"_"+numorden).setStyle("display",'block'); 
                $$(".deleterow"+idobj).setStyle("display",'none');
                $$(".deleterow").setStyle("display",'none');
                
                //alert(lineas.length);
                for(var cont = 0;lineas.length > cont;cont++){ 
                    //alert(lineas[cont]);
                    var linea = lineas[cont];
                    if(String(linea).indexOf("}") <=-1){linea = lineas[cont]+"}";}
                    
                    //alert(linea);
                    myData = JSON.parse(linea, function (key, value) {
                        var type;
                        if((numorden == cont) &&(String(key).length>0)) {
                            //alert(key+"-->"+value);
                            $(key+"_"+idobj).set("value", value)
                            
                            //Create Input
                            $("td_"+key+"_"+numorden).set("html",'<input type="text" name="" value="'+value+'" />');
                            
                        }
                      
                        //return value;
                        });
                }

        }
        
        
        var create_input = function( idobj,  name_input   ){
              
             var input_value = ""; 
            // alert("create_input: "+idobj);
             $$('table.table_result_'+idobj+' tbody tr').each(function(el) {
                    var linea = "{";
                    //el.addClass(count++ % 2 == 0 ? 'odd' : 'even');
                    //alert(el.getChildren('td'));

                    el.getChildren('td').each(function(el) {
                        //alert(el.get("html")+" - "+el.get("class"));
                         
                        if(String(el.get("class"))!="null") {
                            linea += '"'+el.get("class")+'":'+'"'+el.get("html")+'",';
                             
                        }
                    });
                   linea = linea.substring(0,linea.length-1) ;
                   //alert( linea.length );
                   if( linea.length > 0) linea += "}";
                   if( linea.length > 0) input_value += linea+',';
                   //alert("-- "+linea);
                });

            //INSERT INOUT
             
            $(name_input).value = String(input_value).substring(0,  String(input_value).length-1);
            //alert(input_value);
            return input_value;

        }
        
	
	var inject_row = function( table_body, row_str ){
		// convert string to table wrapped in a div element
		var newRow = htmlToElements( row_str );
		// inject the new row into the table body
		newRow.inject( table_body );

	}

        var events_remove = function(  ){
            $$('.deleterow').addEvent('click', function(e) {
                e.stop();
                //alert( $(this).getParent( ).getParent( ) );
                $(this).getParent( ).getParent( ).destroy();

                //Print input

                var nombre = $(this).getParent('tr').get("id");
                var nombre2 = $(this).getParent('tr').get('class') ;

                nombre_int= nombre2.substring(3);
                idobj = nombre_int.substring(6);
                
                //nombre_int = find_input(nombre) ;

               // input_dest = create_input('table_result_'+idobj, nombre_int); 
                
                //events_edit(idobj);
                
                //$$(".editrow"+idobj).setStyle("display",'none');   
               // $$(".functions").set("html", "<a href='' >Save article</a>"); 
               
                reorder_table(idobj);
                
                name_input = "field_"+idobj;
                
                create_input(idobj, name_input);
                
                
                
                return false;
            });
        }

        var find_input = function(  name_input ){
            //alert(name_input);
            //alert("d: "+$("addRow"+idobj).get("class") );
            //input_value = $("addRow"+idobj).get("class") ;
            //alert("find: "+input_value);
            //return input_value;

        }


        /*var create_input = function( name_table, name_input   ){
           
             var input_value = ""; 
             $$('table.table_result_'+idobj+' tbody tr').each(function(el) {
                    var linea = "{";
                    //el.addClass(count++ % 2 == 0 ? 'odd' : 'even');
                    //alert(el.getChildren('td'));

                    el.getChildren('td').each(function(el) {
                        //alert(el.get("html")+" - "+el.get("class"));
                        if(el.get("class")) linea += '"'+el.get("class")+'":'+'"'+el.get("html")+'",';
                    });
                   linea = linea.substring(0,linea.length-1) ;
                   //alert( linea.length );
                   if( linea.length > 0) linea += "}";
                   if( linea.length > 0) input_value += linea+',';
                   //alert("-- "+linea);
                });

            //INSERT INOUT
             
            $(name_input).value = String(input_value).substring(0,  String(input_value).length-1);

            return input_value;

        }
*/
        /**
	 *  Fill the input init
	 */
        var initinput = function( value ){
                //alert(value);
                /*
                var objeto = JSON.decode(linia_json);
                myNewElements.each(function(link)  {
                     //alert(eval("objeto."+link.name));
                     row += '<td class="'+link.name+'">'+link.value+'</td>';
                });
                */
        }



	/**
	 *  wraps tr in a div with full table details.
	 *  this little hack is required for IE support (h8 ie)
	 */
	var htmlToElements = function(str){
	    return new Element('div', {html: '<table><tbody>' + str + '</tbody></table>'}).getElement('tr');
	}
        
        
        var reorder_table = function (idobj)
        {
            //alert("reorder_table --> "+idobj);
            var cont = 0;
            $$('table.table_result_'+idobj+ " tr" ).each(function(el) {
                    
                        var tr_id =  el.get("id");
                       
                        if(String(tr_id).indexOf("tr_")==0)
                            {
                                el.set("id", "tr_"+cont);
                                var oldid =  String(tr_id).substring(3);
                                /*alert("id:"+tr_id);
                                var trid =  String(tr_id).substring(3);
                                alert(trid);*/
                                var change = false;
                                el.getChildren('td').each(function(el2) {
                                    var td_id = el2.get("id");
                                    //alert(td_id);
                                    var tmp = String(td_id).split("_");
                                    var tmp1 = tmp[2];
                                    
                                    if(tmp1 == oldid) { 
                                        var newid = tmp[0]+"_"+tmp[1]+"_"+cont;
                                        el2.set("id",newid);
                                        change = true;
                                    }
                                   
                                    if(String(td_id)=="null")
                                        {
                                            // alert(String(td_id));
                                            if(change){
                                                 el2.getChildren('a').each(function(el3) {
                                                    var link_id =el3.get("id");
                                                    var tmp = String(link_id).split("_");
                                                    var new_id= tmp[0]+"_"+idobj+"_"+cont;
                                                    el3.set("id", new_id);
                                                     
                                                 });
                                                /*var link_id = el2.getElement("a").get("id");
                                                var tmp = String(link_id).split("_");
                                                var new_id= tmp[0]+"_"+cont;
                                                el2.getElement("a").set("id", new_id);*/
                                            }
                                        }
                                    
                                    //alert(el.get("html")+" - "+el.get("class"));
                                    //if(el.get("class")) linea += '"'+el.get("class")+'":'+'"'+el.get("html")+'",';
                                    // var valor = el3.getChildren('input').get("value");

                                    //  if( el3.getChildren('input').length > 0) el2.set("text", valor);
                                    //alert("SSS:"+valor);
                                });
                                cont++;
                            }
                        
                   
                   
                
            });
        }

        //init_table();
        events_remove();
        
        events_edit(idobj);
}






