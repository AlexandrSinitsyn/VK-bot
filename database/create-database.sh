#!/bin/bash

sudo -u postgres createuser "${POSTGRES_USER}"
sudo -u postgres psql -c "CREATE ROLE ${POSTGRES_USER_ROLE} LOGIN PASSWORD ${POSTGRES_PASSWORD};"
#sudo -u postgres createdb "${POSTGRES_DB}"
sudo -u postgres psql -c "CREATE DATABASE ${POSTGRES_DB} WITH OWNER = ${POSTGRES_USER};"

sudo -u postgres psql -c "ALTER USER ${POSTGRES_USER} WITH ENCRYPTED PASSWORD ${POSTGRES_PASSWORD};"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE ${POSTGRES_DB} TO ${POSTGRES_USER};"

#sudo psql postgres -U "${POSTGRES_USER}" -d "${POSTGRES_DB}" -f init.sql
sudo cat init.sql | PGPASSWORD="${POSTGRES_PASSWORD}" psql -p5432 -U "${POSTGRES_USER}" -d "${POSTGRES_DB}"
