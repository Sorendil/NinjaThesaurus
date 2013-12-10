-- CRÉATION DE TYPES DE DONNÉES

CREATE OR REPLACE TYPE DESCRIPTEUR_T AS OBJECT
(LIBELLE VARCHAR2 (35),
VEDETTE NUMBER(1))
/


CREATE OR REPLACE TYPE TYPE_RELATION_T AS OBJECT
(LIBELLE VARCHAR2(35),
VEDETTE_SRC NUMBER(1),
VEDETTE_DST NUMBER(1))
/


CREATE OR REPLACE TYPE RELATION_T AS OBJECT
(REF_SRC REF DESCRIPTEUR_T,
REF_DST REF DESCRIPTEUR_T,
REF_TYPE REF TYPE_RELATION_T)
/




-- CRÉATION DE TRIGGERS

CREATE OR REPLACE TRIGGER CHK_MATCH_RELATION
BEFORE UPDATE OR INSERT ON RELATIONS
FOR EACH ROW
DECLARE
 vedette_desc_src number(1);
 vedette_desc_dst number(1);
 vedette_type_src number(1);
 vedette_type_dst number(1);
BEGIN

	-- récupérations des booleens vedettes imposés par le type de relation
	select vedette_src into vedette_type_src from types_relations tr where ref(tr)=:new.ref_type;
	select vedette_dst into vedette_type_dst from types_relations tr where ref(tr)=:new.ref_type;
	
	-- récupération du booleen vedette du descripteur source
	select vedette into vedette_desc_src from descripteurs d where ref(d)=:new.ref_src;
	
	-- récupération du booleen vedette du descripteurs destination
	select vedette into vedette_desc_dst from descripteurs d where ref(d)=:new.ref_dst;

  if vedette_desc_src <> vedette_type_src then
    raise_application_error(-20001,'Association de ces descripteurs avec ce type de relation est impossible');
  end if;
  
  
  if vedette_desc_dst <> vedette_type_dst then
    raise_application_error(-20002,'Association de ces descripteurs avec ce type de relation est impossible');
  end if;
END;
/


CREATE OR REPLACE TRIGGER CHK_UNIQUE_RELATION
BEFORE UPDATE OR INSERT ON RELATIONS
FOR EACH ROW
DECLARE
 count_number number;
BEGIN
	select count(*) into count_number from relations r where r.ref_src=:new.ref_src and r.ref_dst=:new.ref_dst and r.ref_type=:new.ref_type;
  if count_number > 0 then
    raise_application_error(-20003,'Relation déjà existante');
  end if;
END;
/


-- CRÉATION DES TABLES

CREATE TABLE DESCRIPTEURS OF DESCRIPTEUR_T
(PRIMARY KEY (LIBELLE))
/


CREATE TABLE RELATIONS OF RELATION_T
/


CREATE TABLE TYPES_RELATIONS OF TYPE_RELATION_T
(PRIMARY KEY (LIBELLE))
/
