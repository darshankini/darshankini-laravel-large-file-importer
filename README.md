1.	Implement Authentication: 
Create login and registration pages using the Laravel Breeze package, which provides built-in functionality for user authentication with validation.

2.	File Upload with ResumableJS: 
Enable uploading of a 1GB file using ResumableJS, which handles the file upload in chunks. Once all chunks are uploaded, they are merged into a single CSV file.

3.	Job Dispatch on File Upload Completion: 
Dispatch a job after the file upload is complete.

4.	Data Import Job: 
The dispatched job reads the file from the storage directory and imports the data into the database. Confirm that the data is successfully imported.

5.	User List Display with Yajra DataTables: 
Display a paginated user list with search functionality and a dropdown to select the number of records displayed per page, using the Yajra DataTables package in Laravel.

Implementation Steps
1. Authentication with Laravel Breeze
•	Install Laravel Breeze:
    composer require laravel/breeze --dev 

•	Install Breeze scaffolding:
    php artisan breeze:install 

•	Run migrations:
    php artisan migrate 

•	Install and compile assets:
    npm install && npm run dev 

•	Now, you should have login and registration functionality with validation.

2. File Upload with ResumableJS
•	Include ResumableJS in your project:
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script> 

•	Set up the frontend to handle file chunk uploads:

•	Create a route and controller method for handling uploads:

3. Job Dispatch on File Upload Completion
•	Dispatch a job when all chunks are merged:

4. Data Import Job
•   Create a job for importing data:
    php artisan make:job ImportDataJob

•	In ImportDataJob, handle file reading and database import:

5. User List Display with Yajra DataTables
•   Install Yajra DataTables:
    composer require yajra/laravel-datatables-oracle 

•	Publish DataTables configuration:
    php artisan vendor:publish --provider="Yajra\DataTables\DataTablesServiceProvider" 

•	Set up the controller to use DataTables:
    use Yajra\DataTables\DataTables; 
   
•	Create a view to display the DataTable:

Note : 
Please find sample csv data
File name : sample_data.zip
 
This setup provides a comprehensive approach to implement login and registration, handle large file uploads, process data in background jobs, and display user lists with pagination and search functionality using Laravel and its ecosystem tools.

