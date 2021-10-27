#!/bin/bash
PGPASSWORD=postgres psql -h localhost -p 5432 -d hmsr_db -U postgres -a -f users.sql
PGPASSWORD=postgres psql -h localhost -p 5432 -d hmsr_db -U postgres -a -f buildings.sql
PGPASSWORD=postgres psql -h localhost -p 5432 -d hmsr_db -U postgres -a -f rooms.sql