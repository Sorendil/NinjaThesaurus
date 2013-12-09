<?php

class Descripteur
{
  private $libelle, $vedette, $relations=array(), $db;

  public function __construct ($db, $libelle, $vedette=NULL)
  {
    if (isset($vedette))
    {
      if (($vedette != 1) || ($vedette != 0))
        throw new WrongArgsException();
      else
      {
        $query = oci_parse ($db, 'insert into descripteurs values (:lib,:vedette)');
        oci_bind_by_name ($query, ":lib", $libelle);
        oci_bind_by_name ($query, ":vedette", $vedette); 
        oci_execute ($query);

        if (oci_error ($db))
          throw new AlreadyExistingException();
      }
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
      $query = oci_parse ($db, 'select deref(r).libelle as libelle_desc, deref(r).libelle as libelle_rel, 0 
        from relations r where ref_src=(select ref(d) from descripteur d where libelle=:lib)');
      oci_bind_by_name ($query, ":lib", $libelle);
      oci_execute ($query);
      $n_res = oci_fetch_all ($query, $res_sortante);

      /* récupération relations entrantes */
      $query = oci_parse ($db, 'select deref(r).libelle as libelle_desc, deref(r).libelle as libelle_rel, 1
        from relations r where ref_dst=(select ref(d) from descripteur d where libelle=:lib)');
      oci_bind_by_name ($query, ":lib", $libelle);
      oci_execute ($query);
      $n_res = oci_fetch_all ($query, $res_entrantes);  		

      $relations = array_merge ($res_sortantes, $res_entrantes);
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
     * LIBELLE_DESC | LIBELLE_REL | 0 (sortante) ou 1 (entrante) */
    return $relations;
  }

  public function addRel ($lib, $rel)
  {
  /* essaye d'ajouter une relation
  plusieurs erreur : $lib non existante
           $rel non existante
           booleans faux
   */
  }
}
