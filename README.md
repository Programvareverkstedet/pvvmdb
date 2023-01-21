# PVVMDB
## A new attempt to keep records of PVV members

The projects should keep track of some basic member information:
- Usernames of all existing users
- Basic contact information
- Membership payments
- Disk Quota payments

This will help us keep track of:
- Active and inactive members (based on payment of the membership fee)
- Total disk quota
- Date of registration

This information gives us the ability to lock inactive user accounts, and keep statistics of our userbase.
Locking inactive accounts is useful for security reasons and to incentivize actually paying the fee.

## Requirements

There are two recommended ways of running the application:

### Docker-compose / dev
The development environment is available through docker, as described below.
This includes a database and all required runtimes.
Requires:

- Docker
- Docker-compose

## Production / native
As the application is a normal PHP application, you will need:

- PHP (Tested with 8.2)
- A web server (e.g. nginx)
- php-pgsql
- A PostgreSQL server


## Development

The project is written in basic PHP, without external frameworks.
The dev environment is built with docker-compose, and contains a PostgreSQL database, php-fpm and nginx.

Start it by running `docker-compose up -d`.

You can then view the page at [localhost:3010](http://localhost:3010).

The database can be administered with [adminer](http://localhost:3010/adminer-4.8.1.php).

Some docs should probably be written in /docs

Some tools should probably be made in /utils

## TODO:

- Start Web interface
- Input methods:
  - [ ] Web form
  - [ ] Import from bank statements (PDF/CSV)
  - [ ] Import from GNUCash
- Make admin system
  - Save list of admins or integrate with www.pvv.ntnu.no/admin/
  - Auth / login, one of:
      - SSO with idp.pvv.ntnu.no
      - PAM / Unix auth
- Integrate with PVV "New user" scripts
- Allow normal users to update their own contact info?
- ...


