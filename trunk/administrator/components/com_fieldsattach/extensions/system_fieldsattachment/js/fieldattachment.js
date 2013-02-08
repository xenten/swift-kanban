// everything happens inside 'domready' event
window.addEvent('domready', function(){ 

    //TODO ------Category select event
    /*$('jform_catid').addEvent('change',function(event) {
            //====================================
            //alert($(this).value );
            //
            var pathname = getAbsolutePath(); 
            //prevent the page from changing
            event.stop();
            //make the ajax call
            var req = new Request({
              method: 'post',
              url: pathname+"administrator/index.php?option=com_fieldsattach&view=fields&format=raw",
              data: { 'catid' : $(this).value, 'id' : $("jform_id").value },
              onRequest: function() {
                  // alert('Request made. Please wait...');
              }, 
              onComplete: function(response) {
                  //alert('Response: ' + response);
                  $("fieldsattach_footer").set('html',response);
                  // alert('Response: '+response );
                  var tabPane = new TabPane('demo');
                // alert('Response:aaaaaaaaaaaa ' + response);
              }
            }).send();
        });
       */

});

function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    var path = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
      
    return path.replace("administrator/", "")
}

 

function addRow(idobj)
{
        //alert(idobj);
	// define a function to run when form is submitted
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
                row += '<td style="font-size:11px; padding:7px; color:#333;"><a href="#"  class="deleterow" >Delete</a></td>';
                row += '<tr>';
 

                inject_row( $('table_result_body_'+idobj), row );
                events_remove();

                input_dest = create_input('table_result_'+idobj, this.get("class"));
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

	/**
	 * This is a handy little function that handles adding an
	 * array of data to a table.
	 */
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

                input_dest = create_input('table_result_'+idobj, nombre_int);


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


        var create_input = function( name_table, name_input   ){
           
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

        //init_table();
        events_remove();
}


// end handling of 'domready' event