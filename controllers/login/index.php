<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 * @package: smc/controllers/login/
 * @version $Id: index.php 1.0 2012-01-30 21:31 _Karl_ $;
 * @author: Carlos A. García Hernández <carlos.agh@gmail.com>
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if (!defined("SimpleMVC")) { die("Not Access Direct"); }

use SimpleMVC\Controller\Base_Controller as Controller;

class Index_Controller extends Controller
{

	public function __construct()
	{

		$this->librarys("Sessions", "sess");

		$this->model = $this->model();

	}

	public function login()
	{

		$json = array();

		if (isset($_POST['username']) && !empty($_POST['username']) &&
			isset($_POST['password']) && !empty($_POST['password'])
		)
		{

			// Valida el usuario
			if ($this->model->validarUsuario($_POST['username']))
			{

				// Valida el password
				if ($this->model->validarPassword($_POST['password']))
				{

					if ($sesion = $this->model->validarSesion())
					{

						if ($sesion->usr_enSesion == 0 || $sesion->usr_enSesion == 1)
						{

							$tiempoSesion = date("Y-n-j H:i:s");

							// Actualiza los datos de sesion
							if ($user = $this->model->actualizarAcceso($tiempoSesion))
							{

								if ($user->usr_status == 1)
								{

									$this->sess->set(
										array(
											"user" => array(
												"logged_in" => true,
												"id" => $user->usr_idUsuario,
												"tiempoSesion" => $tiempoSesion
											)
										)
									);

									$json = array(
										"status" => true,
										"redirect" => "home/"
									);

								}
								else
								{

									$json = array(
										"status" => false,
										"message" => "Este usuario esta deshabilitado"
									);

								}

							}
							else
							{

								$json = array(
									"status" => false,
									"message" => "No es pocible iniciar sesion"
								);

							}

						}
						else
						{

							$json = array(
								"status" => false,
								"message" => "Este usuario ya a iniciado sesion en el sistema"
							);

						}

					}

				}
				else
				{

					$json = array(
						"status" => false,
						"message" => "La contraseña es incorrecta"
					);

				}

			}
			else
			{

				$json = array(
					"status" => false,
					"message" => "El usuario no existe"
				);

			}

		}
		else
		{

			$json = array(
				"status" => false,
				"message" => "Por favor, indique sus datos"
			);

		}

		echo json_encode($json);

	}

	public function logout()
	{

		if ($user = $this->sess->get('user'))
		{

			if ($this->model->cerrarSesion($user))
			{
				$this->sess->set('user');
				$_SESSION = array();
				session_destroy();
			}

			$logoutFor = "";

			if (isset($_GET['sess']) && $_GET['sess'] == "inactivity")
			{
				$logoutFor = "&session=inactivity";
			}

			header ("Location: ../?login=finish" . $logoutFor);

		}
		else
		{

			header ("Location: ../?login=required");

		}

	}

	public function recoveryPassword()
	{

		if (isset($_SESSION['user']['login']) && $_SESSION['user']['login'] == true)
		{

			header("Location: " . $root_path . "/home/");

			exit();

		}
		else
		{

			header("Location: " . $root_path . "/?action=disabled");

		}
		
	}

	public function inserton()
	{
		if (($gestor = fopen("controllers/login/LisatadoHoras_cardio_final.csv", "r")) !== FALSE)
		{
			while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE)
			{
				//$numero = count($datos);
				//$this->model->inserton($datos[0], $datos[1]);
			}

			fclose($gestor);
		}
	}
}
?>