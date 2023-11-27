<form name="salas" id="salas" action="<?PHP echo $form_dest;?>.php" method="post">
	<div class="left w100 mb5 c444444"><strong>Fecha</strong></div>
    <div class="left w100 mb15 inputcortos">
        <select name="dia" id="dia" <?PHP echo $cambhor;?>>
            <?PHP
            $cont = 1;
			$ultdia = 29;
			if($messelect == '04' || $messelect == '06' || $messelect == '09' || $messelect == '11'){
				$ultdia = 30;
			}elseif($messelect == '02'){
				$ultdia = 29;
			}else{
				$ultdia = 31;
			}
			$dias = array("Dom","Lun","Mar","Mie","Jue","Vie","Sab");
            while($cont <= $ultdia){
				$totfech = $anioselect.'-'.$messelect.'-'.$cont;
				$numdia = date('w',strtotime($totfech));
				$nomdia = $dias[$numdia];
				$fuldia = $nomdia.' '.$cont;
                if ($cont == $diaselect) $sel = ' selected="selected"'; else $sel = ' ';
                    echo '<option value="'.$cont.'" '.$sel.'>'.$fuldia.'</option>';
                $cont++;
            }
            ?>
        </select>
        <select name="mes" id="mes" <?PHP echo $cambhor;?>>
            <option value="01" <?PHP echo optSel('01', $messelect);?>>Ene</option>
            <option value="02" <?PHP echo optSel('02', $messelect);?>>Feb</option>
            <option value="03" <?PHP echo optSel('03', $messelect);?>>Mar</option>
            <option value="04" <?PHP echo optSel('04', $messelect);?>>Abr</option>
            <option value="05" <?PHP echo optSel('05', $messelect);?>>May</option>
            <option value="06" <?PHP echo optSel('06', $messelect);?>>Jun</option>
            <option value="07" <?PHP echo optSel('07', $messelect);?>>Jul</option>
            <option value="08" <?PHP echo optSel('08', $messelect);?>>Ago</option>
            <option value="09" <?PHP echo optSel('09', $messelect);?>>Sep</option>
            <option value="10" <?PHP echo optSel('10', $messelect);?>>Oct</option>
            <option value="11" <?PHP echo optSel('11', $messelect);?>>Nov</option>
            <option value="12" <?PHP echo optSel('12', $messelect);?>>Dic</option>
        </select>
        <select name="anio" id="select3" <?PHP echo $cambhor;?>>
            <?PHP
            echo '<option value="'.$anioselect.'" '.optSel($anioselect,date("Y-m-d")).'>'.$anioselect.'</option>';
            $aniosig = $anioselect + 1;
            if($aniomax == $aniosig){
                echo '<option value="'.$aniomax.'" '.optSel($anioselect,$aniomax).'>'.$aniomax.'</option>';
            }
            ?>
        </select>
    </div>          
    <?PHP if($usacap == 1){?>
        <div class="left w100 mb5 c444444"><strong>Cantidad de asistentes</strong></div>
        <div class="left w100 mb15">
            <input type="text" name="cantidad" id="textfield" value="<?php echo $cantasis;?>" style="width:40px" />
        </div>
    <?PHP } ?>
    <div class="left w100 mb5 c444444"><strong>Motivo</strong></div>
    <div class="left w100 mb15">
    <input name="motivo" id="motivo" type="text" value="<?PHP echo $motivo;?>" />
    </div>
    <div class="left w100 mb5 c444444"><strong>Duraci&oacute;n</strong></div>
    <div class="left w100 mb15 inputcortos">
        <?PHP
        $durhora = (isset($_POST["durhora"])) ? $_POST["durhora"] : '0';
        $durminuto = (isset($_POST["durminuto"])) ? $_POST["durminuto"] : '30';
        ?>
        <select name="durhora" id="select4" <?PHP echo $cambhor;?>>
            <?PHP
            $canthoras = 0;
            while($canthoras < 10){
                ?>
                <option value="<?PHP echo $canthoras;?>" <?PHP if ($canthoras == $durhora) echo ' selected="selected"';?>><?PHP echo $canthoras;?></option>				
				<?PHP $canthoras++;?>
            <?PHP } ?>
        </select> Horas
        <select name="durminuto" id="select5" <?PHP echo $cambhor;?>>
            <option value="00" <?PHP if ('00' == $durminuto) echo ' selected="selected"';?>>00</option>
            <option value="30" <?PHP if ('30' == $durminuto) echo ' selected="selected"';?>>30</option>
        </select> Minutos
    </div>
    <?PHP if($modo == 1){ // Selector de salas ?>
    	<div class="left w100 mb5 c444444"><strong>Sala</strong></div>
        	<div class="left w100 mb15 inputcortos">
            <select name="sala" id="sala" class="txtfield" style="width:250px" <?PHP echo $cambhor;?> >
            	<?PHP
				$sqlsal = "SELECT * FROM intranet_reservas_salas WHERE del = 0 ORDER BY capacidad";
				$ressal = fullQuery($sqlsal);
				while($rowsal = mysqli_fetch_array($ressal)){
					$sid = $rowsal['id'];
					?>
                	<option value="<?PHP echo $sid;?>" <?PHP echo optSel($sid,$sala);?>>
                    	<?PHP echo $rowsal['nombre'];?>
                    </option>
				<?PHP } ?>
            </select>
        </div>
    <?PHP } ?>
    <?PHP if (isset($_REQUEST["enviar"]) && $_REQUEST["enviar"] == 'Buscar' && $errno == 0) {?>
        <div class="left w100 mb5 c444444"><strong>Repeticiones</strong></div>
        <div class="left w100 mb15 inputcortos">
            <select name="repeticiones" id="repeticiones" class="txtfield" style="width:150px" onchange="javascript:cambiaRepeticiones(this.value);" >
                <option value="0" <?PHP echo optSel(0,$repetir);?>>Sin repeticiones</option>
                <option value="1" <?PHP echo optSel(1,$repetir);?>>Diario</option>
                <option value="2" <?PHP echo optSel(2,$repetir);?>>Semanal</option>
                <option value="3" <?PHP echo optSel(3,$repetir);?>>Quincenal</option>
                <!--<option value="4" <?PHP echo optSel(4,$repetir);?>>Mensual</option>
                <option value="5" <?PHP echo optSel(5,$repetir);?>>Bimestral</option>
                <option value="6" <?PHP echo optSel(6,$repetir);?>>Trimestral</option>
                <option value="7" <?PHP echo optSel(7,$repetir);?>>Cuatrimestral</option>
                <option value="8" <?PHP echo optSel(8,$repetir);?>>Semestral</option>
                <option value="9" <?PHP echo optSel(9,$repetir);?>>Anual</option>-->
            </select>
        </div>
        <?PHP $repfec = ($repetir > 0) ? 'block' : 'none';?>
        <div style="display:<?PHP echo $repfec;?>" id="fecha_finrep">
            <div class="left w100 mb5 c444444"><strong>Repetir hasta</strong></div>
            <div class="left w100 mb15 inputcortos">
                <select name="diav" id="diav" <?PHP echo $cambhor;?>>
                    <?PHP
					/*
					if (!isset($_POST['diav']))
					$diavvalor = $_POST['dia']+1;
					else
					$diavvalor = $_POST['diav'];
					*/
					$ultdiav = 29;
					if($mesvselect == '04' || $mesvselect == '06' || $mesvselect == '09' || $mesvselect == '11'){
						$ultdiav = 30;
					}elseif($mesvselect == '02'){
						$ultdiav = 29;
					}else{
						$ultdiav = 31;
					}
					$cont = 1;
                    while($cont <= $ultdiav){
                        if ($cont == $diavselect) $sel = ' selected="selected"'; else $sel = ' ';
                            echo '<option value="'.$cont.'" '.$sel.'>'.$cont.'</option>';
                        $cont++;
                    }
                    ?>
                </select>
                <select name="mesv" id="mesv" <?PHP echo $cambhor;?>>
                <option value="01" <?PHP if ($mesvselect== 1) echo ' selected="selected"';?>>Ene</option>
                <option value="02" <?PHP if ($mesvselect== 2) echo ' selected="selected"';?>>Feb</option>
                <option value="03" <?PHP if ($mesvselect== 3) echo ' selected="selected"';?>>Mar</option>
                <option value="04" <?PHP if ($mesvselect== 4) echo ' selected="selected"';?>>Abr</option>
                <option value="05" <?PHP if ($mesvselect== 5) echo ' selected="selected"';?>>May</option>
                <option value="06" <?PHP if ($mesvselect== 6) echo ' selected="selected"';?>>Jun</option>
                <option value="07" <?PHP if ($mesvselect== 7) echo ' selected="selected"';?>>Jul</option>
                <option value="08" <?PHP if ($mesvselect== 8) echo ' selected="selected"';?>>Ago</option>
                <option value="09" <?PHP if ($mesvselect== 9) echo ' selected="selected"';?>>Sep</option>
                <option value="10" <?PHP if ($mesvselect== 10) echo ' selected="selected"';?>>Oct</option>
                <option value="11" <?PHP if ($mesvselect== 11) echo ' selected="selected"';?>>Nov</option>
                <option value="12" <?PHP if ($mesvselect== 12) echo ' selected="selected"';?>>Dic</option>
                </select>
                <select name="aniov" id="select3" <?PHP echo $cambhor;?>>
                    <?PHP
                    echo '<option value="'.date("Y").'" '.optSel($aniovselect,date("Y")).'>'.date("Y").'</option>';
                    $aniosig = $anioselect + 1;
                    echo '<option value="'.$aniosig.'" '.optSel($aniosig,$aniovselect).'>'.$aniosig.'</option>';
                    ?>
                </select>
            </div>
        </div>
     <?PHP } ?>
    <input type="hidden" name="enviar" value="Buscar" />
    <input type="hidden" name="repeti" id="repeti" value="<?PHP echo $repetir;?>" />
    <button type="enviar" name="enviar" value="Buscar"><span class="icon icon198"></span><span class="labeled">Buscar</span></button>
</form>