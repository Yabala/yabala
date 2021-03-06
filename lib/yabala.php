<?php



// This file is part of Yabala https://github.com/Yabala/yabala
//
// Yabala is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Yabala is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Yabala.  If not, see <http://www.gnu.org/licenses/>.


include_once("op.php");
include_once("db5.php");
include_once("formats.php");
include_once("elcc.php");
include_once("excepcion.php");


class yabala implements iyabala{

	//Conjunto de materiales
	var $op;
	
	
	
	//Constructor de la clase
	function __construct() {
	       $this->op = new OP();
   	}

	//RECIBE:	Nada
	//RETORNA:	Collection
	//NOTA:		Devuelve el conjunto de materiales
	public function getOP(){
		return $this->op;
	}



	//RECIBE:	Nada
	//RETORNA:	Array of String
	//NOTA:		Devuelve un array con los valores del dominio de FORMAT
	public function getFormats(){
		//Retorna el resultado
		return FORMATS::getDomain();
	}



	//RECIBE:	Nada
	//RETORNA:	Array of String
	//NOTA:		Devuelve un array con los valores del dominio del ELCC
	public function getLicenses(){
		//Retorna el resultado
		return ELCC::getDomain();
	}



	//RECIBE:	Nada
	//RETORNA:	Array of String
	//NOTA:		Devuelve un array con los valores del dominio de EXCEPCION
	public function getExceptions(){
		//Retorna el resultado
		return EXCEPCION::getDomain();
	}



	//RECIBE:	Tag
	//RETORNA:	Array de Tag
	//NOTA:		Retorna todos los valores del ELCC que podr�an ser licencias para la adaptaci�n de un material con licencia $cc
	public function adaptation($cc){
		return ELCC::a($cc);
	}


	
	//RECIBE:	String, String, String, String, Tag, Boolean, Boolean, String
	//RETORNA:	String
	//NOTA:		Agrega el material con datos $format, $keywords, $author, $url, $cc, $modify, $exception, $excepcion al conjunto de materiales op
	//		Si no puede agregar la obra retorna un string distinto de vac�o
	public function add($title, $format, $keywords, $author, $url, $cc, $modify, $exception, $excepcion){

		//Chequear condiciones
		$msg = "";

		//RESTRICCIONES
		
		//$title no tiene restricciones 
		
		//$format debe pertenecer a FORMATS
		if (!(FORMATS::is($format))) $msg = $msg."[Formato desconocido] ";
		
		//$keywords no tiene restricciones
		
		//$author puede estar no definido solo si $cc=pd|cc0
		if (($author=="")&&(ELCC::author($cc))) $msg = $msg."[El autor debe estar definido con una licencia $cc] ";
		
		//$url no tiene restricciones
		
		//$cc debe pertenecer al ELCC
		if (!(ELCC::is($cc))) $msg = $msg."[Licencia $cc desconocida] ";
		
		//$modify es admitida solo si $cc=pd|cc0|BY|BY-SA|BY-NC|BY-NC-SA
		if (($modify)&&(ELCC::modify($cc))) $msg = $msg."[La licencia $cc no admite modificaciones] ";		
		
		//Si $cc=BY-ND|BY-NC-ND|CR es admitido solo si $exception es TRUE 
		if (!($exception)&&(ELCC::exception($cc))) $msg = $msg."[La licencia $cc no admite integrar conjuntos de materiales si no es de forma excepcional] ";		
		
		//Si $cc=PD entonces debe exigirse el autor o el t�tulo o la url
		
		
		//Si $exception==true entonces $excepcion debe ser una excepci�n 
		if (($exception)&&(!EXCEPCION::is($excepcion))) $msg = $msg."[Si la obra es una exepci�n se debe elegir un tipo de excepci�n] ";		

		//Si $exception==false entonces $excepcion==""
		if ((!$exception)&&($excepcion!="")) $msg = $msg."[Si la obra no es una exepci�n, el tipo de excepci�n no puede tener un valor asociado diferente de vac�o] ";		
		
		
		//crear el oc
		if ($msg=="") {//si cumple todas las condiciones crea el oc		
			$this->op->add($title, $format, $keywords, $author, $url, $cc, $modify, $exception, $excepcion);
		}
		
		return $msg;
	}

	//RECIBE:	Integer
	//RETORNA:	Nada
	//NOTA:		Quita el material con identificador $id del conjunto de materiales op
	public function del($id){
		$this->op->del($id);
	}

	//RECIBE:	Nada
	//RETORNA:	Array de Tag
	//NOTA:		Retorna todos los valores del ELCC que podr�an ser licencias para el conjunto de materiales op
	public function calculator(){
		return $this->op->calculator();
	}

	//RECIBE:	Nada
	//RETORNA:	Tag
	//NOTA:		Retorna el m�ximo valor del ELCC que podr�an ser licencias para el conjunto de materiales op
	public function calculatorMax(){
		return $this->op->calculatorMax();
	}

	//RECIBE:	Nada
	//RETORNA:	Tag
	//NOTA:		Retorna el m�nimo valor del ELCC que podr�an ser licencias para el conjunto de materiales op
	public function calculatorMin(){
		return $this->op->calculatorMin();
	}

	//RECIBE:	String, String, String, String, Array of elements
	//RETORNA:	Array of String
	//NOTA:		EN ESTA VERSI�N $options no se usa
	//		Retorna un array de strings que contienen:
	//		[0] String vac�o si se crear�n los cr�ditos, sino trae un mensaje 
	//		[1] La URL de la p�gina HTML con los cr�ditos del conjunto de materiales op ($name es usado para identificar el archivo creado)
	//		[2] La URL de la imagen QR con los cr�ditos  del conjunto de materiales op ($name es usado para identificar el archivo creado)
	//		[3] La URL de la imagen QR con la licencia  del conjunto de materiales op  ($name es usado para identificar el archivo creado)
	//		[4] La URL de la imagen Creative Commons con la licencia  del conjunto de materiales op  ($name es usado para identificar el archivo creado)
	//		[5] La URL ($collectionsUrl+name+png) de la imagen QR de la url de los cr�ditos del remix 
	//		[6] La URL al texto de la licencia, si no hay texto retorna null 
	public function credits($name, $cc, $title, $author, $options){
		
		//si la licencia $cc exige que el autor est� definido y $author="" retorna error
		if (($author=="")&&(ELCC::author($cc))) return array ("[El autor debe estar definido con una licencia $cc]", "", "", "", "");
		
		//si la licencia $cc es PD est� definido y $author="" retorna error
		if ($cc=="PD") return array ("[El autor no puede optar por una licencia $cc, si lo que desea es renunciar a todos sus derechos sobre la obra, elija una licencia CC0]", "", "", "", "");				
		
		return $this->op->credits($name, $cc, $title, $author, self::licensesUrl, self::licensesPath, self::collectionsPath, self::collectionsUrl, self::exceptionUrl);
	}

	//RECIBE:	Nada
	//RETORNA:	Array of Array of String
	//NOTA:		Retorna un array con los componentes de cada material del conjunto de materiales op como un array de strings 
	public function getWorks(){
		return $this->op->printOP();
	}

	//RECIBE:	Nada
	//RETORNA:	String
	//NOTA:		Retorna un string con el t�tulo del conjunto de materiales 
	public function getTitle(){
		return $this->op->title;
	}

	//RECIBE:	Nada
	//RETORNA:	String
	//NOTA:		Retorna un string con el autor(s) del conjunto de materiales 
	public function getAuthor(){
		return $this->op->author;
	}
	
	//RECIBE:	Nada
	//RETORNA:	String
	//NOTA:		Retorna un string con la licencia del conjunto de materiales 
	public function getCc(){
		return $this->op->cc;
	}

	//RECIBE:	String
	//RETORNA:	Nada
	//NOTA:		Recibe un string y lo setea como el t�tulo del conjunto de materiales 
	public function setTitle($title){
		$this->op->setTitle($title);
	}

	//RECIBE:	String
	//RETORNA:	Nada
	//NOTA:		Recibe un string y lo setea como el autor(s) del conjunto de materiales 
	public function setAuthor($author){
		$this->op->setAuthor($author);
	}

	//RECIBE:	String
	//RETORNA:	Nada
	//NOTA:		Recibe un string y lo setea como la licencia del conjunto de materiales 
	public function setCc($cc){
		$this->op->setCc($cc);
	}



	//RECIBE:	Nada
	//RETORNA:	Array of Array of String
	//NOTA:		Devuelve un array con los nombres y url de los respostiros registrado en la base apuntada por $repositoryListUrl
	public function getRepositoryList(){
		return DB5::getRepositoryList(self::repositoryListUrl);
	}

	//RECIBE:	String, String, String, String, String, String
	//RETORNA:	Nada
	//NOTA:		Agrega el RECORD ($title, $format, $keywords, $author, $url, $cc) a la base que est� en la ruta $dbPath
	//		$dbPath hace referencia a un path local, por ejemplo: "../yabala/db/dv.csv"
	public function insert($title, $format, $keywords, $author, $url, $cc){
		DB5::insert(self::dbPath, $title, $format, $keywords, $author, $url, $cc);
	}

	//RECIBE:	String, String, Integer, Integer
	//RETORNA:	Collection
	//NOTA:		Busca en la base de datos ubicada en la url $repositoryUrl, el string contenido en $key y retorna la colecci�n de registros resultado
	//		Si $i<0 busca si aparece $key en forma exacta o como sub-string en cualquier campo
	//		Si $i>=0 y $mode=0 busca si aparece $key en forma exacta o como sub-string dentro del campo $i
	//		Si $i>=0 y $mode=1 busca si aparece $key en forma exacta  dentro del campo $i
	//		$repositoryUrl es una url donde est� la base en la que se buscar�, por ejemplo: "http://misitio.com/db.csv
	public function select($repositoryUrl, $key, $i, $mode){
		return (DB5::select($repositoryUrl, $key, $i, $mode));
	}



	//RECIBE:	String, Array of elements
	//RETORNA:	Nada
	//NOTA:		EN ESTA VERSI�N $options no se usa
	//		Borra:
	//		La URL de la p�gina HTML con los cr�ditos del conjunto de materiales de nombre $nombre
	//		La URL de la imagen QR con los cr�ditos  del conjunto de materiales de nombre $nombre 
	//		La URL de la imagen QR con la licencia  del conjunto de materiales de nombre $nombre 
	//		La URL de la imagen Creative Commons con la licencia  del conjunto de materiales de nombre $nombre 
//	public function resetCredits($name, $options){
//		//definir nombres de archvios de cr�ditos
//		$nameHtml = $name.".html";
//		$nameQrfull = $name."_full.png";
//		$nameQrmin = $name."_min.png";
//		
//		//si el archivo ya existe lo borra
//		if (file_exists(self::collectionsPath.$nameHtml)){
//			unlink(self::collectionsPath.$nameHtml);
//		}
//
//		//si el archivo ya existe lo borra	
//		if (file_exists(self::collectionsPath.$nameQrfull)){
//			unlink(self::collectionsPath.$nameQrfull);
//		}
//
//		//si el archivo ya existe lo borra
//		if (file_exists(self::collectionsPath.$nameQrmin)){
//			unlink(self::collectionsPath.$nameQrmin);
//		}
//	}



}

?>
