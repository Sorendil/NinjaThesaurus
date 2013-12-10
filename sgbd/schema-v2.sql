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


-- LE CHAMP ID PERMET D'ÊTRE UTILISÉ COMME CLÉ PRIMAIRE (ON NE PEUT PAS UTILISER
-- LES REF POUR ÇA. IL SERA COMPOSÉ DE LA CONTATÉNATION DU DESCRIPTEUR1+DESCRIPTEUR2+LIBRELATION
CREATE OR REPLACE TYPE RELATION_T AS OBJECT
(ID VARCHAR2(105),
REF_SRC REF DESCRIPTEUR_T,
REF_DST REF DESCRIPTEUR_T,
REF_TYPE REF TYPE_RELATION_T)
/




-- CRÉATION DE TRIGGERS

CREATE OR REPLACE TRIGGER CHK_MATCH_RELATION
BEFORE UPDATE OR INSERT ON RELATIONS
FOR EACH ROW
DECLARE
 src_vedette number(1);
 ref_src ref descripteur_t;
 ref_dst ref descripteur_t;
 dst_vedette number(1);
 ref_type_rel ref type_relation_t;
 rel_type_src number(1);
 rel_type_dst number(1);
BEGIN
	select ref(tr) into ref_type_rel
	from types_relations tr
	where ref_type_rel = :new.ref_type;
	
	select vedette_src into rel_type_src
	from types_relations tr
	where ref_type_rel = :new.ref_type;
	
	select vedette_dst into rel_type_dst
	from types_relations tr
	where ref_type_rel = :new.ref_type;
	
	
	
	--select vedette_src into src_vedette, vedette_dst into dst_vedette, ref(tr) into ref_type_rel
	--from types_relations tr
	--where ref_type_rel = :new.ref_type;
	
	select ref (d) into ref_src
	from descripteurs d
	where ref_src = :new.ref_src;
	
	select vedette into src_vedette
	from descripteurs d
	where ref_src = :new.ref_src;
	
	--select vedette into src_vedette, ref (d) into ref_src
	--from descripteurs d
	--where ref_src = :new.ref_src;
	
	select ref (d) into ref_dst
	from descripteurs d
	where ref_dst = :new.ref_dst;
	
	select vedette into dst_vedette
	from descripteurs d
	where ref_dst = :new.ref_dst;
	
	--select vedette into dst_vedette, ref (d) into ref_dst
	--from descripteurs d
	--where ref_dst = :new.ref_dst;
	
  if src_vedette <> rel_type_src then
    raise_application_error(-20001,'Association de ces descripteurs avec ce type de relation est impossible');
  end if;
  
  
  if dst_vedette <> rel_type_dst then
    raise_application_error(-20002,'Association de ces descripteurs avec ce type de relation est impossible');
  end if;
END;
/




-- CRÉATION DES TABLES

CREATE TABLE DESCRIPTEURS OF DESCRIPTEUR_T
(PRIMARY KEY (LIBELLE))
/


CREATE TABLE RELATIONS OF RELATION_T
(PRIMARY KEY (ID))
/


CREATE TABLE TYPES_RELATIONS OF TYPE_RELATION_T
(PRIMARY KEY (LIBELLE))
/
