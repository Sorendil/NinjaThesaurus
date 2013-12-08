-- CRÉATION DE TYPES DE DONNÉES

CREATE OR REPLACE TYPE DESCRIPTEUR_T AS OBJECT
(LIBELLE VARCHAR2 (35))
NOT FINAL
NOT INSTANTIABLE
/

CREATE OR REPLACE TYPE DNORMAL_T UNDER DESCRIPTEUR_T
(REF_VEDETTE REF DVEDETTE_T)
/

CREATE OR REPLACE TYPE DVEDETTE_T UNDER DESCRIPTEUR_T
(REF_PERE REF DVEDETTE_T,
FILS DVEDETTE_REF_NESTED_T,
SYNONYMES DNORMAL_REF_NESTED_T)
/

CREATE OR REPLACE TYPE DNORMAL_REF_NESTED_T AS TABLE OF DNORMAL_REF_T
/

CREATE OR REPLACE TYPE DVEDETTE_REF_NESTED_T AS TABLE OF DVEDETTE_REF_T
/


CREATE OR REPLACE TYPE DNORMAL_REF_T AS OBJECT
(REF_DESC REF DNORMAL_T)
/

CREATE OR REPLACE TYPE DVEDETTE_REF_T AS OBJECT
(REF_DESC REF DVEDETTE_T)
/




-- CRÉATION DE TRIGGERS

CREATE OR REPLACE TRIGGER CHK_CONSTRAINT_UNIQUE_DNORMAL
BEFORE UPDATE OR INSERT ON DNORMAL
FOR EACH ROW
DECLARE
 result number;
BEGIN
	select count(*) into result from dvedette where libelle=:new.libelle;
  if result > 0 then
    raise_application_error(-20001,'Libellé déjà utilisé dans DVEDETTE');
  end if;
 	select count(*) into result from dnormal where libelle=:new.libelle;
  if result > 0 then
    raise_application_error(-20001,'Libellé déjà utilisé dans DNORMAL');
  end if;
END;
/

CREATE OR REPLACE TRIGGER CHK_CONSTRAINT_UNIQUE_DVEDETTE
BEFORE UPDATE OR INSERT ON DVEDETTE
FOR EACH ROW
DECLARE
 result number;
BEGIN
	select count(*) into result from dvedette where libelle=:new.libelle;
  if result > 0 then
    raise_application_error(-20001,'Libellé déjà utilisé dans DVEDETTE');
  end if;
 	select count(*) into result from dnormal where libelle=:new.libelle;
  if result > 0 then
    raise_application_error(-20001,'Libellé déjà utilisé dans DNORMAL');
  end if;
END;
/




-- CRÉATION DES TABLES

CREATE TABLE DNORMAL OF DNORMAL_T
(PRIMARY KEY (LIBELLE),
CONSTRAINT DNORMAL_NOTNULL_REF_VEDETTE REF_VEDETTE NOT NULL);


CREATE TABLE DVEDETTE OF DVEDETTE_T
(PRIMARY KEY (LIBELLE))
NESTED TABLE SYNONYMES STORE AS DVEDETTE_SYNONYMES
NESTED TABLE FILS STORE AS DVEDETTE_FILS;




-- DEBUG

--drop type descripteur_t force;
--drop type dnormal_t force;
--drop type dnormal_ref_t force;
--drop type dvedette_ref_t force;
--drop type dnormal_ref_nested_t force;
--drop type dvedette_ref_nested_t force;
--drop type dvedette_t force;

--lister les triggers : select trigger_name from user_triggers;
--supprimer les triggers : drop trigger <triggername>
