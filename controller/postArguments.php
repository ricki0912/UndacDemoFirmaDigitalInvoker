<?php
require('../util/config.php');


$type=$_POST['type'];
$documentName=$_POST['documentName'];


/*"dcfilter": ".*FIR.*|.*FAU.*", ".*FIR.*44627780.*",*/

//$array = array(
		    
	     //   'idRegister' => $ID_REGISTER,
	      //  'idFile'  => '002',
	      //  'type'  => 'L', /*L=Documento está en la PC , W=Documento está en la Web.*/
	     //   'protocol'  => $PROTOCOL, /*T=http, S=https (SE RECOMIENDA HTTPS)*/
	     //   'fileDownloadUrl'  =>'',
	     //   'fileDownloadLogoUrl'  =>$DIR_IMAGE.'iLogo1.png',
	     //   'fileDownloadStampUrl'  =>$DIR_IMAGE.'iFirma1.jpg',
	     //   'fileUploadUrl' =>$DIR_UPLOAD.'upload.php',                   
	     //   'contentFile' => '',
	     //   'reason' => 'Soy el autor del documento',
	     //   'isSignatureVisible' => 'true',            
	     //   'stampAppearanceId' => '0', /*0:(sello+descripcion) horizontal, 1:(sello+descripcion) vertical, 2:solo sello, 3:solo descripcion*/
	     //   'pageNumber' => '0',
	     //   'posx' => '5',
	     //   'posy' => '5',
	     //   'width' => '170',        
	     //   'fontSize' => '9' ,
	    //    'dcfilter' => '.*FIR.*|.*FAU.*', /*'.*' todos, solo fir '.*FIR.*|.*FAU.*'*/
	     //   'timestamp' => 'false',               
	     //   'outputFile' => $documentName,  
	      //  'maxFileSize' => '5242880' /*Por defecto será 5242880 5MB - Maximo 100MB*/
	    //);

		//echo  base64_encode( json_encode($array) );



if($type=="W")
{
$reason="";
$filter="";
$posx="";
$posy="";
$name_doc="";
$name_doc_u="";
$logo="";
if($_POST['select']=='DO'){
	$filter=$_POST['docente'];
	$reason="Soy el autor del documento";
	$posx="0";
	$posy="0";
	$name_doc=$_POST['cod'].'.pdf';
	$name_doc_u=$_POST['cod'].'-'.$_POST['docente'].'.pdf';
	$logo="logo-docente.png";
}else if ($_POST['select']=='DI'){
	$filter=$_POST['director'];
	$reason="En señal de conformidad";
	$posx="200";
	$posy="0";
	$name_doc=$_POST['cod'].'-'.$_POST['docente'].'.pdf';
	$name_doc_u=$_POST['cod'].'-'.$_POST['docente'].'-'.$_POST['director'].'.pdf';
	$logo="logo-director.png";
}


$param ='{
			"app":"pdf",
			"fileUploadUrl":"'.$FILEUPLOADURL.'",
			"reason":"'.$reason.'",
			"type":"W",
			"clientId":"'.$CLIENTID.'",
			"clientSecret":"'.$CLIENTSECRET.'",
			"dcfilter": ".*FAU.*'.$filter.'.*",
			"fileDownloadUrl":"'.$SERVER_PATH.'/documents/'.$name_doc.'",
			"posx":"'.$posx.'",
			"posy":"'.$posy.'",
			"outputFile":"'.$name_doc_u.'",
			"protocol":"T",
			"contentFile":"demo.pdf",
			"stampAppearanceId":"0",
			"isSignatureVisible":"true",
			"idFile":"MyForm",
			"fileDownloadLogoUrl":"'.$SERVER_PATH.'/resources/img/iLogo1.png",
			"fileDownloadStampUrl":"'.$SERVER_PATH.'/resources/img/'.$logo.'",
			"pageNumber":"0",
			"maxFileSize":"5242880",
			"fontSize":"7",			
			"timestamp":"false"
		}';

		echo  base64_encode($param);

}	


?>