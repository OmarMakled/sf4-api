# REST API Symfony4.4, PHP7.4 & mysql

| type   | url                         | payload        | description            |
| ------ | --------------------------- | -------------- | ---------------------- |
| POST   | /api/invitation             |{email: string} | add new invitation     |
| DELETE | /api/invitation/{id}        |                | cancel invitation      |
| PUT    | /api/invitation/{id}/status |{status: string}| declined/accepted invitation|

**API Authentication**

We are using simple auth mechanism, you have to add token with request header `X-API-TOKEN`, 
open fixtures to get a token.

## Project setup

```
git clone https://github.com/OmarMakled/sf4-api
cd symfony4-back-end
composer install
set .env DATABASE_URL=
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load
```

## Serve
`php -S localhost:8888 -t public`

**Example**

Add new invitation to user `user2@example.com`
```
curl --request POST \
  --url http://localhost:8888/api/invitation \
  --header 'content-type: application/json' \
  --header 'x-api-token: user1_token' \
  --data '{
	"email": "user2@example.com"
}'
```
Cancel an invitation
```
curl --request DELETE \
  --url http://localhost:8888/api/invitation/1 \
  --header 'content-type: application/json' \
  --header 'x-api-token: user1_token'
```

Accept/Decline an invitation
```
curl --request PUT \
  --url http://localhost:8888/api/invitation/1/status \
  --header 'content-type: application/json' \
  --header 'x-api-token: user2_token' \
  --data '{
	"status": "declined"
}'
```

## Test
```
bin/console doctrine:database:create --env test --if-not-exists
bin/console doctrine:schema:update --force --env test
bin/phpunit
```


## Todo
- Docker & optimize installation and testing steps
- Api doc

Happy Code!