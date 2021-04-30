<?php 

    // Variables de Fecha y Hora
    // Variable donde se guarda el tiempo total en segundos
    $tiempoTotal = time();
    // Se descompone la variable original "tiempoTotal" para determinar la fecha
    $fechaActual = date("d/M/Y",$tiempoTotal);
    // Se descompone la variable original "tiempoTotal" para determinar la hora
    $horaActual = date("H:i:s",$tiempoTotal);