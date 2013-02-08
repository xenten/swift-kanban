var default_x = -1;
var default_y = -1;
var is_locked = true;
var base_path = "";
var type = "div";
var current_page = "image";
var jfu_url_index = "";
var tfu_insert_resize = 0;
var tfu_show_ruler = 0;
var pdf_available = false;
var full_link_url = '';
var e_name = 'jform_articletext';
var caption_error = "Captions are only displayed properly if an alignment is set. Please select an alignment or don\'t select to use the title as caption.";
var size_warning_showed = false;
var size_warning = "You have set a different size than the one of the original image. It is not good to insert this image this way because the browser has to resize this image on the fly (lower quality) and the website is slower as well because the user has to load the bigger original image.\n\nJFUploader has the option to resize the image to the values you have specified. This gives you better quality and makes your website faster as well.\n\nThis warning is only shown once. If you still want to enter the image with this values please press the 'Insert image' button again.";

function stripBase(fullpath) {
    return fullpath.substr(base_path.length);
}

function toggleLock() {
    if (is_locked) {
        document.getElementById("jfu_lock").className = "jfu_unlock";
        is_locked = false;
    } else {
        document.getElementById("jfu_lock").className = "jfu_lock";
        is_locked = true;
    }
}
function setJfuHeight() {
    if (is_locked) {
        ratio = default_y / default_x;
        if (document.getElementById("jfu_height")) {
            document.getElementById("jfu_height").value = Math.round(numtrim(document.getElementById("jfu_width").value) * ratio);
        }
    }
    checkCreateThumb();
}

function setJfuWidth() {
    if (is_locked) {
        ratio = default_x / default_y;
        if (document.getElementById("jfu_width")) {
            document.getElementById("jfu_width").value = Math.round(numtrim(document.getElementById("jfu_height").value) * ratio);
        }
    }
    checkCreateThumb();
}

function checkCreateThumb() {
    if (document.getElementById("jfu_create_thumb")) {
    document.getElementById("jfu_create_thumb").disabled = !document.getElementById("jfu_width") || (document.getElementById("jfu_create_thumb") && document.getElementById("jfu_width").value == default_x && document.getElementById("jfu_height").value == default_y);
    }
}

function setImage(index, name, x, y) {
    var bname = stripBase(name);
    if (current_page == "image") {
        var is_pdf = isPdf(bname) && pdf_available;
        jfu_url_index = index;
        if (isImage(bname)) {      
            if (document.getElementById("jfu_url")) {
                document.getElementById("jfu_url").value = bname;
            }
            if (document.getElementById("jfu_width")) {
                document.getElementById("jfu_width").value = default_x = x;
            }
            if (document.getElementById("jfu_height")) {
                document.getElementById("jfu_height").value = default_y = y;
            }
            if (document.getElementById("jfu_create_thumb")) {
                document.getElementById("jfu_create_thumb").disabled = true;
            }
            if (document.getElementById("jfu_insert_link")) {
                document.getElementById("jfu_insert_link").disabled = false;
            }
            if (document.getElementById("jfu_insert_image")) {
                document.getElementById("jfu_insert_image").disabled = false;
            }
            setLinkPage(false);
        } else if (is_pdf) {
            if (document.getElementById("jfu_url")) {
                document.getElementById("jfu_url").value = bname;
            }
            if (document.getElementById("jfu_width")) {
                document.getElementById("jfu_width").value = default_x = x;
            }
            if (document.getElementById("jfu_height")) {
                document.getElementById("jfu_height").value = default_y = y;
            }
            if (document.getElementById("jfu_create_thumb")) {
                document.getElementById("jfu_create_thumb").disabled = false;
            }
            if (document.getElementById("jfu_insert_link")) {
                document.getElementById("jfu_insert_link").disabled = true;
            }
            if (document.getElementById("jfu_insert_image")) {
                document.getElementById("jfu_insert_image").disabled = true;
            }
        } else {
            // switch to the link view and insert it there
            document.getElementById("jfu_link").value = bname;
            gotoPage('link');
            // insert image is disabled - only insert link is enabled
            document.getElementById("jfu_url").value = '';
            document.getElementById("jfu_insert_image").disabled = true;
            document.getElementById("jfu_insert_link").disabled = true;
            setLinkPage(true);
            if (document.getElementById("jfu_google_doc")) {
              document.getElementById("jfu_google_doc").disabled = !isGoogleViewer(bname);
              full_link_url = name;
            }
       
        }
    } else {
        document.getElementById("jfu_link").value = bname;
        if (document.getElementById("jfu_google_doc")) {
            document.getElementById("jfu_google_doc").disabled = !isGoogleViewer(bname);
            document.getElementById("jfu_google_doc").checked =  false;
            full_link_url = name;
        }
    }
    if (document.getElementById("jfu_insert_link_button")) {
        document.getElementById("jfu_insert_link_button").disabled = false;
    }
}

/**
 * Set the link page. If there is an image the extra text is shown.
 */
function setLinkPage(linkOnly) {
    if (linkOnly) {
        // show the lighbox text or not
        document.getElementById("jfu_image_div_panel").style.display = "none";
        document.getElementById("link_extra_2").style.display = "none";
        document.getElementById("jfu_image_div_panel_2").style.display = "block";
    } else {
        document.getElementById("jfu_image_div_panel").style.display = "block";
        document.getElementById("link_extra_2").style.display = "inline";
        document.getElementById("jfu_image_div_panel_2").style.display = "none";
    }
}

function gotoPage(page) {
    if (page == "image") {
        document.getElementById("jfu_link_select").style.display = "none";
        document.getElementById("jfu_image_select").style.display = "block";
        current_page = "image";
    } else {
        document.getElementById("jfu_link_select").style.display = "block";
        document.getElementById("jfu_image_select").style.display = "none";
        current_page = "link";
    }
}

function jfu_reset_size() {
    if (default_x != -1) {
        document.getElementById("jfu_width").value = default_x;
    }
    if (default_y != -1) {
        document.getElementById("jfu_height").value = default_y;
    }
    checkCreateThumb();
}

function numtrim(value) {
    return value.replace(/[\sA-Za-z$-]/g, "");
}

function escapeHTML(str) {
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
function escapeTags(str) {
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function insert() {
    var jfu_url = document.getElementById("jfu_url").value;
    if (jfu_url == "") {
        errorImage();
        return false;
    }
    
   
    var jfu_alt = document.getElementById("jfu_alt").value;
    var jfu_image_extra = '';
    if (document.getElementById("jfu_image_extra")) {
        jfu_image_extra = document.getElementById("jfu_image_extra").value;
    }
    var jfu_as_caption = false;
    if (document.getElementById("jfu_as_caption")) {
        jfu_as_caption = (document.getElementById("jfu_as_caption").checked && (jfu_alt != ''));
    }
    
    // we build the style
    var img_style = "";
    var jfu_width = "";
    if (document.getElementById("jfu_width")) {
        jfu_width = document.getElementById("jfu_width").value;
        if (jfu_width != "" && tfu_insert_resize == 1) {
            img_style += "width:" + numtrim(jfu_width) + "px;";
        }
    }
    var jfu_height = "";
    if (document.getElementById("jfu_height")) {
        jfu_height = document.getElementById("jfu_height").value;
        if (jfu_height != "" && tfu_insert_resize == 1) {
            img_style += "height:" + numtrim(jfu_height) + "px;";
        }
    }
    if (document.getElementById("jfu_create_thumb") && !size_warning_showed) {
        if (numtrim(jfu_height) != default_y || numtrim(jfu_width) != default_x) {
            size_warning_showed = true;
            alert(size_warning);
        return;  
        }
    }
    
    var jfu_border = "";
    if (document.getElementById("jfu_border")) {
        jfu_border = document.getElementById("jfu_border").value;
    }
    if (jfu_border != "") {
        img_style += "border-width:" + numtrim(jfu_border) + "px;border-style:solid;";
    }
    var jfu_select = "";
    if (document.getElementById("jfu_select")) {
        jfu_select = document.getElementById("jfu_select").value;
    }
    
    if (jfu_as_caption && jfu_select == "") {
    alert(caption_error);
    return;  
    } 
    
    if (jfu_select != "" && !jfu_as_caption) {
        img_style += jfu_select;
    }
    var jfu_hspace = '';
    if (document.getElementById("jfu_hspace")) {
        jfu_hspace = numtrim(document.getElementById("jfu_hspace").value);
    }
    var jfu_vspace = '';
    if (document.getElementById("jfu_vspace")) {
        jfu_vspace = numtrim(document.getElementById("jfu_vspace").value);
    }
    // when jfu_as_caption is set the margin is done around the div - not the image!
    if (!jfu_as_caption) {
        if (jfu_hspace != "") {
            img_style += "margin-left: " + jfu_hspace + "px; margin-right: " + jfu_hspace + "px;";
        }
        if (jfu_vspace != "") {
            img_style += "margin-top: " + jfu_vspace + "px; margin-bottom: " + jfu_vspace + "px;";
        }
    }

    var jfu_link = document.getElementById("jfu_link").value;
    var jfu_target = document.getElementById("jfu_target").value;
    var jfu_link_extra = document.getElementById("jfu_link_extra").value;

    var new_content = "";
    if (jfu_link != "") {
         if (document.getElementById("jfu_google_doc")) {
            if (document.getElementById("jfu_google_doc").checked) {
              jfu_link = "http://docs.google.com/viewer?url=" + encodeURIComponent(full_link_url);
            }
         }
    
        new_content += '<a href="' + escapeHTML(jfu_link) + '" ';
        if (jfu_target != "") {
            new_content += ' target="' + escapeHTML(jfu_target) + '" ';
        }
        new_content += ' ' + escapeTags(jfu_link_extra) + ' ';
        new_content += '>';
    }
    new_content += '<img src="' + escapeHTML(jfu_url) + '" title="' + escapeHTML(jfu_alt) + '" alt="' + escapeHTML(jfu_alt) + '" style="' + img_style + '" ' + escapeTags(jfu_image_extra) + '></img>';
    if (jfu_link != "") {
        new_content += '</a>';
    }

    if (jfu_as_caption) {
        var div_style = "";
        // when jfu_as_caption is set the margin is done around the div - not the image!
        if (jfu_hspace != "") {
            div_style += "margin-left: " + jfu_hspace + "px; margin-right: " + jfu_hspace + "px;";
        }
        if (jfu_vspace != "") {
            div_style += "margin-top: " + jfu_vspace + "px; margin-bottom: " + jfu_vspace + "px;";
        }
        if (jfu_select != "") {
            div_style += jfu_select;
        }
        var mydiv = '<' + type + ' class="jfu_caption_div" style="' + div_style + '">';
        mydiv += new_content;
        mydiv += '<br/><span class="jfu_caption">' + escapeHTML(jfu_alt) + '</span>';
        mydiv += '</' + type + '>';

        new_content = mydiv;
    }
    window.parent.jInsertEditorText(new_content, e_name);
    // this is different between 1.5 and 1.6
    window.parent.SqueezeBox.close();
}

function insertLink() {
    var jfu_link = document.getElementById("jfu_link").value;
    var jfu_target = document.getElementById("jfu_target").value;
    var jfu_link_extra = document.getElementById("jfu_link_extra").value;
    var jfu_link_save = jfu_link;

    if (document.getElementById("jfu_google_doc")) {
       if (document.getElementById("jfu_google_doc").checked) {
            jfu_link = "http://docs.google.com/viewer?url=" + encodeURIComponent(full_link_url);
       }
    }

    var new_content = "";
    if (jfu_link != "") {
        new_content += '<a href="' + escapeHTML(jfu_link) + '" ';
        if (jfu_target != "") {
            new_content += ' target="' + escapeHTML(jfu_target) + '" ';
        }
        new_content += '>';
    }
    if (jfu_link_extra == '') {
        jfu_link_extra = basename(jfu_link_save);
    }
    new_content += escapeHTML(jfu_link_extra);
    if (jfu_link != "") {
        new_content += '</a>';
    }
    window.parent.jInsertEditorText(new_content, e_name);
    // this is different between 1.5 and 1.6
	window.parent.SqueezeBox.close();
}

function basename(path) {
    return path.replace(/\\/g, '/').replace(/.*\//, '');
}

function setLightbox() {
    document.getElementById("jfu_link_extra").value = 'rel=\"lightbox\"';
    return false;
}
function setLytebox() {
    document.getElementById("jfu_link_extra").value = 'rel=\"lytebox\"';
    return false;
}
function setHighslide() {
    document.getElementById("jfu_link_extra").value = 'class=\"highslide\" onclick=\"return hs.expand(this)\"';
    return false;
}

function createThumbnail() {
    var jfu_url = document.getElementById("jfu_url").value;
    var jfu_width = document.getElementById("jfu_width").value;
    var jfu_height = document.getElementById("jfu_height").value;
    // send to flash and process it
    var obj = document.getElementById("flash_tfu");
    if (obj && typeof obj.refreshFileList != "undefined") {
        // creates a thumbnail of the given index
        obj.createThumbnail(jfu_url_index, jfu_width, jfu_height);
        obj.refreshFileList();
    }
    document.getElementById("jfu_url").value = "";
    if (document.getElementById("jfu_width")) {
        document.getElementById("jfu_width").value = "";
    }
    if (document.getElementById("jfu_height")) {
        document.getElementById("jfu_height").value = "";
    }
    document.getElementById("jfu_insert_image").disabled = true;
    document.getElementById("jfu_insert_link").disabled = true;
    document.getElementById("jfu_insert_link_button").disabled = true;

    if (document.getElementById("jfu_create_thumb")) {
        document.getElementById("jfu_create_thumb").disabled = true;
    }
}

function showRuler() {
    if (tfu_show_ruler == 1 && document.getElementById("jfu_ruler_div")) {
        document.getElementById("jfu_ruler_div").style.display = "block";
    }
}

function hideRuler() {
    if (tfu_show_ruler == 1 && document.getElementById("jfu_ruler_div")) {
        document.getElementById("jfu_ruler_div").style.display = "none";
    }
}

/**
 * Checks if the fine name is an image.
 *
 * @param str The string to check
 */
function isImage(str) {
    str = str.toLowerCase();
    var jpg = str.match(/.*\.(jp)(e){0,1}(g)$/);
    var gif = str.match(/.*\.(gif)$/);
    var png = str.match(/.*\.(png)$/);
    var bmp = str.match(/.*\.(bmp)$/);
    return jpg || gif || png || bmp;
}

/**
 * Checks if the fine name is an image.
 *
 * @param str The string to check
 */
function isPdf(str) {
    str = str.toLowerCase();
    return str.match(/.*\.(pdf)$/);
}

/**
 * Check if the file can be opens in google doc viewer.
 *
 * @param str
 */
function isGoogleViewer(str) {
    str = str.toLowerCase();
    return str.match(/.*\.(doc)(x){0,1}$/) ||
            str.match(/.*\.(xls)(x){0,1}$/) ||
            str.match(/.*\.(ppt)(x){0,1}$/) ||
            str.match(/.*\.(pdf)$/) ||
            str.match(/.*\.(pages)$/) ||
            str.match(/.*\.(ai)$/) ||
            str.match(/.*\.(psd)$/) ||
            str.match(/.*\.(tiff)$/) ||
            str.match(/.*\.(dxf)$/) ||
            str.match(/.*\.(e){0,1}(ps)$/) ||
            str.match(/.*\.(svg)$/) ||
            str.match(/.*\.(ttf)$/) ||
            str.match(/.*\.(xps)$/);
}

function checkCaption() {
    var jfu_as_caption = false;
    var jfu_alt = document.getElementById("jfu_alt").value;
     
    if (document.getElementById("jfu_as_caption")) {
        jfu_as_caption = (document.getElementById("jfu_as_caption").checked && (jfu_alt != ''));
    }

    var jfu_select = "";
    if (document.getElementById("jfu_select")) {
        jfu_select = document.getElementById("jfu_select").value;
    } 
    if (jfu_as_caption && jfu_select == "") {
      alert(caption_error);
      document.getElementById("jfu_as_caption").checked = false;
    }   
}

function enableCaption() {
   if (document.getElementById("jfu_as_caption")) {  
     var jfu_alt = document.getElementById("jfu_alt").value; 
     if (jfu_alt == '') {
        document.getElementById("jfu_as_caption").disabled = true;
        document.getElementById("jfu_as_caption").checked = false;
     } else {
        document.getElementById("jfu_as_caption").disabled = false;
     }
   }
}
