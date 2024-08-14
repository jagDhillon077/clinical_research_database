# Clinical Research Database

## Overview

Our group has designed and implemented a Clinical Research Database intended for use by clinical researchers. The project serves as a centralized database containing information primarily related to clinical trial studies. This tool aids researchers in managing, filtering, and accessing vital clinical trial data, ensuring efficient and secure data handling.

## Project Structure

### Frontend

The frontend was initially built using HTML templates that were further customized to meet the project's specific needs. CSS is utilized to style the web pages, ensuring they are visually appealing and user-friendly. Additionally, some pre-made JavaScript files were incorporated to introduce basic animations, enhancing the user experience.

### Backend

The backend leverages the CPSC department’s Oracle database, PHP, and MySQL to handle data storage, retrieval, and manipulation. These technologies ensure a robust and reliable infrastructure for the database, allowing seamless interaction between the user interface and the data stored in the backend.

## Features

- **Centralized Database**: A comprehensive database that holds information related to various clinical trial studies, ensuring all data is easily accessible in one place.
- **Search and Filter**: Users can filter search queries to find specific studies or entries quickly.
- **Data Management**: Researchers can edit trial information, delete entries such as research assistants, and studies. However, patient information can only be viewed, not edited or deleted.
- **User Profiles**: Researchers can view and delete their own profiles, but cannot access other researchers' profiles.

## Users

This system is designed primarily for researchers affiliated with a university and is overseen by its National Health Department and Clinical Research Ethics Board, depending on the research area. The primary users of this system include:

- **Clinical Researchers**: Conduct trials and manage related data.
- **Research Assistants**: Support the trials by managing day-to-day tasks within the system.
- **Administrators**: Oversee the database, ensuring data integrity and access control.

## Installation

### Prerequisites

- **Web Server**: Apache or any compatible web server.
- **PHP**: Version 7.4 or higher.
- **MySQL**: Version 5.7 or higher.
- **Oracle Database**: Access to the CPSC department’s Oracle database.
- **Browser**: A modern web browser such as Chrome, Firefox, or Edge.

### Setup

1. Clone the repository to your local machine:

   ```bash
   git clone https://github.com/yourusername/clinical-research-database.git

2. Move the contents to your web server's root directory.

3. Configure the database connection in the config.php file:

`define('DB_SERVER', 'your_database_server');`
`define('DB_USERNAME', 'your_username');`
`define('DB_PASSWORD', 'your_password');`
`define('DB_DATABASE', 'your_database_name');`

4. Import the SQL files to set up the database schema and initial data:

`mysql -u username -p database_name < database_setup.sql`

5. Launch the web server and navigate to the site through your browser.

### Usage
Once the setup is complete, the system is ready for use by clinical researchers. Users can log in using their credentials and start managing clinical trial data.

