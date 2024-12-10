<?php
    $database = 'BRBMS';
    $username = 'root';
    $password = '';
    $host = 'localhost';

try {
    $db = new PDO("mysql:host=$host", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database if not exists
    $db->exec("CREATE DATABASE IF NOT EXISTS $database");
    $db->exec("USE $database");

        // 1. Users Table
    $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                contact_number VARCHAR(11) NOT NULL,
                gender  VARCHAR(50) NOT NULL,
                user_role ENUM('customer', 'owner') NOT NULL,
                date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // 2. Beaches Table
    $db->exec("
            CREATE TABLE IF NOT EXISTS beaches (
                beach_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                beach_name VARCHAR(100) NOT NULL UNIQUE,
                description TEXT,
                location VARCHAR(255),
                latitude DECIMAL(9,6),
                longitude DECIMAL(9,6),
                user_id INT NOT NULL,
                gcash_qr_code VARCHAR(255),
                gcash_name VARCHAR(255),
                gcash_phone_number VARCHAR(255),
                image VARCHAR(255),
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                INDEX (user_id)
            )
        ");

        // 3. Amenities Table
    $db->exec("
            CREATE TABLE IF NOT EXISTS amenities (
                amenity_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                beach_id INT NOT NULL,
                amenity_type VARCHAR(100) NOT NULL,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                capacity INT DEFAULT 0,
                quantity INT DEFAULT 1,
                image VARCHAR(255),
                availability_status ENUM('available', 'unavailable') DEFAULT 'available',
                FOREIGN KEY (beach_id) REFERENCES beaches(beach_id) ON DELETE CASCADE,
                INDEX (beach_id)
            )
        ");

        // 4. Events Table
    $db->exec("
            CREATE TABLE IF NOT EXISTS events (
                 event_id INT AUTO_INCREMENT PRIMARY KEY,
                beach_id INT NOT NULL, -- Foreign key to link to the beach or owner
                event_name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                event_date DATE NOT NULL,
                event_time TIME NOT NULL,
                images VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (beach_id) REFERENCES beaches(beach_id) ON DELETE CASCADE 
                )
        ");

        // 5. Reservations Table
    $db->exec("
            CREATE TABLE IF NOT EXISTS reservations (
            reservation_id INT AUTO_INCREMENT PRIMARY KEY,  
            user_id INT NOT NULL,                          -- The ID of the user making the reservation (foreign key)
            beach_id INT NOT NULL,                         -- The ID of the beach where the amenity is located (foreign key)
            amenity_id INT NOT NULL,                       -- The ID of the amenity being reserved (foreign key)
            reservation_date DATETIME NOT NULL,            -- The date and time the reservation was made
            quantity INT NOT NULL,                         -- Number of amenities being reserved
            total_amount DECIMAL(10, 2) NOT NULL,          -- Total price for the reservation
            status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL,  -- Reservation status
            payment_status ENUM('full', 'partial', 'pending') NOT NULL,  -- Payment status
            payment_method ENUM('GCash', 'PayPal', 'Bank Transfer', 'Cash') NOT NULL,  -- Payment method used
            customer_name VARCHAR(255) NOT NULL,           -- Customer's name
            customer_address TEXT NOT NULL,                -- Customer's address
            contact_number VARCHAR(20) NOT NULL,           -- Customer's contact number
            checkin_date DATE NOT NULL,                    -- Check-in date for the reservation
            checkout_date DATE NOT NULL,                   -- Checkout date for the reservation
            reference_number VARCHAR(255) NOT NULL,        -- Reference number for gcash
            FOREIGN KEY (user_id) REFERENCES users(user_id),  -- Foreign key to the `users` table
            FOREIGN KEY (beach_id) REFERENCES beaches(beach_id),  -- Foreign key to the `beaches` table
            FOREIGN KEY (amenity_id) REFERENCES amenities(amenity_id)  -- Foreign key to the `amenities` table
        );



        ");

        // 6. Reviews Table
    $db->exec("
            CREATE TABLE IF NOT EXISTS reviews (
                review_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                beach_id INT NOT NULL,
                rating INT CHECK (rating BETWEEN 1 AND 5),
                review_text TEXT,
                review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                FOREIGN KEY (beach_id) REFERENCES beaches(beach_id) ON DELETE CASCADE,
                INDEX (user_id, beach_id)
            )
        ");

        // 7. Transactions Table
   

    $db->exec("
        CREATE TABLE IF NOT EXISTS transactions (
            transaction_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            reservation_id INT NOT NULL,
            user_id INT NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            payment_method ENUM('gcash', 'cash') DEFAULT 'gcash',
            payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('pending', 'completed', 'refunded') DEFAULT 'pending',
            FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
            INDEX (reservation_id, user_id)
        )
        ");


    } catch (PDOException $e) {
        // Log errors instead of using die()
        error_log("Database error: " . $e->getMessage(), 3, "errors.log");
        echo "An error occurred while setting up the database. Please check the logs.";
    }
?>
