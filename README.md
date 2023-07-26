# SpaceX PHP REST API

This API returns data on SpaceX capsules and launches

To test API:
   - Run composer install
     ```
      composer install
     ```

  - Create a .env file and add the following variable
 ```
  TOKEN_ISSUER=YOURVARIABLE
 ```

 Start php server:
 ```
  php -S 127.0.0.1:8000 -t public
 ```
To test on Postman:

Generate Token by sending a GET request to this endpoint:
http://127.0.0.1:8000/generateToken

Get capsule data by sending a POST request to this endpoint:
http://127.0.0.1:8000/capsules
