insert into reservation (id, user_id, room_id, date, time_from, time_to)
values (
           nextval('reservation_id_seq'), 1, 1, '2021-11-17', '12:00:00', '18:00:00'
       ),
       (
           nextval('reservation_id_seq'), 2, 2, '2021-11-17', '12:00:00', '18:00:00'
       );