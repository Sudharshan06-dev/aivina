# Aivina - AI-Powered Task Management Tool

Aivina is an advanced task management tool inspired by JIRA, with AI capabilities to automate user story creation and epic breakdowns. Built with a microservices architecture, Aivina integrates seamlessly with agile project management workflows, enabling users to track projects, issues, and bugs with enhanced AI-driven insights.

Features
Automated Story Generation: Generate user stories from issues automatically using Aivina's AI-powered analysis.
Epic Breakdown: Break down epics into smaller, manageable user stories with AI assistance.
Notification System: Get real-time notifications via email, SMS, or in-app alerts.
Integrated Payment: Enable premium features for advanced users.
Microservices Architecture: Scalable and modular structure using AWS services for each feature module.
Architecture

The project leverages a microservices architecture with the following services:
Story Management Service (Laravel) - Manages stories, backlogs, and search.
Epic Management Service (Python) - Handles epic creation and AI-based breakdowns.
Notification Service (Java) - Handles email, SMS, and in-app notifications.
Payment Service (Node.js) - Supports premium features for advanced functionalities.

Each service is deployed on AWS Lambda with API Gateway for seamless integration.

Getting Started
Prerequisites
Node.js (for the Payment Service)
Laravel (for Auth and Story Management Services)
Python (for Epic Management Service)
Java (for Notification Service)
AWS Account for deploying the microservices

Installation
Clone the Repository:
bash
Copy code
git clone https://github.com/Sudharshan06-dev/aivina.git
cd aivina
Install Dependencies for each microservice:

# For Auth Service and Story Management Service:
bash
Copy code
cd admin-service
composer install

# For Epic Management Service:
bash
Copy code
cd epic-management-service
pip install -r requirements.txt

# For Notification Service: Set up your Java environment and build the project and For Payment Service:
bash
Copy code
cd payment-service
npm install

Environment Configuration:
Copy .env.example to .env in each service directory and update with necessary API keys, database credentials, and AWS configurations.
Running the Application

# To start the application locally, ensure each service is running and configured to communicate through the API Gateway:
bash
Copy code
php artisan serve  # For Laravel services
python epic_management.py  # For Epic Management
node payment-service/server.js  # For Payment Service

Visit http://localhost:8000 (or the specified port) to access the application. # Start the Notification Service in your Java environment

Deployment
To deploy Aivina to AWS:

Set up AWS Lambda functions for each microservice and connect them via API Gateway.
Configure each service with the respective Lambda endpoint.
Use AWS RDS or an EC2 instance for your database, depending on your configuration.

Usage
Log In: Access the main dashboard.
Create New Stories and Epics: Leverage the AI-powered tools to create and break down tasks.
Receive Notifications: Enable real-time notifications for updates on tasks, issues, and deadlines.
Upgrade for Premium Features: Use the Payment Service to access advanced functionalities.
Contributing
Contributions are welcome! Please submit a pull request or open an issue for feedback or features.

License
Distributed under the MIT License. See LICENSE for more information.

Contact
For questions, reach out via sudharshan.madhav@gmail.com or submit an issue on GitHub.
