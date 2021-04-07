Commodities for exercise purposes with assumptions :
- RSA key is 1024 and should be 4096
- HTTP protocol should be avoided and using most recent version of https (ssl / tls)
- No framework is used for setup purposes, and code is not properly segregated
- The JWT configuration and implementation could be better 
- Constants for database connexion
- CSRF is not implemented and should be
- UX is not part of the POC
- JWT token should be sent through headers and not body request

SETUP :
- In the config/env.php file edit everything to match your server config
- composer require lcobucci/jwt
- Change database name in database.sql
- "Execute" database.sql

TO TEST :
- Username : robby
- Password : password (yes, definitely the best password ever)

NOTE :
- UserData are sanitized in the model just before being used
- UserData is sanitized only before inserting in DB if possible though prepared requests are used 
- BCrypt is used for password encryption
- JWT is used for authentication purpose which is better than session for an API