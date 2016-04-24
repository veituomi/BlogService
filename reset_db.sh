#!/bin/bash

source config/environment.sh

echo "Resetting database..."

ssh $USERNAME@users.cs.helsinki.fi "
cd htdocs/$PROJECT_FOLDER/sql
cat drop_tables.sql create_tables.sql add_test_data.sql | psql -1 -f -
exit"

echo "Done!"
