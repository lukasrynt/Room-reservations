#!/bin/bash

PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f truncate.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f reset_seq.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f groups.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f users.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f buildings.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f rooms.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f request.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f room_user.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f reservations.sql