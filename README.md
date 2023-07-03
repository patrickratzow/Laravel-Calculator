# About
CalcTek Calculator is an API driven calculator service calculator. It is built using Laravel & Vue.js for the back-end & front-end respectively.
The core of CalcTek Calculator is the calculator core, which is a standalone package that can be found in the packages folder.

![UI](https://catmemes.zip/DjaQmj)
# Installation
I'll assume you have cloned the repository. If you are running on Windows, you will need to use WSL.

Copy the .env.example file to .env, and update the database credentials to match your environment.

Then run these commands in your terminal:
```bash
composer install

# Docker up
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install

# Serve the front-end
./vendor/bin/sail npm run dev
```

You should now be able to access the application at the URL specified in your terminal.

# Testing
The only tests for this project are in the calculator core package. To run them, you must go into the calculator package
```bash
# Move into the package
cd ./packages/calctek/calculator

# Run the tests
./vendor/bin/phpunit
```
