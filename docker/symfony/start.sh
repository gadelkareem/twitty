#!/bin/bash
set -e

start_ts=$(date +%s)
while :
    do
        (printf exit | redis-cli -h redis ping ) >/dev/null 2>&1
        result=$?
        end_ts=$(date +%s)
        if [[ $result -eq 0 ]]; then
            echo "redis is available after $((end_ts - start_ts)) seconds"
            break
        fi
        echo "redis is not available after $((end_ts - start_ts)) seconds"
        sleep 1
    done

while :
    do
        (test -f /app/composer.json) >/dev/null 2>&1
        result=$?
        end_ts=$(date +%s)
        if [[ $result -eq 0 ]]; then
            echo "/app is available after $((end_ts - start_ts)) seconds"
            break
        fi
        echo "/app is not available after $((end_ts - start_ts)) seconds"
        sleep 1
    done


echo "Running composer"
composer install

echo "Starting Server.."
exec ./bin/console server:run 0.0.0.0



