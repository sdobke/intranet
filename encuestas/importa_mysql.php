<?php

// VARIABLES A COMPLETAR
// ---------------------

$base_de_datos = "reclamossbux"; // NOMBRE DE LA BASE DE DATOS
$usuario       = "web";
$password      = "w3b4ls34";
$host          = "localhost";

// --------------------
// FIN DE LAS VARIABLES

function mysqli_import_file($filename, &$errmsg)
{
   /* Read the file */
   $lines = file($filename);

   if(!$lines)
   {
      $errmsg = "cannot open file $filename";
      return false;
   }

   $scriptfile = false;

   /* Get rid of the comments and form one jumbo line */
   foreach($lines as $line)
   {
      $line = trim($line);

      if(!ereg('^--', $line))
      {
         $scriptfile.=" ".$line;
      }
   }

   if(!$scriptfile)
   {
      $errmsg = "no text found in $filename";
      return false;
   }

   /* Split the jumbo line into smaller lines */

   $queries = explode(';', $scriptfile);

   /* Run each line as a query */

   foreach($queries as $query)
   {
      $query = trim($query);
      if($query == "") { continue; }
      if(!fullQuery($query.';'))
      {
         $errmsg = "query ".$query." failed";
         return false;
      }
   }

   /* All is well */
   return true;
}

/* Installs a DB with a given name with the help of a given
   .sql file

   Returns: true if all is well
       false if something is wrong
            (error message is embedded in $errmsg)

   One can also use mysqli_error() if this function
   returns an error.

*/

function mysqli_install_db($dbname, $dbsqlfile, &$errmsg)
{
   $result = true;

   if(!mysqli_select_db($dbname))
   {
     $result = fullQuery("CREATE DATABASE $dbname");
     if(!$result)
     {
        $errmsg = "No se puede crear la base de datos [$dbname]";
        return false;
     }
     $result = mysqli_select_db($dbname);
   }

   if(!$result)
   {
      $errmsg = "No existe la base de datos [$dbname]";
      return false;
   }

   $result = mysqli_import_file($dbsqlfile, $errmsg);

   return $result;
}

$link = mysqli_connect ( $host, $usuario, $password); 

if(mysqli_install_db($base_de_datos, "db.sql", $errmsg)) 
{ 
   echo "Importaci&oacute;n completa."; 
}else 
{ 
  echo "Importaci&oacute;n fallada: ".$errmsg."<br/>".mysqli_error(); 
}  

?>