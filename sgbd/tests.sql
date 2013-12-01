insert into DVedette values ('Animal', NULL, NULL);
insert into DVedette values ('Vertébré', (select ref(d) from DVedette d where d.libelle='Animal'), NULL);
insert into DVedette values ('Félin', (select ref(d) from DVedette d where d.libelle='Vertébré'), NULL);
insert into DVedette values ('Chat', (select ref(d) from DVedette d where d.libelle='Félin'), NULL);
