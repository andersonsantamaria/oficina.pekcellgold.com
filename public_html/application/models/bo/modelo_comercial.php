<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class modelo_comercial extends CI_Model
{
	function get_dato_usuario()
	{
		$q=$this->db->query('
			Select U.id, U.username, 
			U.email, 
			TU.descripcion tipo_usuario, 
			UP.nombre, 
			UP.apellido, 
			UP.id_estatus, 
			UP.keyword, 
			(select url from cat_img CIMG where CIMG.id_img=(select CUI.id_img from cross_img_user CUI where CUI.id_user=U.id)) url 
			from users U 
			left join user_profiles UP on UP.user_id=U.id  
			left join cat_tipo_usuario TU on TU.id_tipo_usuario=UP.id_tipo_usuario 
			');
		return $q->result();
	}
	function get_detalle_usuario()
	{
		$q=$this->db->query('
			Select U.id, U.username, 
			U.email, 
			CS.descripcion sexo, 
			CEC.descripcion estado_civil, 
			TU.descripcion tipo_usuario, 
			CE.descripcion estudio, 
			CO.descripcion ocupacion, 
			CTD.descripcion tiempo_dedicado, 
			UP.nombre, 
			UP.apellido, 
			UP.keyword, 
			COA.nombre nombre_co, 
			COA.apellido apellido_co, 
			COA.keyword keyword_co, 
			CEF.descripcion estatus_afiliado, 
			UP.fecha_nacimiento, 
			(select (YEAR(CURDATE())-YEAR(fecha_nacimiento)) - (RIGHT(CURDATE(),5)<RIGHT(fecha_nacimiento,5)) edad from user_profiles where user_id=U.id) edad, 
			UP.ultima_sesion, 
			(select url from cat_img CIMG where CIMG.id_img=(select CUI.id_img from cross_img_user CUI where CUI.id_user=U.id)) url 
			from users U 
			left join user_profiles UP on UP.user_id=U.id  
			left join cat_sexo CS on UP.id_sexo=CS.id_sexo 
			left join cat_edo_civil CEC on CEC.id_edo_civil=UP.id_edo_civil 
			left join cat_tipo_usuario TU on TU.id_tipo_usuario=UP.id_tipo_usuario 
			left join cat_estudios CE on CE.id_estudio=UP.id_estudio 
			left join cat_ocupacion CO on CO.id_ocupacion=UP.id_ocupacion 
			left join cat_tiempo_dedicado CTD on CTD.id_tiempo_dedicado=UP.id_tiempo_dedicado 
			left join cat_estatus_afiliado CEF on CEF.id_estatus=UP.id_estatus 
			left join coaplicante COA on COA.id_user=U.id 
			where U.id='.$_POST['id']);
		return $q->result();
	}
	function get_perfil_usuario()
	{
		$q=$this->db->query('select CPU.id_perfil, (select descripcion from cat_perfil_permiso CPP where CPP.id_perfil=CPU.id_perfil) perfil 
			from cross_perfil_usuario CPU 
			where id_user='.$_POST['id']);
		return $q->result();
	}
	function get_grupo_perfil($id_perfil)
	{
		$q=$this->db->query('select id_grupo, (select descripcion from cat_grupo_perfil CPP where CPP.id_grupo=CPU.id_grupo) grupo 
			from cross_grupo_perfil CPU 
			where id_perfil='.$id_perfil);
		return $q->result();
	}
	function get_permiso_perfil($id_perfil)
	{
		$q=$this->db->query('select id_permiso, (select nombre from cat_permiso CP where CP.id_permiso=CPU.id_permiso) permiso 
			from cross_permiso_perfil CPU 
			where id_perfil='.$id_perfil);
		return $q->result();
	}
	function get_perfiles()
	{
		$q=$this->db->query('select * from cat_perfil_permiso');
		return $q->result();
	}
	function get_permisos()
	{
		$q=$this->db->query('select * from cat_permiso');
		return $q->result();
	}
	function get_cat_grupo_perfil()
	{
		$q=$this->db->query('select * from cat_grupo_perfil');
		return $q->result();
	}
	function actualiza_perfil()
	{
		$this->db->query('delete from cross_perfil_usuario where id_user='.$_POST['id']);
		$this->db->query('insert into cross_perfil_usuario values('.$_POST['id'].', '.$_POST['perfil'].')');
	}
	function new_perfil()
	{
		$dato_perfil=array(
					"descripcion" => $_POST['perfil'],
					"estatus"     => "ACT"
	            );
		$this->db->insert("cat_perfil_permiso",$dato_perfil);

		$id_perfil=mysql_insert_id();

		$dato_grupo=array(
					"id_grupo"  => $_POST['grupo'],
					"id_perfil" => $id_perfil
	            );
		$this->db->insert("cross_grupo_perfil",$dato_grupo);
		foreach ($_POST['permiso'] as $key)
		{
			$dato_permiso=array(
					"id_permiso" => $key,
					"id_perfil"  => $id_perfil
	            );
			$this->db->insert("cross_permiso_perfil",$dato_permiso);
		}
	}
	function del_perfil()
	{
		$this->db->query('delete from cat_perfil_permiso where id_perfil='.$_POST['perfil']);
		$this->db->query('delete from cross_grupo_perfil where id_perfil='.$_POST['perfil']);
		$this->db->query('delete from cross_permiso_perfil where id_perfil='.$_POST['perfil']);
	}
	function desactiva_usuario()
	{
		//1->Activo 2->inactivo 3->Pago pendiente 4->Sin compra mínima
		$this->db->query('update user_profiles set id_estatus=2  where user_id='.$_POST['id']);
	}
	function activa_usuario()
	{
		//1->Activo 2->inactivo 3->Pago pendiente 4->Sin compra mínima
		$this->db->query('update user_profiles set id_estatus=1  where user_id='.$_POST['id']);
	}
	function del_user()
	{

	}
	function get_preregistro()
	{
		$q=$this->db->query('select * from preregistro');
		return $q->result();
	}
	function get_groups()
	{
		$q=$this->db->query('select * from cat_grupo');
		return $q->result();
	}
	function get_tipo_archivo($ext)
	{
		$q=$this->$this->db->query('select id from cat_tipo_archivo where descripcion= '.$ext);
		return $q->result();
	}
	function get_video()
	{
		$q=$this->db->query('SELECT c.id_archivo id,c.id_grupo id_grp, a.descripcion grupo,b.username usuario,c.fecha fecha,c.nombre_publico n_publico,c.descripcion 
		descripcion,c.ruta ruta, e.url img, c.id_tipo tipo FROM cat_grupo a, users b, archivo c, cross_img_archivo d, cat_img e WHERE c.id_tipo in (2,8)
		and a.id=c.id_grupo and b.id=c.id_usuario and c.id_archivo=d.id_archivo and e.id_img=d.id_img');
		return $q->result();
	}
	function get_presentaciones()
	{
		$q=$this->db->query('SELECT c.id_archivo id, a.descripcion grupo,b.username usuario,c.fecha fecha,c.nombre_publico n_publico,c.descripcion descripcion,c.ruta ruta FROM cat_grupo a, 
		users b, archivo c WHERE c.id_tipo in (3,4) and a.id=c.id_grupo and b.id=c.id_usuario');
		return $q->result();
	}
	function get_ebooks()
	{
		$q=$this->db->query('SELECT a.descripcion grupo,b.username usuario,c.fecha fecha,c.nombre_publico n_publico,c.descripcion descripcion,c.ruta ruta, d.url img, 
		c.id_archivo id FROM cat_grupo a, users b, archivo c, cat_img d, cross_img_archivo e WHERE c.id_tipo=1 and a.id=c.id_grupo and b.id=c.id_usuario 
		and c.status="ACT" and d.id_img=e.id_img and c.id_archivo=e.id_archivo');
		return $q->result();
	}
	function get_files()
	{
		$q=$this->db->query('SELECT c.status estado, c.id_archivo id, a.descripcion grupo,b.username usuario,c.fecha fecha,c.nombre_publico n_publico,
		c.descripcion descripcion,c.ruta ruta, c.id_tipo tipo FROM cat_grupo a, users b, archivo c WHERE a.id=c.id_grupo and b.id=c.id_usuario and id_tipo=9');
		return $q->result();
	}
	function get_info() 
	{
		$q=$this->db->query('SELECT c.id id, b.username usuario,c.fecha fecha,c.descripcion descripcion,c.nombre nombre, c.img ruta FROM users b, informacion c 
		WHERE b.id=c.id_usuario');
		return $q->result();
	}
	function get_cupon()
	{
		$q=$this->db->query('SELECT * from cupon');
		return $q->result();
	}
	function get_new()
	{
		$q=$this->db->query('SELECT b.id id, a.username usuario, b.nombre nombre, b.contenido contenido, b.imagen imagen, b.fecha fecha FROM users a, noticia b 
		WHERE a.id=b.id_usuario order by fecha desc');
		return $q->result();
	}
	function noticia_espec($id)
	{
		$q=$this->db->query('SELECT * FROM noticia WHERE id='.$id);
		return $q->result();
	}
	function get_evento()
	{
		$q=$this->db->query('SELECT b.id id, a.username usuario, b.id_tipo tipo, b.id_color color, b.nombre nombre, b.descripcion descripcion, b.inicio inicio, b.fin fin 
		from users a, evento b where a.id=b.id_usuario order by b.inicio asc');
		return $q->result();
	}
	function get_comments()
	{
		$q=$this->db->query('SELECT a.*, b.username FROM comentario a, users b WHERE a.autor=b.id order by fecha DESC');
		return $q->result();
	}
	function get_users($name)
	{
		$q=$this->db->query('SELECT a.id, a.username, b.nombre, b.apellido from users a, user_profiles b where (a.username like "%'.$name.'%" or b.nombre like "%'.$name.'%" 
		or b.apellido like "%'.$name.'%") and a.id=b.user_id');
		return $q->result();
	}
	function get_encuestas()
	{
		$q_encuestas=$this->db->query("SELECT a.*, b.username, a.id_encuesta veces FROM encuesta a, users b where a.id_usuario=b.id");
		$r_encuestas=$q_encuestas->result();
		foreach($r_encuestas as $enc)
		{
			$q_veces=$this->db->query("Select count(*) veces from encuesta_contestada where id_encuesta=".$enc->id_encuesta);
			$r_veces=$q_veces->result();
			$enc->veces=$r_veces[0]->veces;
		}
		return $r_encuestas;
	}

	function get_sponsor()
	{
		$q=$this->db->query("select * from afiliar where id_afiliado=".$_POST['id']);
		return $q->result();
	}
	function get_id_by_red($red)
	{
		$q=$this->db->query("select id_usuario from red where id_red=".$red);
		$q=$q->result();
		return $q[0]->id_usuario;
	}
	function get_red($id)
	{
		$q=$this->db->query("select * from red where id_usuario=".$id);
		return $q->result();
	}
	function get_afiliados_($id)
	{
		$q=$this->db->query("select *,(select nombre from user_profiles where user_id=id_afiliado) afiliado,
			(select apellido from user_profiles where user_id=id_afiliado) afiliado_p, 
			(select nombre from user_profiles where user_id=debajo_de) debajo_de_n, 
			(select apellido from user_profiles where user_id=debajo_de) debajo_de_p, 
			(select (select url from cat_img b where a.id_img=b.id_img) url from cross_img_user a where id_user = id_afiliado) img 
			from afiliar where id_red=".$id." order by lado");
		return $q->result();
	}
	function get_afiliados($id)
	{
		$debajo_de=$this->db->query("select id_usuario from red where id_red=".$id);
		$debajo_de=$debajo_de->result();
		$q=$this->db->query("select *,(select nombre from user_profiles where user_id=id_afiliado) afiliado,
			(select apellido from user_profiles where user_id=id_afiliado) afiliado_p, 
			(select nombre from user_profiles where user_id=debajo_de) debajo_de_n, 
			(select apellido from user_profiles where user_id=debajo_de) debajo_de_p, 
			(select (select url from cat_img b where a.id_img=b.id_img) url from cross_img_user a where id_user = id_afiliado) img 
			from afiliar where id_red=".$id." and debajo_de=".$debajo_de[0]->id_usuario." order by lado");
		return $q->result();
	}
	function get_images($id)
	{
		$q=$this->db->query('select (select nombre_completo from cat_img b where a.id_img=b.id_img) img, (select url from cat_img b where a.id_img=b.id_img) url from cross_img_user a where id_user = '.$id);
		return $q->result();
	}
	function datos_perfil($id)
	{
		$q=$this->db->query('
			SELECT profile.keyword rfc, (select email from users where id=profile.user_id) email, profile.id_edo_civil, profile.user_id, profile.nombre nombre, profile.apellido apellido, 
			profile.fecha_nacimiento nacimiento, profile.id_estudio id_estudio, 
			profile.id_ocupacion id_ocupacion, 
			profile.id_tiempo_dedicado id_tiempo_dedicado, 
			sexo.descripcion sexo, 
			edo_civil.descripcion edo_civil,
			estilos.bg_color, estilos.btn_1_color, estilos.btn_2_color
			from user_profiles profile
			left join cat_sexo sexo 
			on profile.id_sexo=sexo.id_sexo
			left join estilo_usuario estilos on
			profile.user_id=estilos.id_usuario
			left join cat_edo_civil edo_civil on
			profile.id_edo_civil=edo_civil.id_edo_civil 
			where profile.user_id='.$id);
		return $q->result();
	}
	function get_name($id)
	{
		$q=$this->db->query('select nombre, apellido from user_profiles where user_id='.$id);
		return $q->result();
	}
	function edad($id)
	{
		$q=$this->db->query("select (YEAR(CURDATE())-YEAR(fecha_nacimiento)) - (RIGHT(CURDATE(),5)<RIGHT(fecha_nacimiento,5)) edad from user_profiles where user_id=".$id);
		return $q->result();
	}
	function cambia_posicion()
	{
		if($_POST['posicion']==0)
			$contrario=1;
		else
			$contrario=0;
		$this->db->query('Update afiliar set lado='.$_POST['posicion'].' where id_afiliado='.$_POST['mi_id']);

		foreach ($_POST['ids'] as $key)
		{
			if($key!=$_POST['mi_id'])
			{
				$otro=$key;
			}
		}
		if(isset($otro))
			$this->db->query('Update afiliar set lado='.$contrario.' where id_afiliado='.$otro);
	}
}