<?php 
$Testuserlogin = rememberMe();
//echo 'test: '.$Testuserlogin;
if($Testuserlogin == 0){
  unset($_SESSION['usrfrontend']);
}
if (!isset($_SESSION['usrfrontend']) || $_SESSION['usrfrontend'] == 0) {
  $gVars = '';
  $getLink = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  if(isset($_GET)){
    $getLink = explode("?",$getLink);
    $getLink = current($getLink);
    $gCont = 0;
    $gVars = '';
    foreach($_GET as $gVar => $gVal){
      $gVars.='&'.$gVar.'='.$gVal;
    }
  }
  $getLink = explode("/",$getLink);
  $reqFile = '&reqFile='.$getLink[3];
 header("Location: /index.php?redirected=1".$gVars.$reqFile);
 die();
}