# Task Manager (Laravel)

‎A lightweight personal task management system built with Laravel and MySQL. The application allows users to create, view, edit, delete, and update the status of tasks through a simple and user-friendly dashboard.

Project Code: WST21-PM-2026-SF

Student Name: MARY JOHANNA C. LARIDE

Course & Year: BSIT2 - SEC1

Database Used: MySQL

## Overview 

‎This project is a Laravel-based Personal Task Manager developed as a mini project.
‎
‎The application follows the Laravel MVC structure:
‎
‎Routes → Controller → Model → Database → Blade Views
‎
‎Task information is stored in a MySQL database and managed through Laravel's CRUD functionality. The application also includes a separate status update feature for changing tasks between Pending and Completed.

## Features

- Add Task
‎
‎Create a new task by entering the required task information, including:
‎
· ‎Task name
· ‎Description
· ‎Status
· ‎Due date
‎
- ‎View Tasks
‎
‎Display all saved tasks in the task management dashboard.
‎
- ‎Edit Task
‎
‎Update the information of an existing task when changes are needed.
‎
- ‎Delete Task
‎
‎Remove a task from the database.
‎
‎Update Status
‎
‎Change a task's status between:
‎
· ‎Pending
· ‎Completed
‎
‎The status can be updated directly through the task management interface.

## Setup
1. Clone the repo and run `composer install`.
2. Copy `.env.example` to `.env` and set your database credentials.
3. Run `php artisan key:generate`.
4. Run `php artisan migrate`.
5. Run `php artisan serve` and visit `http://127.0.0.1:8000`.

## Screenshots
<img width="1904" height="952" alt="image" src="https://github.com/user-attachments/assets/c9511d94-248c-40ed-a240-2c3d6ccdbae0" />
<img width="1904" height="942" alt="image" src="https://github.com/user-attachments/assets/e4e17919-e9be-487a-ae24-dfde13914ce0" />
<img width="1903" height="947" alt="image" src="https://github.com/user-attachments/assets/6b76b613-6ea1-4fc5-85b2-756c804c70f6" />
<img width="1904" height="950" alt="image" src="https://github.com/user-attachments/assets/e0653df2-d3cf-4de4-994d-0d63d151362c" />





