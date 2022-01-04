insert into room (id, capacity, name, floor, opened_from, opened_to, building_id, room_manager_id, group_id, private)
values (
            nextval('room_id_seq'), 20, 'Knihovna - 320', 3, '10:00:00', '18:00:00', 1, 11, 2, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'Knihovna - 321', 1, '10:00:00', '19:00:00', 1, 11, 2, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'Knihovna - 322', 3, '10:00:00', '19:00:00', 1, 11, 2, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'Knihovna - 323', 2, '10:00:00', '19:00:00', 1, 11, 2, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'Knihovna - 324', 3, '10:00:00', '19:00:00', 1, 11, 2, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'Knihovna - 325', 5, '10:00:00', '19:00:00', 1, 11, 2, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'B 320', 3, '10:00:00', '19:00:00', 2, 12, 3, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'B 321', 3, '10:00:00', '19:00:00', 2, 12, 3, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'B 322', 3, '10:00:00', '19:00:00', 2, 12, 3, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'B 323', 5, '10:00:00', '19:00:00', 2, 12, 3, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'B 324', 3, '10:00:00', '19:00:00', 2, 12, 3, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'B 325', 3, '10:00:00', '19:00:00', 2, 12, 3, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'C 320', 3, '10:00:00', '19:00:00', 3, 13, 4, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'C 321', 3, '10:00:00', '19:00:00', 3, 13, 4, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'C 322', 4, '10:00:00', '19:00:00', 3, 13, 4, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'C 323', 3, '10:00:00', '19:00:00', 3, 13, 4, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'C 324', 2, '10:00:00', '19:00:00', 3, 13, 4, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'C 325', 3, '10:00:00', '19:00:00', 3, 13, 4, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'D 320', 3, '10:00:00', '19:00:00', 4, 14, 5, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'D 321', 3, '10:00:00', '19:00:00', 4, 14, 5, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'D 322', 3, '10:00:00', '19:00:00', 4, 14, 5, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'D 323', 8, '10:00:00', '19:00:00', 4, 14, 5, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'D 324', 7, '10:00:00', '19:00:00', 4, 14, 5, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'D 325', 3, '10:00:00', '19:00:00', 4, 14, 5, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'E 320', 3, '10:00:00', '19:00:00', 5, 15, 6, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'E 321', 3, '10:00:00', '19:00:00', 5, 15, 6, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'E 322', 2, '10:00:00', '19:00:00', 5, 15, 6, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'E 323', 3, '10:00:00', '19:00:00', 5, 15, 6, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'E 324', 4, '10:00:00', '19:00:00', 5, 15, 6, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'E 325', 3, '10:00:00', '19:00:00', 5, 15, 6, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'F 320', 3, '10:00:00', '19:00:00', 6, 4, 1, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'F 321', 7, '10:00:00', '19:00:00', 6, 4, 1, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'F 322', 3, '10:00:00', '19:00:00', 6, 4, 1, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'F 323', 7, '10:00:00', '19:00:00', 6, 4, 1, FALSE
       ),
       (
           nextval('room_id_seq'), 20, 'F 324', 3, '10:00:00', '19:00:00', 6, 4, 1, TRUE
       ),
       (
           nextval('room_id_seq'), 20, 'F 325', 3, '10:00:00', '19:00:00', 6, 4, 1, FALSE
       );