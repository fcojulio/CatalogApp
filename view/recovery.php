<h2>Recuperar no sincronizado</h2>

<h3>Listado de pedidos en memoria(seleccionar para enviar a rma)</h3>

<?php 

	$directorio = opendir("./syn/"); //ruta actual
	while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
	{
	    if (is_dir($archivo))//verificamos si es o no un directorio
	    {
	        //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
	    }
	    else
	    {
	    	if (preg_match( "/bk_orders_/", $archivo) ){
	    		echo "fichero: " . $archivo . " <b><a href='./syn/".$archivo."' >fecha: " . date ("F d Y H:i", filemtime("./syn/".$archivo)) . "</a></b>" ."<br />";	
	    	}
	        
	    }
}