# Contact List PHP server

This is the backend server for Contact List App. This server uses [Laravel](https://laravel.com) framework. It can be run within [Homestead](https://laravel.com/docs/10.x/homestead)


### Start server
```
php artisan serve
```

### DB migration and seeding
```
php artisan migrate:fresh --seed
```

### Configuration for using with Homestead

#### DB config
```
# .env

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=33060
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

#### Homestead config

```
# Homestead.yaml

folders:
    - map: ~/.../contact-list-backend           # project folder directory
      to: /home/vagrant/contact-list-backend

sites:
    - map: homestead.test
      to: /home/vagrant/contact-list-backend/public

databases:
    - homestead

features:
    - mysql: true
services:
    - enabled:
          - "mysql"

ports:
   - send: 33060 # MySQL
     to: 3306
```

## Model
* User: Used for authentication with email and password.
* Contact: Has one to one relationship with User as each user will have one contact information record.
* Role: Role of user. Used for authorisation.
* Company: Company information of user, reference stored in contact record.
* Group: Group that a company belongs to. 
* Note: Store notes maded by user on contacts.

![](db/uml.png)
## About
This is the backend for [Contact List application]().
Contact List app is used to manage contact information for companies with view, filter and sort features. Also, user can add note to each contact record which is inaccessible to other users.

