# About
CalcTek Calculator is an API driven calculator service calculator. It is built using Laravel & Vue.js for the back-end & front-end respectively.
The core of CalcTek Calculator is the Calculator core, which is a standalone package that can be found in the packages folder.

![UI](https://catmemes.zip/DjaQmj)
# Installation
I'll assume you have cloned the repository. If you are running on Windows, you will need to use WSL.

Copy the .env.example file to .env, and update the database credentials to match your environment.

Then run these commands in your terminal:
```bash
composer install

./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

You should now be able to access the application at the URL specified in your terminal.

# Testing
The only tests for this project are in the Calculator core package. To run them, you have to cd into the packages/calctek/calculator directory, and run the following command:
```bash
./vendor/bin/phpunit
```
