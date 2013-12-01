-- CRÉATION DE TYPES DE DONNÉES
CREATE OR REPLACE TYPE DESCRIPTEUR_T AS OBJECT
(LIBELLE VARCHAR2 (35))
NOT FINAL
NOT INSTANTIABLE
/

CREATE OR REPLACE TYPE DNORMAL_T UNDER DESCRIPTEUR_T
/

CREATE OR REPLACE TYPE DNORMAL_NESTED_T AS TABLE OF DNORMAL_T
/

CREATE OR REPLACE TYPE DVEDETTE_NESTED_T AS TABLE OF DVEDETTE_T
/

CREATE OR REPLACE TYPE DVEDETTE_T UNDER DESCRIPTEUR_T
(FILS DVEDETTE_NESTED_T,
SYNONYMES DNORMA_NESTED_T)
/


-- CRÉATION DE MÉTHODES


-- CRÉATION DES TABLES
CREATE TABLE DVEDETTE OF DVEDETTE_T
(PRIMARY KEY (LIBELLE))
NESTED TABLE SYNONYMES STORE AS DVEDETTE_SYNONYMES,
NESTED TABLE FILS STORE AS DVEDETTE_FILS;
