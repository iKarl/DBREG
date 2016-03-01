<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/libs/
 * @version $Id: class.Functions.php 1.0 2011-09-13 21:31 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * Funciones
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
use SimpleMVC\Model\Model_Controller as Model;

class Functions extends Model
{

	/**
	 * 
	 * Metodo que lista los idiomas
	 * 
	 * @param str $claveIdioma ID del idioma para determinar el idioma actual
	 * @param array $disabled Array con las opciones a deshabilitar
	 */
	public function listaIdiomasSeleccion($claveIdioma = '', $disabled = array())
	{
		$qry = "SELECT idio_idIdioma, idio_clave, idio_nombre 
			FROM smc_catIdiomas 
			WHERE 1 
			ORDER BY idio_clave ASC
		";

		$opciones = '<option value="">Seleccione:</option>';

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() > 0)
			{

				while ($idioma = $this->db->hFetchObject())
				{

					$opciones .= '<option value="' . $idioma->idio_clave . '"';
					$opciones .= ($idioma->idio_clave == $claveIdioma) ? ' selected="selected"' : '';

					// deshabilitar opciones
					if (is_array($disabled) && array_key_exists($idioma->idio_clave, $disabled))
					{
						$opciones .= ($disabled[$idioma->idio_clave]) ? ' disabled="disabled"' : '';
					}

					$opciones .= '>' . $idioma->idio_nombre . '</option>';

				}

				$this->db->hFree();

			}
			else
			{

				$opciones .= '<option value="">Idiomas no disponibles</option>';

			}

		}

		return $opciones;
	} // listaPaisesSeleccion

	/**
	 * 
	 * Metodo que lista los paises
	 * 
	 * @param Int $idPais ID del país para determinar el país actual
	 */
	public function getPaises($idPais = 0, $lang = "es")
	{
		$paises = array();

		$qry = "SELECT pais_idPais, pais_nombreEs 
			FROM smc_catPaises 
			WHERE 1 
			ORDER BY pais_nombreES ASC
		";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() > 0)
			{
				while ($pais = $this->db->hFetchObject())
				{
					$paises[] = $pais;
				}
				$this->db->hFree();
			}
		}

		return $paises;
	} // listaPaisesSeleccion

	public function getNombrePais($idPais)
	{
		$idPais = $this->db->hEscapeString($idPais);

		$qry = sprintf(
			"SELECT pais_iso3, pais_nombreEs, pais_nombreEn, pais_imagen " .
			"FROM smc_catPaises " .
			"WHERE pais_idPais = %d",
			$idPais
		);

		if ($this->db->hQuery($qry))
		{

			if ($pais = $this->db->hFetchObject())
			{

				return $pais;

				$this->db->hFree();

			}

		}
	}

	/**
	 * 
	 * Lista los status de disponibilidad
	 * @param Int $idStatus ID para determinar el status actual
	 */
	public function listaStatusDisp($idStatus = 0)
	{
		$qry = "SELECT ctsd_idStatus, ctsd_nombre, ctsd_descripcion 
			FROM int_catStatusDisponibilidad 
			WHERE 1 
			ORDER BY ctsd_nombre ASC
		";

		$opciones = '<option value="">Seleccione:</option>';

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() > 0)
			{

				while ($status = $this->db->hFetchObject())
				{

					$opciones .= '<option value="' . $status->ctsd_idStatus . '" title="' . $status->ctsd_descripcion . '"';
					$opciones .= ($status->ctsd_idStatus == $idStatus) ? ' selected="selected" ' : '';
					$opciones .= '>' . $status->ctsd_nombre . '</option>';

				}

				$this->db->hFree();

			}

		}
		else
		{

			$opciones .= '<option value="">Error en la consulta de status</option>';

		}

		return $opciones;
	}

	/**
	 * 
	 * Metodo que lista los tipos de monedas
	 * 
	 * @param int $idDivisa
	 */
	public function getDivisas($claveDivisa = "")
	{
		$divisas = array();

		$qry = "SELECT dvs_idDivisa, dvs_clave, dvs_nombre, dvs_simbolo 
			FROM smc_catDivisas 
			WHERE 1 
			ORDER BY dvs_clave ASC
		";

		if ($this->db->hQuery($qry))
		{
			if ($this->db->hNumRows() > 0)
			{
				while ($divisa = $this->db->hFetchObject())
				{
					$divisas[] = $divisa;
				}
				$this->db->hFree();
			}
		}

		return $divisas;
	}

	/**
	 * Metodo que remplaza las comas para insertar en la base de datos
	 *
	 * @param string $divisa
	 */
	public function moneda2db($divisa = 0)
	{
		if (empty($divisa))
		{
			$divisa = "0.00";
		}
		else
		{
			$divisa = str_replace(",", "", $divisa);
		}

		return $divisa;
	}

	/**
	 * Metodo que da formato de moneda para salida en pantalla
	 *
	 * @param string $divisa
	 */
	public function moneda2screen($divisa = 0)
	{
		return number_format($divisa, 2, ".", ",");
	}

	/**
	 * Metodo que da formato a una fecha ara ingresar a la base de datos
	 *
	 * @param string $fecha
	 */
	public function date2db($fecha = "", $hora = true)
	{
		if ($fecha != "")
		{
			$fecha = str_replace(array("/", "-", ".", ","), "|", $fecha);

			$conHora = strpos($fecha, " ");

			// Si viene con la hora
			if (($conHora !== false) && $hora)
			{
				$fecha = explode(" ", $fecha);

				// $fecha[0] fecha
				// $fecha[1] hora

				$fecha['dmy'] = explode("|", $fecha[0]);

				list($dia, $mes, $anio) = $fecha['dmy'];

				$fecha = $anio . "-" . $mes . "-" . $dia . " " . $fecha[1];
			}
			else
			{
				if ($conHora !== false)
				{
					$fecha = explode(" ", $fecha);
					$fecha = $fecha[0];
				}

				$fecha = explode("|", $fecha);

				list($dia, $mes, $anio) = $fecha;

				$fecha = $anio . "-" . $mes . "-" . $dia;
			}

		}
		else
		{
			if ($fecha === false)
			{
				$fecha = "Y-m-d";
			}
			else
			{
				$fecha = "Y-m-d";
				$fecha .= " H:i:s";
			}

			$fecha = date($fecha);
		}

		return $fecha;
	}

	/**
	 * Metodo que da formato a una fecha para mostrar por pantalla
	 *
	 * @param string $fecha
	 */
	public function date2screen($fecha = "", $hora = true, $sp = " ")
	{
		if ($fecha != "" && !is_bool($fecha))
		{
			$fecha = str_replace(array("-", ".", ","), "|", $fecha);

			$conHora = strpos($fecha, " ");

			// Si viene con la hora
			if (($conHora !== false) && $hora)
			{
				$fecha = explode(" ", $fecha);

				// $fecha[0] fecha
				// $fecha[1] hora

				$fecha['ymd'] = explode("|", $fecha[0]);

				list($anio, $mes, $dia) = $fecha['ymd'];

				if ($sp != " ")
				{
					$sp = addslashes($sp);
				}

				$fecha = $dia . "/" . $mes . "/" . $anio . $sp . $fecha[1];
			}
			else
			{

				if ($conHora !== false)
				{
					$fecha = explode(" ", $fecha);
					$fecha = $fecha[0];
				}

				$fecha = explode("|", $fecha);

				list($anio, $mes, $dia) = $fecha;

				$fecha = $dia . "/" . $mes . "/" . $anio;
			}

		}
		else if ($fecha == "")
		{
			$fecha = "";
		}
		else
		{

			if ($fecha === false)
			{
				$fecha = "d/m/Y";
			}
			else
			{
				$fecha = "d/m/Y";
				$fecha .= addslashes($sp) . "H:i:s";
			}

			$fecha = date($fecha);

		}

		return $fecha;
	}

	public function getNombreUsuario($id)
	{
		$nombre = "";
		$id = $this->db->hEscapeString($id);

		$qry = "SELECT usr_nombre, usr_app, usr_apm 
			FROM smc_usuarios 
			WHERE usr_idUsuario = %d
		";

		$qry = sprintf($qry, $id);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{
				$usuario = $this->db->hFetchObject();

				$nombre = $usuario->usr_nombre . " " . $usuario->usr_app;

				$nombre .= ($usuario->usr_apm) ? " " . $usuario->usr_apm : '';

			}

		}

		return $nombre;
	}

	/**
	 * generarID
	 *
	 * Funcion que regresa una llave aleatoria.-
	 *
	 * @param int $length
	 */
	public function generarID( $length = 16 )
	{
		$key = "";
		$pattern = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		for($i = 0 ; $i < $length ; $i++ )
		{
			$key .= $pattern[rand(0,61)];
		}

		return $key;
	}

	public function generarTagRFID($idRegistro)
	{
		//$dec = str_pad($i, 24, '0', STR_PAD_LEFT);

		$hex = sprintf('%023d', $idRegistro);

		return 'A' . $hex;
	}

	public function getGeneros($g = "", $idioma = 'es')
	{
		$generos = array(
			array("gen_clave" => "F", "gen_nombre" => "Femenino"),
			array("gen_clave" => "M", "gen_nombre" => "Masculino")
		);

		return $generos;
	}

	public function getGenero($genero)
	{

		return ($genero == "F") ? 'Femenino' : 'Masculino';
	}

	public function limpiarStr($cadena)
	{
		return trim($cadena);
	}

	public function capitalizarStr($cadena)
	{
		$cadena = $this->limpiarStr($cadena);
		return mb_convert_case($cadena, MB_CASE_TITLE, "UTF-8");
	}

	public function mayusStr($cadena)
	{
		$cadena = $this->limpiarStr($cadena);
		return mb_convert_case($cadena, MB_CASE_UPPER, "UTF-8");
	}

	/**
	 * Funcion que combierte una fecha dada a timestamp
	 */
	public function fechaATimestamp($fecha = '')
	{

	 	if (empty($fecha))
	 	{
	 		return time();
	 	}
	 	else
	 	{
	 		return strtotime($fecha);
	 	}

	}
	
	public function nombreImagenBarcode($idRegistro, $int = 5)
	{
		return str_pad($idRegistro, $int, "0", STR_PAD_LEFT);
	}

	public function timer()
	{
		$tiempo_inicio = microtime(true);

		$tiempo_fin = microtime(true);

		echo "Tiempo empleado: " . round($tiempo_fin - $tiempo_inicio, 2);
	}

	public function replace_tildes($string)
	{
		$string = preg_replace("[äáàâãª]","a",$string);
		$string = preg_replace("[ÁÀÂÃÄ]","A",$string);
		$string = preg_replace("[ÍÌÎÏ]","I",$string);
		$string = preg_replace("[íìîï]","i",$string);
		$string = preg_replace("[éèêë]","e",$string);
		$string = preg_replace("[ÉÈÊË]","E",$string);
		$string = preg_replace("[óòôõöº]","o",$string);
		$string = preg_replace("[ÓÒÔÕÖ]","O",$string);
		$string = preg_replace("[úùûü]","u",$string);
		$string = preg_replace("[ÚÙÛÜ]","U",$string);
		$string = preg_replace("[^´`¨~]","",$string);
		$string = str_replace("ç","c",$string);
		$string = str_replace("Ç","C",$string);
		$string = str_replace("ñ","n",$string);
		$string = str_replace("Ñ","N",$string);
		$string = str_replace("Ý","Y",$string);
		$string = str_replace("ý","y",$string);

		return $string;
	}

	/*!
	  @function num2letras ()
	  @abstract Dado un n?mero lo devuelve escrito.
	  @param $num number - N?mero a convertir.
	  @param $fem bool - Forma femenina (true) o no (false).
	  @param $dec bool - Con decimales (true) o no (false).
	  @result string - Devuelve el n?mero escrito en letra.
	*/ 
	public function num2letras($num, $fem = false, $dec = true)
	{
	   $matuni[2]  = "dos"; 
	   $matuni[3]  = "tres"; 
	   $matuni[4]  = "cuatro"; 
	   $matuni[5]  = "cinco"; 
	   $matuni[6]  = "seis"; 
	   $matuni[7]  = "siete"; 
	   $matuni[8]  = "ocho"; 
	   $matuni[9]  = "nueve"; 
	   $matuni[10] = "diez"; 
	   $matuni[11] = "once"; 
	   $matuni[12] = "doce"; 
	   $matuni[13] = "trece"; 
	   $matuni[14] = "catorce"; 
	   $matuni[15] = "quince"; 
	   $matuni[16] = "dieciseis"; 
	   $matuni[17] = "diecisiete"; 
	   $matuni[18] = "dieciocho"; 
	   $matuni[19] = "diecinueve"; 
	   $matuni[20] = "veinte"; 
	   $matunisub[2] = "dos"; 
	   $matunisub[3] = "tres"; 
	   $matunisub[4] = "cuatro"; 
	   $matunisub[5] = "quin"; 
	   $matunisub[6] = "seis"; 
	   $matunisub[7] = "sete"; 
	   $matunisub[8] = "ocho"; 
	   $matunisub[9] = "nove"; 

	   $matdec[2] = "veint"; 
	   $matdec[3] = "treinta"; 
	   $matdec[4] = "cuarenta"; 
	   $matdec[5] = "cincuenta"; 
	   $matdec[6] = "sesenta"; 
	   $matdec[7] = "setenta"; 
	   $matdec[8] = "ochenta"; 
	   $matdec[9] = "noventa"; 
	   $matsub[3]  = 'mill'; 
	   $matsub[5]  = 'bill'; 
	   $matsub[7]  = 'mill'; 
	   $matsub[9]  = 'trill'; 
	   $matsub[11] = 'mill'; 
	   $matsub[13] = 'bill'; 
	   $matsub[15] = 'mill'; 
	   $matmil[4]  = 'millones'; 
	   $matmil[6]  = 'billones'; 
	   $matmil[7]  = 'de billones'; 
	   $matmil[8]  = 'millones de billones'; 
	   $matmil[10] = 'trillones'; 
	   $matmil[11] = 'de trillones'; 
	   $matmil[12] = 'millones de trillones'; 
	   $matmil[13] = 'de trillones'; 
	   $matmil[14] = 'billones de trillones'; 
	   $matmil[15] = 'de billones de trillones'; 
	   $matmil[16] = 'millones de billones de trillones'; 
	   
	   //Zi hack
	   $float=explode('.',$num);
	   $num=$float[0];

	   $num = trim((string)@$num); 
	   if ($num[0] == '-') { 
	      $neg = 'menos '; 
	      $num = substr($num, 1); 
	   }else 
	      $neg = ''; 
	   while ($num[0] == '0') $num = substr($num, 1); 
	   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
	   $zeros = true; 
	   $punt = false; 
	   $ent = ''; 
	   $fra = ''; 
	   for ($c = 0; $c < strlen($num); $c++) { 
	      $n = $num[$c]; 
	      if (! (strpos(".,'''", $n) === false)) { 
	         if ($punt) break; 
	         else{ 
	            $punt = true; 
	            continue; 
	         } 

	      }elseif (! (strpos('0123456789', $n) === false)) { 
	         if ($punt) { 
	            if ($n != '0') $zeros = false; 
	            $fra .= $n; 
	         }else 

	            $ent .= $n; 
	      }else 

	         break; 

	   } 
	   $ent = '     ' . $ent; 
	   if ($dec and $fra and ! $zeros) { 
	      $fin = ' coma'; 
	      for ($n = 0; $n < strlen($fra); $n++) { 
	         if (($s = $fra[$n]) == '0') 
	            $fin .= ' cero'; 
	         elseif ($s == '1') 
	            $fin .= $fem ? ' una' : ' un'; 
	         else 
	            $fin .= ' ' . $matuni[$s]; 
	      } 
	   }else 
	      $fin = ''; 
	   if ((int)$ent === 0) return 'Cero ' . $fin; 
	   $tex = ''; 
	   $sub = 0; 
	   $mils = 0; 
	   $neutro = false; 
	   while ( ($num = substr($ent, -3)) != '   ') { 
	      $ent = substr($ent, 0, -3); 
	      if (++$sub < 3 and $fem) { 
	         $matuni[1] = 'una'; 
	         $subcent = 'as'; 
	      }else{ 
	         $matuni[1] = $neutro ? 'un' : 'uno'; 
	         $subcent = 'os'; 
	      } 
	      $t = ''; 
	      $n2 = substr($num, 1); 
	      if ($n2 == '00') { 
	      }elseif ($n2 < 21) 
	         $t = ' ' . $matuni[(int)$n2]; 
	      elseif ($n2 < 30) { 
	         $n3 = $num[2]; 
	         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
	         $n2 = $num[1]; 
	         $t = ' ' . $matdec[$n2] . $t; 
	      }else{ 
	         $n3 = $num[2]; 
	         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
	         $n2 = $num[1]; 
	         $t = ' ' . $matdec[$n2] . $t; 
	      } 
	      $n = $num[0]; 
	      if ($n == 1) { 
	         $t = ' ciento' . $t; 
	      }elseif ($n == 5){ 
	         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
	      }elseif ($n != 0){ 
	         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
	      } 
	      if ($sub == 1) { 
	      }elseif (! isset($matsub[$sub])) { 
	         if ($num == 1) { 
	            $t = ' mil'; 
	         }elseif ($num > 1){ 
	            $t .= ' mil'; 
	         } 
	      }elseif ($num == 1) { 
	         $t .= ' ' . $matsub[$sub] . '?n'; 
	      }elseif ($num > 1){ 
	         $t .= ' ' . $matsub[$sub] . 'ones'; 
	      }   
	      if ($num == '000') $mils ++; 
	      elseif ($mils != 0) { 
	         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
	         $mils = 0; 
	      } 
	      $neutro = true; 
	      $tex = $t . $tex; 
	   } 
	   $tex = $neg . substr($tex, 1) . $fin; 
	   //Zi hack --> return ucfirst($tex);
	   $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
	   return $end_num; 
	}
}
?>