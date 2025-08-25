# Sped Delivery Backend
Backend system for managing delivery zones, orders, and nearest delivery assignments.

## Description
This Laravel v12 backend allows restaurants to define delivery zones (polygon or radius),
validate orders based on zones, assign the nearest delivery person, and notify them.

## Setup Instructions

1. Clone the repository
2. Install PHP dependencies
3. npm install
4. Copy `.env` file and set your database credentials
5. Generate application key
6. Run migrations and seeders
7. php artisan migrate --seed

## Assumptions

- After running the project, a **login interface** will appear.  
- Login as **Admin** using the following credentials:  
  - Email: `admin@example.com`  
  - Password: `12345678` 

- **Admin Interface:** After login, a sidebar will be available with the following modules:  
  1. **Delivery Zone**  
     - Displays a list of delivery zones.  
     - Provides a **Create** option to add new zones.  
     - Supports creating multiple zones and editing existing ones.  

  2. **Orders**  
     - Displays a list of orders.  
     - Allows creating new orders.  
     - Provides functionality to assign delivery personnel to orders.  

  3. **Demo Delivery Man**  
     - Shows notifications related to assigned deliveries.  
     - Delivery personnel can **accept** or **reject** assignments.  
     - If rejected, another available delivery person will be automatically assigned.


## Design Choices
- Geo calculations done using custom Haversine formula for nearest delivery man.
- Zone model supports both polygon and circle types for flexibility.
- Feature and unit tests implemented using PHPUnit for reliable backend validation.
