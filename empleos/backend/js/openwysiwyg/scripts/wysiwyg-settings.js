/********************************************************************
 * openWYSIWYG settings file Copyright (c) 2006 openWebWare.com
 * Contact us at devs@openwebware.com
 * This copyright notice MUST stay intact for use.
 *
 * $Id: wysiwyg-settings.js,v 1.4 2007/01/22 23:05:57 xhaggi Exp $
 ********************************************************************/

/*
 * Full featured setup used the openImageLibrary addon
 */
var full = new WYSIWYG.Settings();
//full.ImagesDir = "images/";
//full.PopupsDir = "popups/";
//full.CSSFile = "styles/wysiwyg.css";
full.Width = "85%"; 
full.Height = "250px";
// customize toolbar buttons
full.addToolbarElement("font", 3, 1); 
full.addToolbarElement("fontsize", 3, 2);
full.addToolbarElement("headings", 3, 3);
// openImageLibrary addon implementation
full.ImagePopupFile = "addons/imagelibrary/insert_image.php";
full.ImagePopupWidth = 600;
full.ImagePopupHeight = 245;

/*
 * Small Setup Example
 */
var small = new WYSIWYG.Settings();
small.Width = "350px";
small.Height = "200px";
small.DefaultStyle = "font-family: Arial; font-size: 12px; background-color: #FFF";
small.Toolbar[0] = new Array("font", "fontsize", "bold", "italic", "underline", "forecolor", "backcolor", "seperator", "justifyfull", "justifyleft", "justifycenter", "justifyright", "seperator"); // small setup for toolbar 1
small.Toolbar[1] = new Array("subscript", 
			"superscript", 
			"seperator", 
			"cut", 
			"copy", 
			"paste",
			"removeformat",
			"seperator", 
			"undo", 
			"redo", 
			"seperator");
small.Toolbar[2] = ""  // disable toolbar 3
small.StatusBarEnabled = true;
/*
 * Mini Setup Example
 */
var mini = new WYSIWYG.Settings();
mini.Width = "800px";
mini.Height = "300px";
mini.DefaultStyle = "font-family: Arial; font-size: 12px; background-color: #FFF";
mini.Toolbar[0] = new Array("bold", "italic", "underline", "seperator", "forecolor", "backcolor", "justifyfull", "justifyleft", "justifycenter", "justifyright", "seperator"); // mini setup for toolbar 1
mini.Toolbar[1] = new Array("subscript", 
			"superscript", 
			"seperator", 
			"cut", 
			"copy", 
			"paste",
			"removeformat",
			"seperator", 
			"undo", 
			"redo", 
			"seperator");
mini.Toolbar[2] = ""  // disable toolbar 3
mini.StatusBarEnabled = true;
