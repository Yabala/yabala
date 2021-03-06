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



class FORMATS{



	//Valores del dominio de FORMATS
	//const _DOMAIN_ = array ("APPLICATION", "AUDIO", "EXAMPLE", "IMAGE", "MESSAGE", "MODEL", "MULTIPART", "TEXT", "VIDEO");
	//_DOMAIN_ deber�a declararse como una constante pero PHP no admite constantes que sean arreglos
	

	
	//RECIBE:	Nada
	//RETORNA:	Array de InternetMediaType
	//NOTA:		Retorna todos los valores del dominio de FORMATS
	public static function getDomain(){
		//_DOMAIN_ deber�a declararse como una constante pero PHP no admite constantes que sean arreglos
		//este m�todo deber�a retornar: self::_DOMAIN_
		return array ("APPLICATION", "AUDIO", "EXAMPLE", "IMAGE", "MESSAGE", "MODEL", "MULTIPART", "TEXT", "VIDEO");
	}

	//RECIBE:	InternetMediaType
	//RETORNA:	Boolean
	//NOTA:		Retorna TRUE si $value pertenece al dominio de FORMATS y FALSE en caso contrario
	public static function is($value){
		if (in_array($value, self::getDomain(), TRUE)) {
		    return TRUE;   
		}
		return FALSE;
	}



}

?>


