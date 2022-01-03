-- hesla jsou všechny stejná: heslo123
insert into "user" (id, first_name, last_name, email, phone_number, note, roles, password, username, group_id)
values (
           nextval('user_id_seq'), 'Lukáš', 'Rynt', 'ryntluka@fit.cvut.cz', 777888999, 'something', '{"roles": "ROLE_ADMIN"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'luki', 1
       ),
       (
           nextval('user_id_seq'), 'Martin', 'Šír', 'sirmart@fit.cvut.cz', 777888999, 'some pretty note', '{"roles": "ROLE_ADMIN"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'martin', 1
       ),
       (
           nextval('user_id_seq'), 'Markéta', 'Minářová', 'minarma@fit.cvut.cz', 777888999, 'cool', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'maki', 1
       ),
       (
           nextval('user_id_seq'), 'Daniel', 'Honys', 'honysdan@fit.cvut.cz', 777888999, 'Room Manager - 31,32,33,34,35,36', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'dan', 1
       ),
       (
           nextval('user_id_seq'), 'Tomáš', 'Kalvoda', 'honysdan@fit.cvut.cz', 777888999, 'Vedoucí katedry aplikované matematiky', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'tomas', 1
       ),
       (
           nextval('user_id_seq'), 'Anička', 'Nováková', 'test2@fit.cvut.cz', 777888999, 'Vedoucí katedry softwarového inženýrství', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'anicka', 2
       ),
       (
           nextval('user_id_seq'), 'Filip', 'Novák', 'test3@fit.cvut.cz', 777888999, 'Vedoucí subkatedry aplikované matematiky', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'filip', 3
       ),
       (
           nextval('user_id_seq'), 'Ondřej', 'Novák', 'test4@fit.cvut.cz', 777888999, 'Vedoucí subkatedra softwarového inženýrství - softwarového inženýrství', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'ondrej', 4
       ),
       (
           nextval('user_id_seq'), 'Josef', 'Novák', 'test5@fit.cvut.cz', 777888999, 'Vedoucí katedry teoretické informatiky', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'josef', 5
       ),
       (
           nextval('user_id_seq'), 'David', 'Bernahuer', 'test6@fit.cvut.cz', 777888999, 'Vedoucí subkatedry softwarového inženýrství - webové inženýrství', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'david', 6
       ),
       (
           nextval('user_id_seq'), 'Manka', 'Nováková', 'test11@fit.cvut.cz', 777888999, 'Room Manager - 1,2,3,4,5,6', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'manka', 2
       ),
       (
           nextval('user_id_seq'), 'Julča', 'Nováková', 'test12@fit.cvut.cz', 777888999, 'Room Manager - 7,8,9,10,11,12', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'julca', 3
       ),
       (
           nextval('user_id_seq'), 'Vojta', 'Novák', 'test13@fit.cvut.cz', 777888999, 'Room Manager - 13,14,15,16,17,18', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'vojta', 4
       ),
       (
           nextval('user_id_seq'), 'Martina', 'Nováková', 'test14@fit.cvut.cz', 777888999, 'Room Manager - 19,20,21,22,23,24', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'martina', 5
       ),
       (
           nextval('user_id_seq'), 'Roman', 'Novák', 'test15@fit.cvut.cz', 777888999, 'Room Manager - 25,26,27,28,29,30', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'roman', 6
       ),
       (
           nextval('user_id_seq'), 'Žaneta', 'Nováková', 'test16@fit.cvut.cz', 777888999, 'Uživatel patřící pod skupinu 2', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'zaneta', 2
       ),
       (
           nextval('user_id_seq'), 'Evžen', 'Novák', 'test17@fit.cvut.cz', 777888999, 'Uživatel patřící pod skupinu 2', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'evzen', null
       ),
       (
           nextval('user_id_seq'), 'Božena', 'Nováková', 'test18@fit.cvut.cz', 777888999, 'Uživatel patřící pod skupinu 2', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'bozena', null
       ),
       (
           nextval('user_id_seq'), 'Barbora', 'Nováková', 'test18@fit.cvut.cz', 777888999, 'Uživatel patřící pod skupinu 2', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'barbora', null
       ),
       (
           nextval('user_id_seq'), 'Zuzana', 'Nováková', 'test18@fit.cvut.cz', 777888999, 'Uživatel patřící pod skupinu 2', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'zuzana', null
       ),
       (
           nextval('user_id_seq'), 'Lucie', 'Nováková', 'test18@fit.cvut.cz', 777888999, 'Uživatel patřící pod skupinu 2', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'lucie', null
       );

update "group" set group_manager_id = 5 where id = 1;
update "group" set group_manager_id = 6 where id = 2;
update "group" set group_manager_id = 7 where id = 3;
update "group" set group_manager_id = 8 where id = 4;
update "group" set group_manager_id = 9 where id = 5;
update "group" set group_manager_id = 10 where id = 6;