<?php
use SKien\PNServer\PNVapid;

function getMyVapid()
{
    /**
	 * set the generated VAPID key and rename to MyVapid.php
	 *
	 * you can generate your own VAPID key on https://tools.reactpwa.com/vapid.
	 */
     $oVapid = new PNVapid(
     "mailto:sdobke@gmail.com",
     "BJWgy3AK2QAvjdPGZW59-e3aCwcIV_7IrdUQdEEfqmYhRJuyW5rX-trTY90RMLlWFbOPCell2NAHRoijVPiTccc",
     "htIWaeuNUi1_uv83mdu_EGS0pvXJ4QphppGc5t116SU"
     );
    return $oVapid;    
}