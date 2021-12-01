insert into reservation (id, user_id, room_id, date_to, date_from)
values (
           nextval('reservation_id_seq'), 1, 1, '2021-11-17 15:55:17', '2021-11-22 15:55:17'
       ),
       (
           nextval('reservation_id_seq'), 2, 2, '2021-11-17 15:55:17', '2021-11-22 15:55:17'
       );