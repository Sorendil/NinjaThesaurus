<?php

class Descripteur
{
  private $id, $libelle, $pere;

  public function __construct ($id=NULL, $libelle=NULL)
  {
    if ($id != NULL)
      $this->id = $id;

    if ($libelle != NULL)
      $this->libelle = $libelle;
  }

  public function modifier ($libelle)
  {
    echo "Descripteur modifier $libelle <br />";
  }

  public function getId ()
  {
    return $this->id;
  }

  public function setId ($id)
  {
    $this->id = $id; /* TODO */
  }

  public function getLibelle ()
  {
    return $this->libelle;
  }

  public function setLibelle ()
  {
    $this->libelle = $libelle; /* TODO */
  }

  public function push ()
  {
    /* enregistre l'objet sur la bdd (ou modifie) */
  }

  public function pull ()
  {
    /* rempli l'objet à partir de la bdd en fonction de son id ou de son libelle */
  }
}
