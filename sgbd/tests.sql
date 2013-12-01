-- ajout racine
insert into dvedette values (dvedette_t('Animal', NULL, dvedette_ref_nested_t(), NULL));

-- ajout noeud
insert into dvedette values ('Vertébré', (select ref(d) from dvedette d where d.libelle='Animal'), dvedette_ref_nested_t(), NULL);
insert into table (select d.fils from dvedette d where d.libelle='Animal') values (dvedette_ref_t (select ref(d2) from dvedette d2 where d2.libelle='Vertébré'));


insert into DVedette values ('Félin', (select ref(d) from DVedette d where d.libelle='Vertébré'), NULL);
insert into DVedette values ('Chat', (select ref(d) from DVedette d where d.libelle='Félin'), NULL);
