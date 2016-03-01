<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/models/home/
 * @version $Id: index.php 1.0 2012-05-12 00:20 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Model\Model_Controller as Model;

class Index_Model extends Model
{
	public function prueba($post)
	{
		$qry = "INSERT INTO prueba SET 
			reader_name = '%s',
			mac_address = '%s',
			line_ending = '%s',
			field_delim = '%s',
			field_names = '%s',
			field_values = '%s',
			req_post = '%s'
		";
		$arr = print_r($post, true);

		$qry = sprintf($qry,
			$post['reader_name'],
			$post['mac_address'],
			$post['line_ending'],
			$post['field_delim'],
			$post['field_names'],
			$post['field_values'],
			$arr
		);

		if ($this->db->hQuery($qry))
		{
			return true;
		}
	}
}
?>