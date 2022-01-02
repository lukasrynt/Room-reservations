-- after insert you have to manually set group manager id to each group,
-- group manager id goes from 5 (Katedra aplikované matematiky) to 10 (Subkatedra softwarového inženýrství - webové inženýrství)
insert into "group" (id, name, group_manager_id, parent_id)
values (
           nextval('group_id_seq'), 'Katedra aplikované matematiky', null, null
       ),
       (
           nextval('group_id_seq'), 'Katedra softwarového inženýrství', null, null
       ),
       (
           nextval('group_id_seq'), 'Subkatedra aplikované matematiky', null, 1
       ),
       (
           nextval('group_id_seq'), 'Subkatedra softwarového inženýrství - softwarového inženýrství', null, 2
       ),
       (
           nextval('group_id_seq'), 'Katedra teoretické informatiky', null, null
       ),
       (
           nextval('group_id_seq'), 'Subkatedra softwarového inženýrství - webové inženýrství', null, 2
       );
