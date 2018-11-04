# deployment
Holds docker files for local and production development

# Running dependencies locally
`docker-compose up`

# Running a service image
`docker-compose -f docker-compose.ServiceName.yml - f docker-compose.yml up`

# Common problems
## Need Sudo to run these / could not connect to Docker service
1. Ensure the current user is added to the docker group
`sudo usermod -a -G docker $USER`
2. Log out and back into session
3. Run all docker commands without sudo

## The MYSQL_ROOT_PASSWORD env variable is not set
1. Ensure that you export the env. variable like so
`export MYSQL_ROOT_PASSWORD=yourPass`
2. If you are running it as root (see above, you shouldn't), you require the -E flag. For example
`sudo -E docker-compose up`

## Resetting filestore
1. Ensure all containers are stopped
`docker-compose down`
2. Prune unused containers to remove all
`docker volume prune`
3. (Alternative) Remove one container
`docker volume list`
`docker volume rm VOL_NAME`