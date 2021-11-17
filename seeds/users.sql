insert into "user" (id, first_name, last_name, email, phone_number, role, note, roles, password, username, plain_password)
values (
           nextval('user_id_seq'), 'Lukáš', 'Rynt', 'ryntluka@fit.cvut.cz', 777888999, 'student', 'something', '{"roles": "ROLE_ADMIN"}', 'heslo', 'luki', 'heslo'
       ),
       (
           nextval('user_id_seq'), 'Martin', 'Šír', 'sirmart@fit.cvut.cz', 777888999, 'student', 'some pretty note', '{"roles": "ROLE_USER"}', 'heslo', 'martin', 'heslo'
       ),
       (
           nextval('user_id_seq'), 'Markéta', 'Minářová', 'minarma@fit.cvut.cz', 777888999, 'student', 'cool', '{"roles": "ROLE_USER"}', 'heslo', 'maki', 'heslo'
       ),
       (
           nextval('user_id_seq'), 'Daniel', 'Honys', 'honysdan@fit.cvut.cz', 777888999, 'student', 'something else', '{"roles": "ROLE_USER"}', 'heslo', 'dan', 'heslo'
       );