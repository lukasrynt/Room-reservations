-- hesla jsou všechny stejná: heslo123
insert into "user" (id, first_name, last_name, email, phone_number, role, note, roles, password, username, discr, group_id)
values (
           nextval('user_id_seq'), 'Lukáš', 'Rynt', 'ryntluka@fit.cvut.cz', 777888999, 'COMMON_USER', 'something', '{"roles": "ROLE_ADMIN"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'luki', 'user', 1
       ),
       (
           nextval('user_id_seq'), 'Martin', 'Šír', 'sirmart@fit.cvut.cz', 777888999, 'ADMIN', 'some pretty note', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'martin', 'admin', 1
       ),
       (
           nextval('user_id_seq'), 'Markéta', 'Minářová', 'minarma@fit.cvut.cz', 777888999, 'COMMON_USER', 'cool', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'maki', 'user', 1
       ),
       (
           nextval('user_id_seq'), 'Daniel', 'Honys', 'honysdan@fit.cvut.cz', 777888999, 'COMMON_USER', 'something else', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'dan', 'roomManager', 1
       ),
       (
           nextval('user_id_seq'), 'Jan', 'Novák', 'honysdan@fit.cvut.cz', 777888999, 'GROUP_ADMIN', 'something pretty', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'jan', 'groupManager', 1
       );