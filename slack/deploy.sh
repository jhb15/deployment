#!/bin/sh
exec 2>&1
ssh -i /var/www/aberfitness.key dkm2@docker2.aberfitness.biz "cd /shared/deployment && git pull && docker-compose down && docker-compose pull && docker-compose up -d"
