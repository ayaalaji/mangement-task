# the name of project
Task Management System
# step to run the project 
1- add data base name in phpmyadmin
2- add the database name in .env
3- run the project in termial using this step:
   1 . php artisan config:cache
   2. php artisan config:clear
   //we put this step above (1+2) because in my project i put database name but you want to add new data so this is to clear .env and allow you to put your database
   3. php artisan migrate
   4. php artisan db:seed --class=UserSeeder
# Features
i use JWT package for Api   
# what about this project
this project containe three role admin and manager and user
admin can add task and update and delet and see the detail of any task
also manager can do it but we admin add some thing the manager can not rewrite any think 
the user can update the status of task and can see tis details
the admin can manage user like add user and update and delete
# doc of postman is 
https://documenter.getpostman.com/view/34555205/2sAXjSy8ak



