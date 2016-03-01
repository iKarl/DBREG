<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/catalogos/categorias/
 * @version $Id: index.php 1.0 2011-06-09 23:27 _Karl_ $;
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
	 * Lista las categorias
	 */
	public function traerCategorias($id = 0)
	{

		$where = "";
		$categorias = array();

		if ($id)
		{
			$id = $this->db->hEscapeString($id);
			$where = " WHERE ctg_idCategoria = " . $id;
		}

		$qry = "SELECT ctg_idCategoria, ctg_clave, ctg_nombre, ctg_idioma 
			FROM smc_catCategorias
			%s
		";

		$qry = sprintf($qry, $where);

		if ($this->db->hQUery($qry))
		{

			if ($this->db->hNumRows() >= 1)
			{

				while ($categoria = $this->db->hFetchObject())
				{

					$categorias[] = $categoria;

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

		return $categorias;

	} 

	/**
	 * 
	 * Buscar categoria existente
	 */
	public function existeCategoria($clave, $nombre, $idCategoria = 0)
	{

		$and = "";
		$clave = $this->db->hEscapeString($clave);
		$nombre = $this->db->hEscapeString($nombre);

		if ($idCategoria)
		{

			$idCategoria = $this->db->hEscapeString($idCategoria);
			$and = " AND ctg_idCategoria <> $idCategoria";

		}

		$qry = "SELECT ctg_idCategoria 
			FROM smc_catCategorias 
			WHERE (ctg_clave = '%s' 
			OR ctg_nombre = '%s')
			%s
		";

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
	 * Inserta categoria
	 * 
	 * @param unknown_type $clave
	 * @param unknown_type $nombre
	 */
	public function insertar($idioma, $clave, $nombre, $idUsuario)
	{

		$idioma = $this->db->hEscapeString($_POST['ctg_idioma']);
		$clave = $this->db->hEscapeString($_POST['ctg_clave']);
		$nombre = $this->db->hEscapeString($_POST['ctg_nombre']);

		$qry = "INSERT INTO smc_catCategorias SET 
			ctg_idioma = '%s',
			ctg_clave = '%s',
			ctg_nombre = '%s',
			ctg_fechaAlta = NOW(),
			ctg_usuarioAlta = %d
		";

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

	/**
	 * 
	 * Actualiza una categoria
	 * 
	 * @param string $idCategoria	 
	 * @param string $idioma
	 * @param string $clave
	 * @param string $nombre
	 * @param string $idUsuario	 
	 */
	public function editar($idCategoria, $idioma, $clave, $nombre, $idUsuario)
	{

		$idCategoria = $this->db->hEscapeString($idCategoria);
		$idioma = $this->db->hEscapeString($idioma);
		$clave = $this->db->hEscapeString($clave);
		$nombre = $this->db->hEscapeString($nombre);

 		$qry = "UPDATE smc_catCategorias SET 
 			ctg_idioma = '%s', 
 			ctg_clave = '%s', 
 			ctg_nombre = '%s', 
 			ctg_fechaModificacion = NOW(), 
 			ctg_usuarioModifico = %d 
 			WHERE ctg_idCategoria = %d 
 			LIMIT 1
 		";

 		$qry = sprintf($qry, $idioma, $clave, $nombre, $idUsuario, $idCategoria);

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