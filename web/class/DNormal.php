<?php

class DNormal extends Descripteur
{
  public function __construct ($id=NULL, $libelle=NULL)
  {
    parent::__construct($id, $libelle);
  }

  public function supprimer ()
  {
    echo 'DNormal supprimer <br />';
  }
}
