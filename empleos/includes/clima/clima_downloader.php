<?php
// Descarga del clime
if(copy("http://www.google.com/ig/api?weather=buenos+aires&hl=es","temp.xml")){copy("temp.xml","clima.xml");}
?>