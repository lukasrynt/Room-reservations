insert into request (id, date_from, date_to, state, room_id, requestor_id)
values (
           nextval('request_id_seq'), '2021-11-17 15:55:17', '2021-11-22 15:55:17', 'PENDING', 1, 1
       ),
       (
           nextval('request_id_seq'), '2021-11-17 15:55:17', '2021-11-22 15:55:17', 'APPROVED',  2, 2
       );