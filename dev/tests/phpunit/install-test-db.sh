#!/usr/bin/env bash

validate_mysql_installed() {
    if type mysql >/dev/null 2>&1; then
        echo "Mysql is installed, continuing..."
    else
        echo "The MySQL client is not installed on your Mac, installing now..."
        echo ""

        echo "Updating brew..."
        brew update

        echo "Installing MySQL..."
        brew install mysql
        exit
    fi
}

create_db() {
    mysql -u root -p password -h 127.0.0.1 -P 3307 -e "DROP DATABASE IF EXISTS wp_phpunit_tests; CREATE DATABASE IF NOT EXISTS wp_phpunit_tests"
    echo "Database wp_phpunit_tests created successfully."
}

validate_mysql_installed
create_db
