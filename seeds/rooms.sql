insert into room (id, capacity, name, floor, opened_from, opened_to)
values (
            nextval('room_id_seq'), 20, '320', 3, '10:00:00', '18:00:00'
       ),
       (
           nextval('room_id_seq'), 20, '321', 3, '10:00:00', '19:00:00'
       );