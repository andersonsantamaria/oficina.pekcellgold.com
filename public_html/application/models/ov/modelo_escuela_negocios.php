<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class modelo_escuela_negocios extends CI_Model
{
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
		$q=$this->db->query('SELECT b.username usuario,c.fecha fecha,c.descripcion descripcion,c.nombre nombre, c.img ruta FROM users b, informacion c WHERE b.id=c.id_usuario');
		return $q->result();
	}
	function get_cupon($id)
	{
		$q=$this->db->query('SELECT a.id_cupon, a.codigo, a.descripcion, a.fecha_adicion from cupon a, cross_usuario_cupon b where a.id_cupon=b.id_cupon and a.estado="ACT" 
		and b.id_usuario='.$id);
		return $q->result();
	}
	function get_new()
	{
		$q=$this->db->query('SELECT b.id id_noticia, a.username usuario, b.nombre nombre, b.contenido contenido, b.imagen imagen, b.fecha fecha FROM users a, noticia b 
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
		$q=$this->db->query('SELECT a.username usuario, b.id_tipo tipo, b.id_color color, b.nombre nombre, b.descripcion descripcion, b.inicio inicio, b.fin fin from users a, 
		evento b where a.id=b.id_usuario');
		return $q->result();
	}
	function get_comments()
	{
		$q=$this->db->query('SELECT a.*, b.username FROM comentario a, users b WHERE a.autor=b.id order by fecha DESC');
		return $q->result();
	}
	function get_promo_mes()
	{
		$fecha=date("Y-m-d");
		$q=$this->db->query('select *, estatus img from promocion where "'.$fecha.'" between inicio and fin ');
		return $q->result();
	}
	function get_promo_historico()
	{
		$fecha=date("Y-m-d");
		$q=$this->db->query('select *, estatus img from promocion');
		return $q->result();
	}
	function get_img_prom($i)
	{
		$q=$this->db->query('SELECT a.url FROM cat_img a, promocion b, cross_img_promo c WHERE c.id_promo=b.id_promocion and a.id_img=c.id_img 
		and b.id_promocion='.$i.' limit 1');
		return $q->result();
	}
	function get_info_evento($id)
	{
		$q=$this->db->query("SELECT * from evento where id=".$id);
		return $q->result();
		
	}
}