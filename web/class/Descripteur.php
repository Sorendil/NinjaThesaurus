<?php

class Descripteur
{
  private $libelle, $vedette, $relations=array(), $db;

  public function __construct ($db, $libelle, $vedette=NULL)
  {
    if (isset($vedette))
    {
      $query = oci_parse ($db, 'insert into descripteurs values (:lib,:vedette)');
      oci_bind_by_name ($query, ":lib", $libelle);
      oci_bind_by_name ($query, ":vedette", $vedette); 
      oci_execute ($query);

      if (oci_error ($db))
        throw new AlreadyExistingException();
    }

    else
    {
      $query = oci_parse ($db, 'select * from descripteurs where libelle=:lib');
      oci_bind_by_name ($query, ":lib", $libelle);
      oci_execute ($query);
      $n_res = oci_fetch_all ($query, $res);
      if ($n_res == 0)
        throw new NotFoundException();

      /* récupération relations sortantes */	
      $query = oci_parse ($db, 'select deref(r.ref_dst).libelle as libelle_desc, deref(r.ref_type).libelle as libelle_rel, 0 as sens from relations r where r.ref_src=(select ref(d) from descripteurs d where libelle=:lib)');
      oci_bind_by_name ($query, ":lib", $libelle);
      oci_execute ($query);
      $n_res = oci_fetch_all ($query, $res_sortantes);

      /* récupération relations entrantes */
      $query = oci_parse ($db, 'select deref(r.ref_src).libelle as libelle_desc, deref(r.ref_type).libelle as libelle_rel, 1 as sens from relations r where r.ref_dst=(select ref(d) from descripteurs d where libelle=:lib)');
      oci_bind_by_name ($query, ":lib", $libelle);
      oci_execute ($query);
      $n_res = oci_fetch_all ($query, $res_entrantes);  		

      $this->relations['LIBELLE_DESC'] = array_merge ($res_sortantes['LIBELLE_DESC'], $res_entrantes['LIBELLE_DESC']);
      $this->relations['LIBELLE_REL'] = array_merge ($res_sortantes['LIBELLE_REL'], $res_entrantes['LIBELLE_REL']);
      $this->relations['SENS'] = array_merge ($res_sortantes['SENS'], $res_entrantes['SENS']);
    }

    $this->db = $db;
    $this->libelle = $libelle;
    $this->vedette = $vedette;
  }

  public function getLibelle ()
  {
    return $this->libelle;
  }

  public function getVedette ()
  {
    return $this->vedette;
  }

  public function getRel ()
  {
    /* format du tableau $relations
     * LIBELLE_DESC | LIBELLE_REL | SENS
     * sens=0 : sortante
     * sens=1 : entrante
     * */
    return $this->relations;
  }

  public function addRel ($dest_lib, $rel_lib)
  {
    $query = oci_parse ($this->db, 'insert into relations values ((select ref(d1) from descripteurs d1 where d1.libelle=:lib1), (select ref(d2) from descripteurs d2 where d2.libelle=:lib2), (select ref(r) from types_relations r where r.libelle=:rel))');
    oci_bind_by_name ($query, ":lib1", $this->libelle);
    oci_bind_by_name ($query, ":lib2", $dest_lib);
    oci_bind_by_name ($query, ":rel", $rel_lib);
    oci_execute ($query);
    
    if (is_array(oci_error($this->db)))
      return true;
    return false;
  }
}
