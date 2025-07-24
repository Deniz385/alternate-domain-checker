Domain Availability Checker
This project is a simple web application that checks the availability of a domain name.
The domain name entered through the user interface is sent to a PHP backend script, and the result is dynamically displayed on the screen.

🚀 Project Purpose
This tool was developed to provide a basic example of how to retrieve data from an external service using web scraping and internal API emulation methods, without a need for an official API.

📂 File Structure
The project consists of two main files:

index.html: The frontend file where the user enters the domain name and sees the results. It contains a simple HTML structure, 
CSS for styling, and JavaScript code to handle communication with the server.

check_domain.php: Contains the main backend logic. This PHP script:

Receives the domain name request from index.html.

Sends a cURL request to internal check-availability service.

Adds necessary headers and cookies to mimic a request coming from a real browser.

Sends the result back to index.html.

⚙️ Setup and Usage
A web server environment that can interpret PHP code is required for this project to run.
You can run the project on your local machine by following the steps below.

Requirements:

A local web server package like XAMPP.

Steps:

Install XAMPP: Download and install XAMPP from the official website.

Start the Server: Open the XAMPP Control Panel and start the Apache server.

Move Project Files: Copy your project folder (containing index.html and check_domain.php) into the htdocs directory within your XAMPP installation folder (usually C:\xampp\htdocs\).

Access the Project: Open your web browser and navigate to http://localhost/your-project-folder-name/.

