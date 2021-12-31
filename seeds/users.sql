-- hesla jsou všechny stejná: heslo123
insert into "user" (id, first_name, last_name, email, phone_number, note, roles, password, username, discr, group_id)
values (
           nextval('user_id_seq'), 'Lukáš', 'Rynt', 'ryntluka@fit.cvut.cz', 777888999, 'something', '{"roles": "ROLE_ADMIN"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'luki', 'admin', 1
       ),
       (
           nextval('user_id_seq'), 'Martin', 'Šír', 'sirmart@fit.cvut.cz', 777888999, 'some pretty note', '{"roles": "ROLE_ADMIN"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'martin', 'admin', 1
       ),
       (
           nextval('user_id_seq'), 'Markéta', 'Minářová', 'minarma@fit.cvut.cz', 777888999, 'cool', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'maki', 'user', 1
       ),
       (
           nextval('user_id_seq'), 'Daniel', 'Honys', 'honysdan@fit.cvut.cz', 777888999, 'something else', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'dan', 'roomManager', 1
       ),
       (
           nextval('user_id_seq'), 'Jan', 'Novák', 'honysdan@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'jan', 'groupManager', 1
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 2', 'test2@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test2', 'groupManager', 2
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 3', 'test3@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test3', 'groupManager', 3
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 4', 'test4@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test4', 'groupManager', 4
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 5', 'test5@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test5', 'groupManager', 5
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 6', 'test6@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test6', 'groupManager', 6
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 7', 'test7@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test7', 'groupManager', 1
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 8', 'test8@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test8', 'groupManager', 1
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 9', 'test9@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test9', 'groupManager', 1
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 10', 'test10@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_GROUP_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test10', 'groupManager', 1
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 11', 'test11@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test11', 'roomManager', 2
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 12', 'test12@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test12', 'roomManager', 3
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 13', 'test13@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test13', 'roomManager', 4
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 14', 'test14@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test14', 'roomManager', 5
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 15', 'test15@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_ROOM_MANAGER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test15', 'roomManager', 6
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 16', 'test16@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test16', 'user', 2
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 17', 'test17@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test17', 'user', 2
       ),
       (
           nextval('user_id_seq'), 'Testovací', 'uživatel 18', 'test18@fit.cvut.cz', 777888999, 'something pretty', '{"roles": "ROLE_USER"}', '$2y$13$T8/IskRpUciHepQPN0.2UeS9yXzRo4CvH.rBOvIne58D2kqg1f5q.', 'test18', 'user', 2
       );