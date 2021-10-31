#!/bin/bash
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f users.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f buildings.sql
PGPASSWORD=pgpass psql -h localhost -p 5432 -d pgdb -U pguser -a -f rooms.sql