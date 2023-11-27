<?PHP
echo 'Audios';
$agregarm = (isset($agregarm) && $agregarm == 0) ? 0 : 1;
$max_mp3 = (isset($max_mp3)) ? $max_mp3 : 21;
echo agregaMp3(1,1,$agregarm);
$cont_mp3 = 2;
while ($cont_mp3 < $max_mp3){
	echo agregaMp3($cont_mp3);
	$cont_mp3++;
}
?>