# About **frecole**

**frecole** is a simple school management system, offering an alternative to establishments that cannot acquire one of the many solutions on the market.

**frecole** allows these features:

- User management (creation/modification/deletion)
- User roles: each role (teacher, student, parent, etc.) gives access rights to the application
- Management of user groups (parent<==>student)
- Materials management
- Management of school years

For each school year:

- Class management
- Management of assignments (students and teachers)
- Work management
- Management of corrections
- Access to school results (parents, students, teachers)

The data model can be viewed here : [modèle de données](https://docs.google.com/drawings/d/1EbIsxDt3z9tIoRHQU_xx-jazaEomfl7eew0EOv8sZoE/edit "Data model").

**frecole** has been developed in PHP with the [laravel](https://laravel.com/) framework.

A full demo is available here : [https://school-mpa-mono-8f20b5d7a8b2.herokuapp.com/](https://school-mpa-mono-8f20b5d7a8b2.herokuapp.com/)

## Installation - prerequisites

**frecole** requires the use of:

- *PHP 8.1*
- *composer* (to install PHP dependencies)
- *node* (to install JS dependencies)

## Installation

- Clone the project

> git clone https://github.com/dburea01/school-mpa-mono.git my_folder

- Enter the installation directory

> cd my_folder

- Install the PHP dependencies

> composer install

- Install the JS dependencies

> npm install

- Initialize your environments. Copy the .env.example file to .env

> COPY .env.example .env

If you want to use tests, you can also initialize the test environment (not required)

> COPY .env.example .env.testing

## Databases

### sqlite

By default, **frecole** uses the SQLITE database (provided with the PHP installation). The *.env* environment file points to this database.

But you can also choose to use another database (see below).

### postgresql

@todo

## Initializing tables

Run the migration to create the tables. The migration also populates a few tables:

- some subjects
- some types of work
- some comments
- civilities
- countries
- the roles

You are then free to complete and modify these lists.

> php artisan migrate:fresh

To create some test data (users, periods, assignments, results...), you can also launch the migration by specifying --seed:

> php artisan migrate:fresh --seed

## Project launch

From the installation directory:

> php artisan serve

This will launch the application on port 8000, **frecole** will then be available on [localhost:8000](http://localhost:8000)

## Launch of the project in development mode

If you want to develop on the project, you can launch *vite* in parallel:

> npm run dev

This will inspect any changes in the project in real time, refresh the site automatically, and build javascript and css resources optimized for production. See also [https://laravel.com/docs/11.x/vite](https://laravel.com/docs/11.x/vite)

## The tests

**frecole** is tested for all application security aspects (a help is welcome for the othee tests....).

Before running the tests:

- create your test database (postgresql OR sqlite)
- create your test environment, see the *.env.testing* file
  
To run the tests:
> php artisan test

## Permissions

**frecole** comes with these roles

- ADMIN (administrator - has access to everything)
- TEACHER (teacher - limited access)
- PARENT (parent - limited access)
- STUDENT (student - limited access)

Each of these roles corresponds to a list of tasks. See the matrix of these tasks here [https://docs.google.com/spreadsheets/d/1GB4SWRHhzk6gIeP6052KiuQ903_O8UaOWN6J4lz_eBE](https://docs.google.com/spreadsheets/d/1GB4SWRHhzk6gIeP6052KiuQ903_O8UaOWN6J4lz_eBE)

The default assignment of tasks by roles is defined in the migration file *database/migrations/create_roles_tasks_table.php*. You are free to modify this attribution. **WARNING: sensitive subject....**

## Translation of **frecole**

**frecole** is provided by default in French. COMING SOON: MULTILANGUAGE.

## Want to invest?

The author of the site is looking for his product owner. Contact me.

## Licence

**frecole** is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## A few words about the author of the site

- Identity: Dominique BUREAU (Dom)
- Age: sixty-year-old (immediately that commands respect eh.. ;-)
- Situation: married, 2 grown-up and beautiful children
- CV: I work
- Motto: Make this day count - Jack Dawson
- Hobbies: I love web development (ah if only it had been invented earlier).

Despite all my efforts, if you encounter problems (or even bugs...), or if you have functional or technical suggestions to make to me on **frecole**, you can contact me. Small messages of encouragement are also welcome.

Finally, if the site finds its audience and if the enthusiasm is there, then undoubtedly a “full API” version will see the light of day. To be continued!

## L'équipe

- Project Manager : Dominique BUREAU
- Product owner : Dominique BUREAU
- Conception : Dominique BUREAU
- Development : Dominique BUREAU
- Scrum master : Dominique BUREAU
- Test : Dominique BUREAU
- Designer : Dominique BUREAU
