<?php 
  define('IMAGE',                 'images/default.png');
  define('IMAGE_URL',                 'admin');
  define('PDF_URL',                 'admin/');

  define('FEMENINO',                 'femenino');
  define('MASCULINO',                'masculino');

  define('CHECKED',                'checked');
  define('NO_CHECKED',             '');

  define('PASS',     	 '/^(?=^.{4,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/');
  define('EMAIL',        '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/');
  define('CODE',         '/^([A-Za-z0-9\-]{5,10})+$/');
  define('NAME',         '/^(([A-Za-záéíóúñ]{2,})|([A-Za-záéíóúñ]{2,}[\s][A-Za-záéíóúñ\s]{2,}))+$/');
  define('LAST_NAME',    '/^(([A-Za-záéíóúñ]{2,})|([A-Za-záéíóúñ]{2,}[\s][A-Za-záéíóúñ]{2,}))+$/'); 
  define('DESCRIPTION',  '/^(([A-Za-záéíóúñ0-9\-\/\.\, ]{1,}))+$/');
  define('ALPHANUMERIC',  '/^(([A-Za-záéíóúñ0-9 ]{1,}))+$/');
  define('COLOR',        '/^([A-Za-z0-9\-]{2,})+$/');
  define('PHONE',        '/^([0-9]{1,})+$/');
  define('DATE_TIME',    '/^(19|20)\d{2}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01]) (0?[1-9]|1[012]):([0-5][0-9]) (am|pm|AM||PM)+$/');
  define('DATE',    	 '/^(19|20)\d{2}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])+$/');
  define('INT',      	 '/^([0-9]{1,})+$/');
  define('NUMBER',        '/^([0-9]{1,})\.([0-9]{1,})+$/'); 

  define('ROW_TAG_START',               '<!--row-->');
  define('ROW_TAG_END',                 '<!--.row-->');

  define('PRODUCTO_TAG_START',          '<!--producto-->');
  define('PRODUCTO_TAG_END',            '<!--.producto-->');

  define('DOMICILIO_TAG_START',         '<!--domicilio-->');
  define('DOMICILIO_TAG_END',           '<!--.domicilio-->');

  define('PAIS_TAG_START',               '<!--pais-->');
  define('PAIS_TAG_END',                 '<!--.pais-->');

  define('ESTADO_TAG_START',              '<!--estado-->');
  define('ESTADO_TAG_END',                '<!--.estado-->');

  define('MUNICIPIO_TAG_START',           '<!--municipio-->');
  define('MUNICIPIO_TAG_END',             '<!--.municipio-->');

  define('TELEFONO_TAG_START',            '<!--telefono-->');
  define('TELEFONO_TAG_END',              '<!--.telefono-->');

  define('ROL_TAG_START',                 '<!--rol-->');
  define('ROL_TAG_END',                   '<!--.rol-->');

  define('TALLA_TAG_START',               '<!--talla-->');
  define('TALLA_TAG_END',                 '<!--.talla-->');

  define('CATEGORIA_TAG_START',           '<!--categoria-->');
  define('CATEGORIA_TAG_END',             '<!--.categoria-->');

  define('SUBCATEGORIA_TAG_START',         '<!--subcategoria-->');
  define('SUBCATEGORIA_TAG_END',           '<!--.subcategoria-->');


  define('SUBCATEGORIA_DAMA_TAG_START',         '<!--subcategoria-dama-->');
  define('SUBCATEGORIA_DAMA_TAG_END',           '<!--.subcategoria-dama-->');

  define('SUBCATEGORIA_CABALLERO_TAG_START',         '<!--subcategoria-caballero-->');
  define('SUBCATEGORIA_CABALLERO_TAG_END',           '<!--.subcategoria-caballero-->');

  define('SUBCATEGORIA_INFANTIL_TAG_START',         '<!--subcategoria-infantil-->');
  define('SUBCATEGORIA_INFANTIL_TAG_END',           '<!--.subcategoria-infantil-->');

  define('COLOR_TAG_START',               '<!--color-->');
  define('COLOR_TAG_END',                 '<!--.color-->');



  define('DESCUENTO_TAG_START',               '<!--descuento-->');
  define('DESCUENTO_TAG_END',                 '<!--.descuento-->');

  define('OFERTA_TAG_START',               '<!--oferta-->');
  define('OFERTA_TAG_END',                 '<!--.oferta-->');

  define('CATALOGO_TAG_START',               '<!--catalogo-->');
  define('CATALOGO_TAG_END',                 '<!--.catalogo-->');


  define('CATALOGO_DAMA_TAG_START',               '<!--catalogo-dama-->');
  define('CATALOGO_DAMA_TAG_END',                 '<!--.catalogo-dama-->');

  define('CATALOGO_CABALLERO_TAG_START',               '<!--catalogo-caballero-->');
  define('CATALOGO_CABALLERO_TAG_END',                 '<!--.catalogo-caballero-->');

  define('CATALOGO_INFANTIL_TAG_START',               '<!--catalogo-infantil-->');
  define('CATALOGO_INFANTIL_TAG_END',                 '<!--.catalogo-infantil-->');

  define('CATALOGO_ESPECIAL_TAG_START',               '<!--catalogo-especial-->');
  define('CATALOGO_ESPECIAL_TAG_END',                 '<!--.catalogo-especial-->');

  define('FILTRO_TAG_START',               '<!--filtro-->');
  define('FILTRO_TAG_END',                 '<!--.filtro-->');

?>