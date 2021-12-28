insert into room (id, capacity, name, floor, opened_from, opened_to, building_id, room_manager_id, group_id, private)
values (
            nextval('room_id_seq'), 20, '320', 3, '10:00:00', '18:00:00', 1, 4, 1, TRUE
       ),
       (
           nextval('room_id_seq'), 20, '321', 3, '10:00:00', '19:00:00', 2, null, 2, FALSE
       );