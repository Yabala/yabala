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



include_once("lib/yabala.php");



interface iyabala{



	const collectionsUrl = "http://localhost/yabala/yabala/www/collections/";
	const collectionsPath = "../yabala/www/collections/";
	const licensesUrl = "http://localhost/yabala/yabala/www/licenses/";
	const licensesPath = "../yabala/www/licenses/";
	const exceptionUrl = "http://localhost/yabala/yabala/www/exceptions/";
	const exceptionPath = "../yabala/www/exception/";
	const repositoryListUrl = "http://localhost/yabala/yabala/db/list.csv";
	const dbPath = "../yabala/db/db.csv";
	

	
	//RECIBE:	Nada
	//RETORNA:	Collection
	//NOTA:		Devuelve el conjunto de materiales	
	public function getOP();



	//RECIBE:	Nada
	//RETORNA:	Array of String
	//NOTA:		Devuelve un array con los valores del dominio de FORMAT
	public function getFormats();



	//RECIBE:	Nada
	//RETORNA:	Array of String
	//NOTA:		Devuelve un array con los valores del dominio del ELCC
	public function getLicenses();

	//RECIBE:	Nada
	//RETORNA:	Array of String
	//NOTA:		Devuelve un array con los valores del dominio de EXCEPCION
	public function getExceptions();
	
	
	//RECIBE:	Tag
	//RETORNA:	Array de Tag
	//NOTA:		Retorna todos los valores del ELCC que podr�an ser licencias para la adaptaci�n de un material con licencia $cc
	public function adaptation($cc);


	
	//RECIBE:	String, String, String, String, Tag, Boolean, Boolean, String
	//RETORNA:	String
	//NOTA:		Agrega el material con datos $format, $keywords, $author, $url, $cc, $modify, $exception, $excepcion al conjunto de materiales op
	//		Si no puede agregar la obra retorna un string distinto de vac�o que informaci�n del error
	//
	//RESTRICCIONES:
	//$title	no tiene restricciones
	//$format	debe pertenecer a FORMATS
	//$keywords	no tiene restricciones
	//$author	puede estar no definido solo si $cc=pd|cc0
	//$url		no tiene restricciones
	//$cc		debe pertenecer al ELCC
	//$modify	es admitida como TRUE solo si $cc=pd|cc0|BY|BY-SA|BY-NC|BY-NC-SA
	//Si $cc=BY-ND|BY-NC-ND|CR es admitido solo si $exception es TRUE 
	public function add($title, $format, $keywords, $author, $url, $cc, $modify, $exception, $excepcion);

	//RECIBE:	Integer
	//RETORNA:	Nada
	//NOTA:		Quita el material con identificador $id del conjunto de materiales op
	public function del($id);

	//RECIBE:	Nada
	//RETORNA:	Array de Tag
	//NOTA:		Retorna todos los valores del ELCC que podr�an ser licencias para el conjunto de materiales op
	public function calculator();

	//RECIBE:	Nada
	//RETORNA:	Tag
	//NOTA:		Retorna el m�ximo valor del ELCC que podr�an ser licencias para el conjunto de materiales op
	public function calculatorMax();

	//RECIBE:	Nada
	//RETORNA:	Tag
	//NOTA:		Retorna el m�nimo valor del ELCC que podr�an ser licencias para el conjunto de materiales op
	public function calculatorMin();
	
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
	public function credits($name, $cc, $title, $author, $options);
	
	//RECIBE:	Nada
	//RETORNA:	Array of Array of String
	//NOTA:		Retorna un array con los componentes de cada material del conjunto de materiales op como un array de strings 
	public function getWorks();

	//RECIBE:	Nada
	//RETORNA:	String
	//NOTA:		Retorna un string con el t�tulo del conjunto de materiales 
	public function getTitle();

	//RECIBE:	Nada
	//RETORNA:	String
	//NOTA:		Retorna un string con el autor(s) del conjunto de materiales 
	public function getAuthor();

	//RECIBE:	Nada
	//RETORNA:	String
	//NOTA:		Retorna un string con la licencia del conjunto de materiales 
	public function getCc();

	//RECIBE:	String
	//RETORNA:	Nada
	//NOTA:		Recibe un string y lo setea como el t�tulo del conjunto de materiales 
	public function setTitle($title);

	//RECIBE:	String
	//RETORNA:	Nada
	//NOTA:		Recibe un string y lo setea como el autor(s) del conjunto de materiales 
	public function setAuthor($author);

	//RECIBE:	String
	//RETORNA:	Nada
	//NOTA:		Recibe un string y lo setea como la licencia del conjunto de materiales 
	public function setCc($cc);



	//RECIBE:	Nada
	//RETORNA:	Array of Array of String
	//NOTA:		Devuelve un array con los nombres y url de los respostiros registrado en la base apuntada por $repositoryListUrl
	public function getRepositoryList();
	
	//RECIBE:	String, String, String, String, String, String
	//RETORNA:	Nada
	//NOTA:		Agrega el RECORD ($title, $format, $keywords, $author, $url, $cc) a la base que est� en la ruta $dbPath
	//		$dbPath hace referencia a un path local, por ejemplo: "../yabala/db/dv.csv"
	public function insert($title, $format, $keywords, $author, $url, $cc);
	
	//RECIBE:	String, String, Integer, Integer
	//RETORNA:	Collection
	//NOTA:		Busca en la base de datos ubicada en la url $repositoryUrl, el string contenido en $key y retorna la colecci�n de registros resultado
	//		Si $i<0 busca si aparece $key en forma exacta o como sub-string en cualquier campo
	//		Si $i>=0 y $mode=0 busca si aparece $key en forma exacta o como sub-string dentro del campo $i
	//		Si $i>=0 y $mode=1 busca si aparece $key en forma exacta  dentro del campo $i
	//		$repositoryUrl es una url donde est� la base en la que se buscar�, por ejemplo: "http://misitio.com/db.csv
	public function select($repositoryUrl, $key, $i, $mode);
	
		
	
	//RECIBE:	String, Array of elements
	//RETORNA:	Nada
	//NOTA:		EN ESTA VERSI�N $options no se usa
	//		Borra:
	//		La URL de la p�gina HTML con los cr�ditos del conjunto de materiales de nombre $nombre
	//		La URL de la imagen QR con los cr�ditos  del conjunto de materiales de nombre $nombre 
	//		La URL de la imagen QR con la licencia  del conjunto de materiales de nombre $nombre 
	//		La URL de la imagen Creative Commons con la licencia  del conjunto de materiales de nombre $nombre 
	//public function resetCredits($name, $options);



}

?>
