-- pour les tests, j'utilise des majuscule, mais tout les libelles doivent être minuscule dans la bdd

-- ajout racine animal
insert into dvedette values (dvedette_t('Animal', NULL, dvedette_ref_nested_t(), dnormal_ref_nested_t()));


-- ajout noeud en dessous de animal
insert into dvedette values ('Vertébré', (select ref(d) from dvedette d where d.libelle='Animal'), dvedette_ref_nested_t(), dnormal_ref_nested_t());
insert into table (select d.fils from dvedette d where d.libelle='Animal') values (dvedette_ref_t ((select ref(d2) from dvedette d2 where d2.libelle='Vertébré')));

insert into dvedette values ('Amphibia', (select ref(d) from dvedette d where d.libelle='Animal'), dvedette_ref_nested_t(), dnormal_ref_nested_t());
insert into table (select d.fils from dvedette d where d.libelle='Animal') values (dvedette_ref_t ((select ref(d2) from dvedette d2 where d2.libelle='Amphibia')));


-- ajout de félin et de chat sous vertébré
insert into dvedette values ('Félin', (select ref(d) from dvedette d where d.libelle='Vertébré'), dvedette_ref_nested_t(), dnormal_ref_nested_t());
insert into table (select d.fils from dvedette d where d.libelle='Vertébré') values (dvedette_ref_t ((select ref(d2) from dvedette d2 where d2.libelle='Félin')));

insert into dvedette values ('Chat', (select ref(d) from dvedette d where d.libelle='Félin'), dvedette_ref_nested_t(), dnormal_ref_nested_t());
insert into table (select d.fils from dvedette d where d.libelle='Félin') values (dvedette_ref_t ((select ref(d2) from dvedette d2 where d2.libelle='Chat')));


-- ajout de synonyme à chat
insert into dnormal values (dnormal_t('minou', (select ref(d) from dvedette d where d.libelle='Chat')));
insert into table (select d.synonymes from dvedette d where d.libelle='Chat') values (dnormal_ref_t ((select ref(d2) from dnormal d2 where d2.libelle='minou')));



-- sélections des fils de Animal (ou n'importe quel object)
select deref(t.ref_desc).libelle from table (select fils from dvedette where libelle='Animal') t;

-- sélection des synonymes du descripteur vedette 'chat'
select deref(t.ref_desc).libelle from table (select synonymes from dvedette d where libelle='Chat') t;

