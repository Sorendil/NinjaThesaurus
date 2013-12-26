<?php

class Tools
{
  static function parse_libelle ($lib)
  {
    $lib = strtolower ($lib);
    $lib = str_replace ('_', ' ', $lib);
    return $lib;
  }

  static function connect_db()
  {
    include './config_db.php';
    return oci_connect ($login, $password, $host);
  }

  static function disconnect_db($db)
  {
    oci_close ($db);
  }
  
  static function getLibelleRel($db)
  {
	$query = oci_parse($db, "select libelle from types_relations");
	oci_execute ($query);
	$nres=oci_fetch_all($query, $res);
	return $res;
  }
  
}
