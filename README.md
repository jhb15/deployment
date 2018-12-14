# AberFitness Deployment

Contains files required to deploy AberFitness on a docker host in both Staging and Production environments.

## Requirements

The deployment has only been verified as working on the docker hosts we were provided, which have the following versions:
* Docker 18.09.0
* Docker Compose 1.8.0

## Environment Variables

Environment variables are used to configure docker-compose and the services being launched.

### Docker-Compose .env

A top level `.env` file is required, which provides environment variables to the whole docker-compose file.
```env
DEPLOYMENT_TAG=staging # valid values staging or latest
Layout__LayoutServiceUrl=https://docker2.aberfitness.biz/layout-service/ # url to the layout service
```

### Service .env

Each service has an `.env` file within the `env` folder, i.e. for gatekeeper the env file is `env/gatekeeper.env`.  Environment variables set within this file are specific to containers running that service only.
Some example .env files are included in the `example_env` folder, however these may not be up-to-date with the latest variables for the service being deployed, so check the service-specific documentation to see which environment variables need setting.


### Certificates

A number of certificates are required for the services to work correctly in production.

Create a volume for shared certificates within the deployment stack.
```sh
docker volume create deployment_shared_certs
```

Mount the volume inside a temporary container to allow you to manipulate data within the volume.

```sh
docker run --name tempubuntu -v deployment_shared_certs:/certs -it ubuntu
```

From another terminal, copy in your archive containing the SSL certificates required by nginx.
```sh
docker cp aberfitness.tar tempubuntu:/certs
```

Inside the docker container, extract the certificates
```sh
tar -xf aberfitness.biz
```
The SSL certificates should now exist within the temporary container at `/certs/aberfitness.biz/fullchain.cer` and `/certs/aberfitness.biz/aberfitness.biz.key`.

Create the certificates required by all .NET containers:
```sh
mkdir /certs/shared
cd /certs/shared
openssl genrsa -des3 -passout pass:httpscertpassword -out https.key 2048
openssl rsa -passin pass:httpscertpassword -in https.key -out https.key
openssl req -sha256 -new -key https.key -out https.csr -subj '/CN=localhost'
openssl x509 -req -sha256 -days 365 -in https.csr -signkey https.key -out https.crt
openssl pkcs12 -export -out https.pfx -inkey https.key -in https.crt -certfile https.crt -passout pass:httpscertpassword
```

Create the certificates required by Gatekeeper:

```sh
mkdir /certs/gatekeeper
cd /certs/gatekeeper
```
Follow the instructions at https://github.com/sem5640-2018/gatekeeper/blob/development/docs/certificates.md to generate the required certificates inside `/certs/gatekeeper`

Clean up the temporary container you used
```
exit
docker container rm tempubuntu
```

## Deploying

Once all environment variables have been set, run:

```sh
docker-compose pull
docker-compose up -d
```
