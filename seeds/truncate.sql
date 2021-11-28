TRUNCATE "user", room, building, room_user, request_user, request, "group" CASCADE;
ALTER SEQUENCE user_id_seq RESTART WITH 1;
ALTER SEQUENCE room_id_seq RESTART WITH 1;
ALTER SEQUENCE building_id_seq RESTART WITH 1;
ALTER SEQUENCE request_id_seq RESTART WITH 1;
ALTER SEQUENCE group_id_seq RESTART WITH 1;