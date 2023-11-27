<?PHP
if ($limit == 0){
	$totalpages = 1;
}else{
	$totalpages = $contar_paginador / $limit;
}
$totalpages = ceil($totalpages);
if($page == 1)
{
$actualpage = '1';
}
else
{
$actualpage = "[$page]";
}
if($page < $totalpages)
{
$nv = $page+1;
$pv = $page-1;
$nextpage = '<a href=?page='.$nv.'&estado='.$estado.'&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'>Siguiente ></a>';
$prevpage = '<a href=?page='.$pv.'&estado='.$estado.'&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'>< Anterior</a>';
$firstpage = '<a href="?page=1&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'"><< </a>';
$finalpage = '<a href="?page='.$totalpages.'&estado='.$estado.'&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'"> >></a>';
}
if($page == '1')
{
$nv = $page+1;
$nextpage = '<a href=?page='.$nv.'&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'> Siguiente > </a>';
$prevpage = '< Anterior';
$firstpage = '<< ';
$finalpage = '<a href="?page='.$totalpages.'&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'"> >></a>';
}elseif($page == $totalpages){
$pv = $page-1;
$nextpage = ' Siguiente >';
$prevpage = '<a href=?page='.$pv.'&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'>< Anterior </a>';
$firstpage = '<a href="?page=1&orden='.$orden.'&desde='.$fechadesde.'&hasta='.$fechahasta.'&items='.$items.'"><< </a>';
$finalpage = ' >>';
}
if($totalpages == '1' || $totalpages == '0'){
$nextpage = 'Siguiente >';
$prevpage = '< Anterior';
$firstpage = '<< ';
$finalpage = ' >>';
}
?>
<div class="paginador" style="margin-bottom: 10px; margin-top: 10px; font-size: 11px;" align="center">
	<span><?PHP echo $firstpage;?></span> <span><?PHP echo $prevpage;?></span> <span><?PHP echo $actualpage;?>/<?PHP echo $totalpages;?></span> <span><?PHP echo $nextpage;?></span> <span><?PHP echo $finalpage;?></span>
</div>