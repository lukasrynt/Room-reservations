#!/bin/bash
PGPASSWORD=postgres psql -h localhost -p 5432 -d hmsr_db -U postgres -a -f seeds/users.sql