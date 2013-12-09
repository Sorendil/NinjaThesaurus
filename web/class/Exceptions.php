<?php

class NotFoundException extends Exception
{
  echo 'Descripteur non trouv&eacute;.';
}

class AlreadyExistingException extends Exception
{
  echo 'Decripteur d&eacute;j&agrave; existant.';
}

class WrongArgsException extends Exception
{
  echo 'Param&egrave;tres incorrect.';
}
