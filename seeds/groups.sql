insert into "group" (id, name, group_manager_id, parent_id)
values (
           nextval('group_id_seq'), 'Pilni studenti', null, null
       ),
       (
           nextval('group_id_seq'), 'Ne moc dobri studenti', null, null
       ),
       (
           nextval('group_id_seq'), 'Trochu mene pilni studenti', null, 1
       ),
       (
           nextval('group_id_seq'), 'Jeste min pilni studenti', null, 3
       );
