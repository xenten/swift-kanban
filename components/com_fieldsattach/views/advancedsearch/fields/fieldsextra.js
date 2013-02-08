//OBJECT ARTICLES =========================================
function fields(id, title)
{
    this.array_of_fields = new Array(); 
   
}
//OBJECT ARTICLE =========================================
function field(id, title){
        this.id = id;
        this.title = title;
        this.textfilter;
        this.type;
    }
    
 
    
//FUNCTION ADD ID =========================================
/*fields.prototype.RenderLI = function(id, title)    // Define Method
{ 
   
   var ids = document.id("fieldsid_name").value;
   
    var sid = new Array(); 
    sid = (ids.split(",")); 
    
    for(var i=0; i<ids.length();i++)
        {
            alert("ids");
            
        }
   
   
}*/
   
    
//FUNCTION ADD ID =========================================
fields.prototype.AddId = function(id, title, fieldname, textfilter, type)    // Define Method
{ 
    var obj_field = new field;
    obj_field.id  = id;
    obj_field.title  = title;
    
    obj_field.textfilter  = textfilter; 
    obj_field.type  = type; 
    
    
      

    //FIND IF EXIST ---------------------------------------
    var find = false;
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        
        if(this.array_of_fields[cont].id == id) {find = true;break}
    }

    //IF NOT EXIST ADD ---------------------------------------
    if(!find) {this.array_of_fields[this.array_of_fields.length] = obj_field;}

    //RENDER --------------------------------------------------
    this.render_fieldsid(fieldname);
}





//FUNCTION REMOVE ID =========================================
fields.prototype.RemoveId = function(id,fieldname)    // Define Method
{  
     
    
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        var elid = this.array_of_fields[cont].id ;
        if(elid == id) this.array_of_fields.splice(cont,1);    
    }
    //RENDER --------------------------------------------------
    this.render_fieldsid(fieldname);
    
}

//FUNCTION RENDER =========================================
fields.prototype.render_fieldsid = function(fieldname)    // Define Method
{
    
    document.id(fieldname).value ="";
    document.getElementById("fieldslist").innerHTML ="" ;
    //$("articleslist").value="";

    /*
    var myString = new String('red,green,blue');
    var myArray = myString.split(',');
    */
   
    for(var cont=0;cont<this.array_of_fields.length;cont++ )
    {
        var str = this.array_of_fields[cont].id +"_"+this.array_of_fields[cont].textfilter ; 

        if (this.array_of_fields.length-1>cont) str += ",";
        document.id(fieldname).value += str;
        addLI( this.array_of_fields[cont].id, this.array_of_fields[cont].title, fieldname, this.array_of_fields[cont].textfilter);
    }
}
fields.prototype.changeFilter = function(id, fieldname)
{
   // alert(document.id(fieldname+"_value").value);
   
    var valor = document.id(fieldname+"_"+id+"_value").value;
    valor = String(valor).replace(/,/gi, "_");
   // alert(valor);
    
    var input = document.id(fieldname).value;
    var tmpids = String(input).split(",");
    for(var i=0; i< tmpids.length;i++)
        {
            var elid = String(tmpids[i]).split("_");
            //alert(elid[0]);
            if(elid[0] == id){
                tmpids[i] = id+"_"+valor;
                
            }
        }
    
    document.id(fieldname).value = tmpids;
    
    
    
}
//FUNCTION AD LI =========================================
function addLI( id, text, fieldname, textfilter){
    var Parent = document.getElementById("fieldslist");
    var NewLI = document.createElement("LI");
    
    textfilter = String(textfilter).replace(/_/gi, ",");
    
    
    text = '<div style="position:relative;width:100%; padding:5px 0 5px 0;border-top:#ddd dotted 1px; overflow:hidden;"><div style="  padding:5px 0 5px 0;">'+text+'</div><div><input type="text" name="'+fieldname+'_'+id+'_value" id="'+fieldname+'_'+id+'_value" onchange="javascript:obj.changeFilter('+id+',\''+fieldname+'\')" value="'+textfilter+'" size="80" /></div>  <div style="position:absolute; top:10px; right:5px;"><a href="javascript:obj.RemoveId('+id+',\''+fieldname+'\')" >delete</a></div></div>';

    NewLI.innerHTML = text; 
    Parent.appendChild(NewLI);
} 

//CREATE OBJECT ARTICLES =========================================
obj = new fields; 


//MOOTOOLS EVENT =========================================
window.addEvent('domready', function() {
    //alert("admin");
    init_obj();
});
 
