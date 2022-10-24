<?php
function dinero($monto){
	$monto = str_replace(',', '', $monto);
 	return '$'.number_format($monto, 2, '.', ',').' MXN';
}