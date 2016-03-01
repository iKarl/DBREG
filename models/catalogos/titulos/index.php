<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/catalogos/titulos/
 * @version $Id: index.php 1.0 2011-04-08 00:22 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com, son_gohan_khan@hotmail.com>
 * 
 * Modelo
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{

	/**
	 * 
	 * Traer titulos
	 */
	public function traerTitulos($id = 0)
	{

		$titulos = array();
		$where = "";

		if ($id)
		{
			$id = $this->db->hEscapeString($id);
			$where = " WHERE ttl_idTitulo = " . $id;
		}

		$qry = "SELECT t.ttl_idTitulo, t.ttl_clave, t.ttl_nombre, t.ttl_idioma, i.idio_nombre
			FROM smc_catTitulos AS t 
			JOIN smc_catIdiomas AS i ON (i.idio_clave = t.ttl_idioma) 
			%s
			ORDER BY i.idio_nombre ASC";

		$qry = sprintf($qry, $where);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() >= 1)
			{

				while ($titulo = $this->db->hFetchObject())
				{

					$titulos[] = $titulo;

				}

			}
			else
			{

				return 0;

			}

		}
		else
		{

			return false;

		}

		return $titulos;

	} 

	/**
	 * 
	 * Buscar titulo existente
	 */
	public function existeTitulo($clave, $nombre, $idTitulo = 0)
	{

		$and = "";
		$clave = $this->db->hEscapeString($clave);
		$nombre = $this->db->hEscapeString($nombre);

		if ($idTitulo)
		{
			$idTitulo = $this->db->hEscapeString($idTitulo);
			$and = " AND ttl_idTitulo <> $idTitulo";
		}

		$qry = "SELECT ttl_idTitulo 
			FROM smc_catTitulos 
			WHERE (ttl_clave = '%s' 
			OR ttl_nombre = '%s')
			%s";

		$qry = sprintf($qry, $clave, $nombre, $and);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hNumRows() == 1)
			{

				$this->db->hFree();

				return true;

			}

		}

		return false;

	}

	/**
	 * 
	 * Inserta un nuevo titulo
	 * 
	 * @param unknown_type $clave
	 * @param unknown_type $nombre
	 */
	public function insertar($idioma, $clave, $nombre, $idUsuario)
	{

		$idioma = $this->db->hEscapeString($_POST['ttl_idioma']);
		$clave = $this->db->hEscapeString($_POST['ttl_clave']);
		$nombre = $this->db->hEscapeString($_POST['ttl_nombre']);

		$qry = "INSERT INTO smc_catTitulos SET 
			ttl_idioma = '%s',
			ttl_clave = '%s',
			ttl_nombre = '%s',
			ttl_fechaAlta = NOW(),
			ttl_usuarioAlta = %d";

		$qry = sprintf($qry, $idioma, $clave, $nombre, $idUsuario);

		if ($this->db->hQuery($qry))
		{

			if ($this->db->hAffectedRows() == 1)
			{

				return $this->db->hInsertID();

			}

		}

		return false;

	}

	public function editar($idioma, $clave, $nombre, $idTitulo, $idUsuario)
	{

		$idTitulo = $this->db->hEscapeString($idTitulo);
		$idioma = $this->db->hEscapeString($idioma);
		$clave = $this->db->hEscapeString($clave);
		$nombre = $this->db->hEscapeString($nombre);

 		$qry = "UPDATE smc_catTitulos SET 
 			ttl_idioma = '%s', 
 			ttl_clave = '%s', 
 			ttl_nombre = '%s',
 			ttl_fechaModificacion = NOW(),
 			ttl_usuarioModifico = %d 
 			WHERE ttl_idTitulo = %d 
 			LIMIT 1";

 		$qry = sprintf($qry, $idioma, $clave, $nombre, $idUsuario, $idTitulo);

 		if ($this->db->hQuery($qry))
 		{

 			if ($this->db->hAffectedRows() == 1)
 			{

 				return true;

 			}

 		}

 		return false;

	}

}
?>