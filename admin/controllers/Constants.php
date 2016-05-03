<?php 
  define('FEMENINO',                 'femenino');
  define('MASCULINO',                'masculino');

  define('CHECKED',                'checked');
  define('NO_CHECKED',             '');

  define('PASS',     	 '/^(?=^.{4,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/');
  define('EMAIL',        '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/');
  define('CODE',         '/^([A-Za-z0-9\-]{5,10})+$/');
  define('NAME',         '/^(([A-Za-záéíóúñ]{2,})|([A-Za-záéíóúñ]{2,}[\s][A-Za-záéíóúñ]{2,}))+$/');
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



  define('TELEFONO_TAG_START',           '<!--telefono-->');
  define('TELEFONO_TAG_END',             '<!--.telefono-->');

  define('ROL_TAG_START',                '<!--rol-->');
  define('ROL_TAG_END',                 '<!--.rol-->');

  define('TALLA_TAG_START',                '<!--talla-->');
  define('TALLA_TAG_END',                 '<!--.talla-->');

  define('CATEGORIA_TAG_START',                '<!--categoria-->');
  define('CATEGORIA_TAG_END',                 '<!--.categoria-->');
?>