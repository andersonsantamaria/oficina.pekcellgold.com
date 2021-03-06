<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class comercial extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('bo/modelo_dashboard');
		$this->load->model('bo/general');
		$this->load->model('bo/modelo_comercial');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);

		$style=$this->modelo_dashboard->get_style($id);

		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);

		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/main_dashboard');
	}
	function oficina_virtual()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);

		$style=$this->modelo_dashboard->get_style($id);

		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$archivos=$this->modelo_comercial->get_files();
		$info=$this->modelo_comercial->get_info();
		$presentaciones=$this->modelo_comercial->get_presentaciones();
		$ebooks=$this->modelo_comercial->get_ebooks();
		$videos=$this->modelo_comercial->get_video();
		$eventos=$this->modelo_comercial->get_evento();
		$noticias=$this->modelo_comercial->get_new();
		$cupones=$this->modelo_comercial->get_cupon();
		$data=array();	
		$data['cupones']=$cupones;
		$data["noticias"]=$noticias;
		$data["eventos"]=$eventos;
		$data['videos']=$videos;
		$grupos=$this->modelo_comercial->get_groups();
		$data['grupos']=$grupos;
		$comentarios=$this->modelo_comercial->get_comments();
		$data['comentarios']=$comentarios;
		$data['ebooks']=$ebooks;
		$data["files"]=$archivos;
		$data['infos']=$info;
		$data["presentaciones"]=$presentaciones;
		$encuestas=$this->modelo_comercial->get_encuestas();
		$data['encuestas']=$encuestas;
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/oficina_virtual',$data);
	}
	function ver_noticia()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);

		$style=$this->modelo_dashboard->get_style($id);

		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$data=array();
		if(isset($_GET["idnw"]))
		{
			$data["noticia"]=$this->modelo_comercial->noticia_espec($_GET["idnw"]);
			$index=1;
			$parrafos=array();
			$i=0; 
			$texto=nl2br($data["noticia"][0]->contenido);
			while($index>0)
			{
				
				$index=strpos($texto, "<br />");
				$parrafos[$i]=substr($texto,0,$index);
				$texto=substr($texto,$index+6);
				$i++;
			}
			$data["parrafos"]=$parrafos;
		}
		
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/ov/header');
        $this->template->set_partial('footer', 'website/ov/footer');
		$this->template->build('website/bo/comercial/ver_noticia',$data);
	}
	function crear_encuesta()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$id=$this->tank_auth->get_user_id();
		$usuario=$this->general->get_username($id);

		$style=$this->modelo_dashboard->get_style($id);

		$this->template->set("usuario",$usuario);
		$this->template->set("style",$style);
		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/crear_encuesta');
	}
	function red()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$id          = $this->tank_auth->get_user_id();
		$style       = $this->modelo_dashboard->get_style($id);
		$red         = $this->modelo_comercial->get_red($id);
		$id_red      = $red[0]->id_red;
		$afiliados   = $this->modelo_comercial->get_afiliados_($id_red);
		$users       = $this->modelo_comercial->get_dato_usuario();
		$perfiles    = $this->modelo_comercial->get_perfiles();
		$permisos    = $this->modelo_comercial->get_permisos();
		$grupos      = $this->modelo_comercial->get_cat_grupo_perfil();
		$preregistro = $this->modelo_comercial->get_preregistro();


		$image=$this->modelo_comercial->get_images($id);
		$user="/template/img/empresario.jpg";
		foreach ($image as $img) {
			$cadena=explode(".", $img->img);
			if($cadena[0]=="user")
			{
				$user=$img->url;
			}
		}

		$this->template->set("user",$user);
		$this->template->set("style",$style);
		$this->template->set("id",$id);
		$this->template->set("afiliados",$afiliados);
		$this->template->set("users",$users);
		$this->template->set("img_perfil",$user);
		$this->template->set("perfiles",$perfiles);
		$this->template->set("permisos",$permisos);
		$this->template->set("grupos",$grupos);
		$this->template->set("preregistro",$preregistro);

		$this->template->set_theme('desktop');
        $this->template->set_layout('website/main');
        $this->template->set_partial('header', 'website/bo/header');
        $this->template->set_partial('footer', 'website/bo/footer');
		$this->template->build('website/bo/comercial/red');
	}
	function get_detalle_usuario()
	{
		$detalle=$this->modelo_comercial->get_detalle_usuario();
		$img= ($detalle[0]->url) ? $detalle[0]->url : '/template/img/empresario.jpg';
		echo '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
		echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><img src="'.$img.'" alt="<?=$user->username?>" style="max-height: 100%; max-width: 100%;"></div>
		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8"><div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Id: </div><strong>'.$detalle[0]->id.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Usuario:</div><strong>'.$detalle[0]->username.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Correo:</div><strong>'.$detalle[0]->email.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Estado:</div><strong>'.$detalle[0]->estatus_afiliado.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Estado civil:</div><strong>'.$detalle[0]->estado_civil.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Tipo:</div><strong>'.$detalle[0]->tipo_usuario.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Estudios:</div><strong>'.$detalle[0]->estudio.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Ocupacion:</div><strong>'.$detalle[0]->ocupacion.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Tiempo dedicado:</div><strong>'.$detalle[0]->tiempo_dedicado.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Co-aplicante:</div><strong>'.$detalle[0]->nombre_co.' '.$detalle[0]->apellido_co.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Nombre:</div><strong>'.$detalle[0]->nombre.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Apellido:</div><strong>'.$detalle[0]->apellido.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Nacimiento:</div><strong>'.$detalle[0]->fecha_nacimiento.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Edad:</div><strong>'.$detalle[0]->edad.'</strong></div>';
		echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px"><div class="col-xs-6">Última sesión:</div><strong>'.$detalle[0]->ultima_sesion.'</strong></div>';
		echo '</div></div>';
	}
	function detalle_red()
	{
		$misdatos        = $this->modelo_comercial->datos_perfil($_POST['id']);
		$sponsor         = $this->modelo_comercial->get_sponsor();
		$id_user         = $this->modelo_comercial->get_id_by_red($sponsor[0]->id_red);
		$afiliados       = $this->modelo_comercial->get_afiliados($sponsor[0]->id_red);
		$detalle_sponsor = $this->modelo_comercial->datos_perfil($id_user);

		echo '
		<div class="smart-form row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<fieldset>
					<legend>Datos del sponsor</legend>
					<div>Id: '.$id_user.'</div>
					<div>Nombre: '.$detalle_sponsor[0]->nombre." ".$detalle_sponsor[0]->apellido.'</div>
					<div>Correo: '.$detalle_sponsor[0]->email.'</div>
				</fieldset>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<fieldset>
					<legend>Datos de los afiliados</legend>';
				$ids=array();
				foreach ($afiliados as $key) {
					$ids[$key->id_afiliado]=$key->lado;
				echo '<div>Id: '.$key->id_afiliado.'</div>';
				echo '<div>Nombre: '.$key->afiliado." ".$key->afiliado_p.'</div>';
				echo '<div>Posicion: ';
				echo ($key->lado==0) ? 'Izquierda' : 'Derecha'; 
				echo '</div><hr />';
				}
		echo '</fieldset>
			</div>';

			echo'
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<fieldset id="edita_posicion">
				<legend>Editar</legend>
				<div>Id: '.$_POST['id'].'</div>
				<div>Nombre: '.$misdatos[0]->nombre." ".$misdatos[0]->apellido.'</div>
				<div>Correo: '.$misdatos[0]->email.'</div>
				<div>Posicion actual: '; echo ($sponsor[0]->lado==0) ? 'Izquierda' : 'Derecha'; 
				echo '<input name="mi_id" type="hidden" value="'.$_POST['id'].'">';
				foreach ($ids as $key)
					echo '<input name="posiciones[]" type="hidden" value="'.$key.'">';
				foreach (array_keys($ids) as $key)
					echo '<input name="ids[]" type="hidden" value="'.$key.'">';
				echo'</div>
				<br /><hr />
				<section class="col col-6">Selecciona la posicion para el usuario
					<label class="select">
						<select name="posicion">
							<option value="0">Izquierda</option>
							<option value="1">Derecha</option>
						</select>
					</label>
				</section>
			</fieldset></div>
		</div>';
	}
	function cambia_posicion()
	{
		$this->modelo_comercial->cambia_posicion();
		if(sizeof($_POST['ids'])>1)
			echo "Se han cambiado de posicion dos afiliados";
		else
			echo "Se ha cambiado de posicion al afiliado";
	}
	function perfil_permiso()
	{
		$perfiles=$this->modelo_comercial->get_perfiles();
		$perfil=$this->modelo_comercial->get_perfil_usuario();
		$grupo=$this->modelo_comercial->get_grupo_perfil($perfil[0]->id_perfil);

		echo '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
			echo '
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px">
					<div class="col-xs-4">Grupo del perfil: </div><strong>'.$grupo[0]->grupo.'</strong>
				</div>
				<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px">
					<div class="col-xs-4">Perfil actual: </div><strong>'.$perfil[0]->perfil.'</strong>
				</div>
				<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px">
					<div class="col-xs-4">Detalles del perfil: </div><strong><span class="txt-color-blue" style="cursor: pointer;" onclick="get_permisos('.$perfil[0]->id_perfil.')">Mostrar permisos</span></strong>
				</div>
				<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px">
					<div class="col-xs-4">Acciones de perfiles: </div><strong><span class="txt-color-blue" style="cursor: pointer;" onclick="new_perfil()">Crear perfil</span></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<strong><span class="txt-color-red" style="cursor: pointer;" onclick="del_perfil()">Borrar perfil</span></strong>
				</div>
			</div>';
			echo '<div class="row"><br /><br /><br /></div>';
			echo '<div class="row"><br /><br /><br /></div>';

			echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="row"><div class="col-xs-6">Cambiar el perfil: </div>';
			echo '<form id="perfil_123" action="/bo/comercial/actualiza_perfil" action="POST"><select name="perfil">';
				foreach ($perfiles as $key) {

					echo ($perfil[0]->id_perfil==$key->id_perfil) ? '<option selected value="'.$key->id_perfil.'">'.$key->descripcion.'</option>':'<option value="'.$key->id_perfil.'">'.$key->descripcion.'</option>';
				}
				echo '</select>
				<input name="id" type="hidden" value="'.$_POST['id'].'"></form>
			</div></div>
		</div>';

	}
	function get_permisos()
	{
		$permisos=$this->modelo_comercial->get_permiso_perfil($_POST['id']);
		foreach ($permisos as $key)
		{
			echo '<div class="row" style="border-bottom: 1px solid #CCC; padding: 3px">';
			echo '<div class="col-xs-6">Permiso: </div><strong>'.$key->permiso.'</strong></div>';
		}
	}
	function actualiza_perfil()
	{
		$this->modelo_comercial->actualiza_perfil();
		echo "Se ha cambiado el perfil con exito";
	}
	function new_perfil()
	{
		$this->modelo_comercial->new_perfil();
		echo "Se ha agregado el perfil con exito";
	}
	function del_perfil()
	{
		$this->modelo_comercial->del_perfil();
		echo "Se ha eliminado el perfil con exito";
	}
	function subred()
	{
		$id_red=$this->modelo_comercial->get_red($_POST['id']);
		$id_red=$id_red[0]->id_red;
		$afiliados=$this->modelo_comercial->get_afiliados($id_red);
		if($afiliados)
		{
			$usuario=array();
			foreach ($afiliados as $id_afiliado)
			{
				$usuario[]=$this->modelo_comercial->datos_perfil($id_afiliado->id_afiliado);
			}
			echo "<ul role='group'>";
			foreach ($usuario as $afiliado)
			{
				echo "
				<li class='parent_li' style='display: list-item;' role='treeitem' id='".$afiliado[0]->user_id."'>
	            	<span class='quitar'  onclick='subred(".$afiliado[0]->user_id.")'><i class='fa fa-lg fa-plus-circle'></i> ".$afiliado[0]->nombre." ".$afiliado[0]->apellido."</span>
	            </li>";
			}
			echo "</ul>";
		}
		else
		{
			echo "<ul  role='group'>
				<li  class='parent_li' style='display: list-item;' role='treeitem'>
					No tiene afiliados
	            </li>";
			echo "</ul>";
		}
	}
	function subtree()
	{
		$id_red=$this->modelo_comercial->get_red($_POST['id']);
		$id_red=$id_red[0]->id_red;
		$afiliados=$this->modelo_comercial->get_afiliados($id_red);

		if($afiliados)
		{
			$usuario=array();
			foreach ($afiliados as $id_afiliado)
			{
				$usuario[]=$this->modelo_comercial->datos_perfil($id_afiliado->id_afiliado);
			}
			echo "<ul>";
			foreach ($usuario as $afiliado)
			{
				$image 			 = $this->modelo_comercial->get_images($afiliado[0]->user_id);
				$img_perfil='/template/img/empresario.jpg';
				foreach ($image as $img)
				{
					$cadena=explode(".", $img->img);
					if($cadena[0]=="user")
					{
						$img_perfil=$img->url;
					}
				}
				echo "
				<li id='t".$afiliado[0]->user_id."'>
	            	<a class='quitar' onclick='subtree(".$afiliado[0]->user_id.")' style='background: url(".$img_perfil."); background-size: cover; background-position: center;' href='#'><div class='nombre'>".$afiliado[0]->nombre." ".$afiliado[0]->apellido."</div></a>
	            	<div onclick='detalles(".$afiliado[0]->user_id.")' class='todo'>Detalles</div>
	            </li>";
			}
			echo "</ul>";
		}
		else
		{
			$nombre=$this->modelo_comercial->get_name($_POST['id']);
			$nombre='"'.$nombre[0]->nombre." ".$nombre[0]->apellido.'"';
			echo "<ul>
				<li>
					<a href='#'>No tiene afiliados</a>
	            </li>";
			echo "</ul>";
		}
	}
	function afiliar_nuevo()
	{
		$id=$this->tank_auth->get_user_id();
		$this->modelo_comercial->afiliar_nuevo($id);
	}
	function afiliar_nuevo_r($id)
	{
		sleep(1);
		//print_r($_POST);
		$this->modelo_comercial->afiliar_nuevo($id);
		if($_POST['sponsor'])
		{
			$id_=$this->tank_auth->get_user_id();
			$this->modelo_comercial->actualiza_directo($id_,$id);
		}
	}
	function detalle_usuario()
	{
		$image 			 = $this->modelo_comercial->get_images($_POST['id']);

		$img_perfil="/template/img/empresario.jpg";
		foreach ($image as $img)
		{
			$cadena=explode(".", $img->img);
			if($cadena[0]=="user")
			{
				$img_perfil=$img->url;
			}
		}

		$usuario =$this->modelo_comercial->datos_perfil($_POST['id']);
		$edad    =$this->modelo_comercial->edad($_POST['id']);
		echo '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
		echo '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><img alt="'.$usuario[0]->nombre.'" src="'.$img_perfil.'" style="max-width: 100%; max-height: 100%"></div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6"><div class="row">Id: '.$_POST["id"].'</div>';
		echo '<div class="row">Nombre: '.$usuario[0]->nombre.'</div>';
		echo '<div class="row">Apellido: '.$usuario[0]->apellido.'</div>';
		echo '<div class="row">Nacimiento: '.$usuario[0]->nacimiento.'</div>';
		echo '<div class="row">Edad: '.$edad[0]->edad.'</div></div>';
		echo '</div></div>';
	}
	function nuevo_grupo()
	{
		$this->db->query('insert into cat_grupo (descripcion) values ("'. $_GET["grupo"] .'")');
	}
	function sube_video()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'mp4|jpg|png';

		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);

		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_multi_upload('userfile'))
		{
			//echo 'Holis';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->get_multi_upload_data());
			foreach ($data["upload_data"] as $key) 
			{
				$filename=strrev($key["file_name"]);
				$explode=explode(".",$filename);
				$nombre=strrev($explode[1]);
				$extencion=strrev($explode[0]);
				$ext=strtolower($extencion);
				if($ext=="mp4")
				{
					$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
					values ('.$id.','.$_POST['grupo_frm'].',2,"'.$_POST['desc_frm'].'","'.$ruta.$key["file_name"].'","ACT","'.$_POST["nombre_publico"].'")');
					$video=mysql_insert_id();
				}
				else 
				{
					$this->db->query('insert into cat_img (url,nombre_completo,nombre,extencion,estatus) 
					values ("'.$ruta.$key["file_name"].'","'.$key["file_name"].'","'.$nombre.'","'.$extencion.'","ACT")');
					$imgn=mysql_insert_id();
				}
									
			}
			$this->db->query('insert into cross_img_archivo	values ('.$video.','.$imgn.')');
		}  
		
		redirect('/bo/comercial/oficina_virtual');
	}
	function sube_video_youtube()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		//echo 'heey';
		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'jpg|png|gif';

		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);
		//echo 'heey 2';
		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_upload())
		{
			//echo 'Holis valio vergui';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$filename=strrev($data["upload_data"]["file_name"]);
			$explode=explode(".",$filename);
			$nombre=strrev($explode[1]);
			$extencion=strrev($explode[0]);
			$ext=strtolower($extencion);
			if($ext=='jpg'||$ext=="png") 
			{
				//echo 'insert into noticia (id_usuario,nombre,contenido,imagen) values ('.$id.',"'.$_POST['nombre_frm'].'","'.$_POST['desc_frm'].'","'.$ruta.$_POST['file_nme'].'")';
				$this->db->query('insert into cat_img (url,nombre_completo,nombre,extencion,estatus) 
				values ("'.$ruta.$data["upload_data"]["file_name"].'","'.$data["upload_data"]["file_name"].'","'.$nombre.'","'.$extencion.'","ACT")');
				$imgn=mysql_insert_id();
				
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',8,"'.$_POST['desc_frm'].'","'.$_POST["url"].'","ACT","'.$_POST["nombre_publico"].'")');
				$video=mysql_insert_id();
				$this->db->query('insert into cross_img_archivo	values ('.$video.','.$imgn.')');
			}
		}
		redirect('/bo/comercial/oficina_virtual');
	}
	function sube_presentacion()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'ppt|pptx';

		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);

		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_upload())
		{
			//echo 'Holis';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$nombre=$data['upload_data']['file_name'];
			$filename=strrev($nombre);
			$explode=explode(".",$filename);
			$nombre=strrev($explode[1]);
			$extencion=strrev($explode[0]);
			$ext=strtolower($extencion);
			
			if($ext=="pptx") 
			{
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',4,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			elseif ($ext=="ppt") 
			{ 
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status) 
				values ('.$id.','.$_POST['grupo_frm'].',3,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT")');
			}
			//echo 'ptm';
			
			
		}  
		redirect('/bo/comercial/oficina_virtual');
	}
	function sube_ebook()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'pdf|jpg|png';

		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);

		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_multi_upload('userfile'))
		{
			//echo 'Holis';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
			print_r($error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->get_multi_upload_data());
			foreach ($data["upload_data"] as $key) 
			{
				$filename=strrev($key["file_name"]);
				$explode=explode(".",$filename);
				$nombre=strrev($explode[1]);
				$extencion=strrev($explode[0]);
				$ext=strtolower($extencion);
				if($ext=="pdf")
				{
					$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
					values ('.$id.','.$_POST['grupo_frm'].',1,"'.$_POST['desc_frm'].'","'.$ruta.$key["file_name"].'","ACT","'.$_POST["nombre_publico"].'")');
					$ebook=mysql_insert_id();
				}
				else 
				{
					$this->db->query('insert into cat_img (url,nombre_completo,nombre,extencion,estatus) 
					values ("'.$ruta.$key["file_name"].'","'.$key["file_name"].'","'.$nombre.'","'.$extencion.'","ACT")');
					$imgn=mysql_insert_id();
				}
				
					
			}
			$this->db->query('insert into cross_img_archivo	values ('.$ebook.','.$imgn.')');
		}  
		redirect('/bo/comercial/oficina_virtual');
	}
	function sube_noticia()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		//echo 'heey';
		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'jpg|png|gif';

		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);
		//echo 'heey 2';
		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_upload())
		{
			//echo 'Holis valio vergui';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$nombre=$data['upload_data']['file_name'];
			$filename=strrev($nombre);
			$explode=explode(".",$filename);
			$nombre=strrev($explode[1]);
			$extencion=strrev($explode[0]);
			$ext=strtolower($extencion);
			//echo 'se supone que se debo de subir';
			if($ext=="jpg"||$ext="png"||$ext="gif") 
			{
				//echo 'insert into noticia (id_usuario,nombre,contenido,imagen) values ('.$id.',"'.$_POST['nombre_frm'].'","'.$_POST['desc_frm'].'","'.$ruta.$_POST['file_nme'].'")';
				$this->db->query('insert into noticia (id_usuario,nombre,contenido,imagen) 
				values ('.$id.',"'.$_POST['nombre_frm'].'","'.$_POST['desc_frm'].'","'.$ruta.$nombre.'")');
			}
			//echo 'ptm';
			
			
		}  
		redirect('/bo/comercial/oficina_virtual');
	}
	function sube_info()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		//echo 'heey';
		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'jpg|png|gif';
		
		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);
		//echo 'heey 2';
		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_upload())
		{
			//echo 'Holis valio vergui';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$nombre=$data['upload_data']['file_name'];
			$nombre=$data['upload_data']['file_name'];
			$filename=strrev($nombre);
			$explode=explode(".",$filename);
			$nombre=strrev($explode[1]);
			$extencion=strrev($explode[0]);
			$ext=strtolower($extencion);
			//echo 'se supone que se debo de subir';
			if($ext=="jpg"||$ext="png"||$ext="gif")  
			{
				//echo 'insert into noticia (id_usuario,nombre,contenido,imagen) values ('.$id.',"'.$_POST['nombre_frm'].'","'.$_POST['desc_frm'].'","'.$ruta.$_POST['file_nme'].'")';
				$this->db->query('insert into informacion (id_usuario,nombre,descripcion,img) 
				values ('.$id.',"'.$_POST['nombre_frm'].'","'.$_POST['desc_frm'].'","'.$ruta.$nombre.'")');
			}
			//echo 'ptm';
			
			
		}  
		redirect('/bo/comercial/oficina_virtual');
	}
	function nuevo_evento()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$tipo=$data['tipo'];
		$color=$data['color'];
		$nombre=$data['nombre'];
		$desc=$data['descripcion'];
		$dia_ini=$data['dia_ini'];
		$hora_ini=$data['hora_ini'];
		$minuto_ini=$data['minuto_ini'];
		$dia_fin=$data['dia_fin'];
		$hora_fin=$data['hora_fin'];
		$minuto_fin=$data['minuto_fin'];
		$ano_ini=substr($dia_ini, 6);
		$mes_ini=substr($dia_ini, 3,2);
		$dia_ini=substr($dia_ini, 0,2);
		$ano_fin=substr($dia_fin, 6);
		$mes_fin=substr($dia_fin, 3,2);
		$dia_fin=substr($dia_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini.' '.$hora_ini.':'.$minuto_ini.':00';
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin.' '.$hora_fin.':'.$minuto_fin.':00';
		$id=$this->tank_auth->get_user_id();
		$this->db->query('insert into evento (id_tipo,id_color,id_usuario,nombre,descripcion,inicio,fin,lugar,costo,direccion,latitud,longitud,observaciones) 
						values('.$tipo.','.$color.','.$id.',"'.$nombre.'","'.$desc.'","'.$inicio.'","'.$fin.'","'.$data["lugar"].'",'.$data["costo"].'
						,"'.$data["direccion"].'","0.00000","0.00000","'.$data["observacion"].'")');
		$id_evento=mysql_insert_id();
		$descripcion=$desc.'&nbspc;<a class="ver-mas-calendario" href="#" onclick="ver_evento('.$id_evento.')">Ver más</a>';
		$this->db->query("update evento set descripcion='".$descripcion."' where id=".$id_evento);
	}
	function nuevo_video()
	{
		$id=$this->tank_auth->get_user_id();
		$grupo=$_GET['grupo'];
		$ruta="/media/".$id."/".$_GET['video'];
		$archivo=$this->general->get_tipo_archivo('mp4');
		$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta) values ('.$id.','.$grupo.','.$archivo.',"algo","'.$ruta.'")');
	}
	function insert_coment()
	{
		$id_user=$this->tank_auth->get_user_id();	
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$id=$data['video'];
		$coment=$data['comentario'];
		$this->db->query('insert into comentario (comentario,id_video,autor) values ("'.$coment.'"," '.$id.'","'.$id_user.'")');
	}
	function sube_archivo()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}

		$id=$this->tank_auth->get_user_id();

		//Checamos si el directorio del usuario existe, si no, se crea
		if(!is_dir(getcwd()."/media/".$id))
		{
			mkdir(getcwd()."/media/".$id, 0777);
		}

		$ruta="/media/".$id."/";
		//definimos la ruta para subir la imagen
		$config['upload_path'] 		= getcwd().$ruta;
		$config['allowed_types'] 	= 'ppt|pptx|pdf|jpg|png|mp4';

		//Cargamos la libreria con las configuraciones de arriba
		$this->load->library('upload', $config);

		//Preguntamos si se pudo subir el archivo "foto" es el nombre del input del dropzone
		if (!$this->upload->do_upload())
		{
			//echo 'Holis';
			//echo $ruta;
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			if(stristr($_POST['file_nme'], 'pptx')) 
			{
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',4,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			if (stristr($_POST['file_nme'], 'ppt')) 
			{ 
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',3,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			if (stristr($_POST['file_nme'], 'pdf')) 
			{ 
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',1,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			if (stristr($_POST['file_nme'], 'jpg')) 
			{ 
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',5,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			if (stristr($_POST['file_nme'], 'png')) 
			{ 
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',6,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			if (stristr($_POST['file_nme'], 'mp4')) 
			{ 
				$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
				values ('.$id.','.$_POST['grupo_frm'].',2,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
			}
			//echo 'ptm';
			$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta,status,nombre_publico) 
			values ('.$id.','.$_POST['grupo_frm'].',9,"'.$_POST['desc_frm'].'","'.$ruta.$data['upload_data']['file_name'].'","ACT","'.$_POST["nombre_publico"].'")');
		}  
		redirect('/bo/comercial/oficina_virtual');
	}
	function nuevo_archivo()
	{
		$tipo=$_GET['tipo'];
		$this->db->query('insert into archivo (id_usuario,id_grupo,id_tipo,descripcion,ruta) values ()');
	}
	function borrar_archivo()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$this->db->query('delete from archivo where id_archivo='.$data['id']);
		if(unlink($data['file']))
    		echo "File Deleted.";

	}
	function borrar_noticia()
	{
		$this->db->query('delete from noticia where id='.$_GET['id']);

	}
	function borrar_info()
	{
		$this->db->query('delete from informacion where id='.$_GET['id']);

	}
	function borrar_cupon()
	{
		$this->db->query('delete from cupon where id_cupon='.$_GET['id']);

	}
	function borrar_encuesta()
	{
		$q=$this->db->query('SELECT id_pregunta FROM encuesta_pregunta WHERE id_encuesta='.$_GET['id']);
		$preg=$q->result();
		for($i=0;$i<sizeof($preg);$i++)
		{
			$this->db->query('delete from encuesta_respuesta where id_pregunta='.$preg[$i]->id_pregunta);	
		}
		$this->db->query('delete from encuesta where id_encuesta='.$_GET['id']);
		$this->db->query('delete from encuesta_pregunta where id_encuesta='.$_GET['id']);
		$n=$this->db->query('SELECT id_encuesta_contestada FROM encuesta_contestada WHERE id_encuesta='.$_GET['id']);
		$cont=$n->result();
		for($j=0;$j<sizeof($cont);$j++)
		{
			$this->db->query('delete from encuesta_resultado where id_encuesta_contestada='.$cont[$j]->id_encuesta_contestada);	
		}
		$this->db->query('delete from encuesta_contestada where id_encuesta='.$_GET['id']);
	}
	function borrar_evento()
	{
		$this->db->query('delete from evento where id='.$_GET['id']);
	}
	function nuevo_cupon()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$this->db->query('insert into cupon (codigo,descripcion,estado) values ("'.$data['cod'].'","'.$data['desc'].'","'.$data['act'].'")');
	}
	function estado_cupon()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$this->db->query('update cupon set estado="'.$data['tipo'].'" where id_cupon='.$data['id']);
	}
	function estado_archivo()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$this->db->query('update archivo set status="'.$data['tipo'].'" where id_archivo='.$data['id']);
	}
	function estado_encuesta()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$this->db->query('update encuesta set estatus="'.$data['tipo'].'" where id_encuesta='.$data['id']);
	}
	function editar_archivo()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		if($data['tipo']==1)
		{
			$this->db->query('update archivo set id_grupo='.$data['grupo'].', descripcion="'.$data['desc'].'" where id_archivo='.$data['id']);
		}
		if($data['tipo']==2)
		{
			$this->db->query('update informacion set nombre="'.$data['grupo'].'", descripcion="'.$data['desc'].'" where id='.$data['id']);
		}
		if($data['tipo']==3)
		{
			$this->db->query('update noticia set nombre="'.$data['grupo'].'", contenido="'.$data['desc'].'" where id='.$data['id']);
		}
		if($data['tipo']==4)
		{
			$this->db->query('update cupon set codigo="'.$data['grupo'].'", descripcion="'.$data['desc'].'" where id_cupon='.$data['id']);
		}
	}
	function buscar_usr()
	{
		$users=$this->modelo_comercial->get_users($_GET['nombre']);
		if(empty($users))
		{
			echo '<div class="row">
					<div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
					</div>
					<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
						<div style="text-align:middle;">
							NO HAY USUARIOS CON ESTE NOMBRE
						</div>
					</div>
					<div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
					</div>
				</div>
				';
			
		}
		else
		{
			echo '
			<div class="row">
				<div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
				</div>
				<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
					<div class="table-responsive">	
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th></th>
									<th>Usuario</th>
									<th>Nombre</th>
									<th>Apellido</th>
								</tr>
							</thead>
							<tbody>';
								
									for($i=0;$i<sizeof($users);$i++)
									{
										echo '
										<tr>
											<td>
												<label class="radio">
													<input type="radio" name="radio" id="user_selected" value="'.$users[$i]->id.'">
												<i></i></label>
											</td>
											<td>'.$users[$i]->username.'</td>
											<td>'.$users[$i]->nombre.'</td>
											<td>'.$users[$i]->apellido.'</td>
										</tr>';
									}
						echo '</tbody>
						</table>

					</div>
				</div>
				<div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
				</div>
			</div>';
		}
	}
	function insert_cupon_usr()
	{
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$this->db->query('insert into cross_usuario_cupon values('.$data["usuario"].','.$data["cupon"].')');
	}
	function ver_eventos()
	{
		$eventos=$this->modelo_comercial->get_evento();
		echo '
		<div class="row">
									
			<!-- NEW WIDGET START -->
			<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
				</div>
				<div class="col-lg-10 col-sm-10 col-md-10 col-xs-10">
					<div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">
						
						
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
		
								<table id="datatable_fixed_column5" class="table table-striped table-bordered table-hover" width="100%">
									<thead>
										<tr>
											<th data-hide="phone">ID</th>
											<th data-class="expand">Nombre</th>
											<th>Descripcion</th>
											<th data-hide="phone">Fecha inicio</th>
											<th>Fecha fin</th>
											<th>Mas...</th>
										</tr>
									</thead>
									<tbody>';
									for($i=0;$i<sizeof($eventos);$i++)
									{
										echo'
										<tr>
											<td>'.($i+1).'</td>
											<td>'.$eventos[$i]->nombre.'</td>
											<td>'.$eventos[$i]->descripcion.'</td>
											<td>'.$eventos[$i]->inicio.'</td>
											<td>'.$eventos[$i]->fin.'</td>
											<td class="text-center">									
												<a class="txt-color-red" style="cursor: pointer;" onclick="delete_evento('.$eventos[$i]->id.')" title="Eliminar""><i class="fa fa-trash-o"></i></a>
												<a class="txt-color-green"  style="cursor: pointer;" onclick="editar_evento('.$eventos[$i]->id.')"  title="Editar"><i class="fa fa-edit"></i></a>
											</td>';
											
									}
										
								echo '</tbody>
								</table>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
					</div>
				</div>
				<div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
				</div>
				
				<!-- end widget -->
	
			</article>
		</div>';
	}
	function update_evento()
	{
		if (!$this->tank_auth->is_logged_in()) 
		{																		// logged in
			redirect('/auth');
		}
		$data=$_GET["info"];
		$data=json_decode($data,true);
		$tipo=$data['tipo'];
		$color=$data['color'];
		$nombre=$data['nombre'];
		$desc=$data['descripcion'];
		$dia_ini=$data['dia_ini'];
		$hora_ini=$data['hora_ini'];
		$minuto_ini=$data['minuto_ini'];
		$dia_fin=$data['dia_fin'];
		$hora_fin=$data['hora_fin'];
		$minuto_fin=$data['minuto_fin'];
		$ano_ini=substr($dia_ini, 6);
		$mes_ini=substr($dia_ini, 3,2);
		$dia_ini=substr($dia_ini, 0,2);
		$ano_fin=substr($dia_fin, 6);
		$mes_fin=substr($dia_fin, 3,2);
		$dia_fin=substr($dia_fin, 0,2);
		$inicio=$ano_ini.'-'.$mes_ini.'-'.$dia_ini.' '.$hora_ini.':'.$minuto_ini.':00';
		$fin=$ano_fin.'-'.$mes_fin.'-'.$dia_fin.' '.$hora_fin.':'.$minuto_fin.':00';
		
		$id=$this->tank_auth->get_user_id();
		$this->db->query('update evento set id_tipo='.$tipo.' ,id_color='.$color.' ,id_usuario='.$id.' ,nombre="'.$nombre.'" ,descripcion="'.$desc.'" ,
		inicio="'.$inicio.'",fin="'.$fin.'" where id='.$data['id']);
	}
	
	function insertar_encuesta()
	{
		$id_usuario=$this->tank_auth->get_user_id();
		$data=$_POST["info"];
		$data=json_decode($data,true);
		//print_r($data);
		$keys=array_keys($data);
		//print_r($keys);
		$this->db->query('insert into encuesta (nombre,descripcion,id_usuario,estatus) values ("'.$data['nombre'].'","'.$data['desc'].'",'.$id_usuario.',"DES")');
		$enc_id=mysql_insert_id();
		for($i=0;$i<$data['qty'];$i++)
		{
			$this->db->query('insert into encuesta_pregunta (id_encuesta,pregunta) values ('.$enc_id.',"'.$data[$keys[$i]]["pregunta"].'")');
			$preg_id=mysql_insert_id();
			//print_r($data[$keys[$i]]);
			//echo $data[$keys[$i]]["pregunta"];
			$resp_keys=array_keys($data[$keys[$i]]["respuestas"]);
			for($j=0;$j<sizeof($resp_keys);$j++)
			{
				//echo $data[$keys[$i]]["respuestas"][$resp_keys[$j]];
				//print_r($resp_keys);
				if($data[$keys[$i]]["respuestas"][$resp_keys[$j]]!="")
				{
					$this->db->query('insert into encuesta_respuesta (id_pregunta,respuesta) values ('.$preg_id.',"'.$data[$keys[$i]]["respuestas"][$resp_keys[$j]].'")');
				}			
			}
		} 
	}
	function add_grupo()
	{
		$this->db->query("insert into cat_grupo (descripcion) values ('".$_POST['grupo']."')");
	}

}