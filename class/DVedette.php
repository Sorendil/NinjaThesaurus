<?php

class DVedette extends Descripteur
{
  private $synonymes = array ();
  private $fils = array ();

  public function __construct ($id=NULL, $libelle=NULL)
  {
    parent::__construct($id, $libelle);
  }

  public function supprimer ()
  {
    echo 'DVedette supprimer <br />';
  }

  public function creerSynonyme ($libelle)
  {
    echo "DVedette creeSynonyme $libelle <br />";
  }

  public function creerFils ($libelle)
  {
    echo "DVedette creeFils $libelle <br />";
  }

  public function getPere ()
  {
    return $this->pere;
  }

  public function getFils ()
  {
    return $this->fils;
  }

  public function getSynonymes ()
  {
    return $this->synonymes;
  }
}
