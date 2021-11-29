insert into "user" (id, first_name, last_name, email, phone_number, role, note, roles, password, username, plain_password, discr)
values (
           nextval('user_id_seq'), 'Lukáš', 'Rynt', 'ryntluka@fit.cvut.cz', 777888999, 'COMMON_USER', 'something', '{"roles": "ROLE_ADMIN"}', 'heslo', 'luki', 'heslo', 'user'
       ),
       (
           nextval('user_id_seq'), 'Martin', 'Šír', 'sirmart@fit.cvut.cz', 777888999, 'ADMIN', 'some pretty note', '{"roles": "ROLE_USER"}', 'heslo', 'martin', 'heslo', 'admin'
       ),
       (
           nextval('user_id_seq'), 'Markéta', 'Minářová', 'minarma@fit.cvut.cz', 777888999, 'COMMON_USER', 'cool', '{"roles": "ROLE_USER"}', 'heslo', 'maki', 'heslo', 'user'
       ),
       (
           nextval('user_id_seq'), 'Daniel', 'Honys', 'honysdan@fit.cvut.cz', 777888999, 'COMMON_USER', 'something else', '{"roles": "ROLE_USER"}', 'heslo', 'dan', 'heslo', 'roomManager'
       ),
       (
           nextval('user_id_seq'), 'Jan', 'Novák', 'honysdan@fit.cvut.cz', 777888999, 'GROUP_ADMIN', 'something pretty', '{"roles": "ROLE_USER"}', 'heslo', 'jan', 'heslo', 'groupManager'
       );