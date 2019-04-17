-- SQL Schema for the project

-- Change to the DB; if this fails you should create it
use chronos;

-- Drop tables if they exist
DROP TABLE IF EXISTS note_tags;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS notes;
DROP TABLE IF EXISTS message_instances;
DROP TABLE IF EXISTS message_attachments;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS int_appointments;
DROP TABLE IF EXISTS ext_appointments;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS secretarial_relationships;
DROP TABLE IF EXISTS managers;
DROP TABLE IF EXISTS user_details;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS tokens;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

-- Roles table
CREATE TABLE roles (
    id TINYINT UNSIGNED,
    role VARCHAR(50) UNIQUE NOT NULL,
    PRIMARY KEY roles_pk (id)
);

-- Users table
-- Used for authentication/authorisation management.
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT,
    username VARCHAR(25) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY users_pk (id),
    FOREIGN KEY users_role_id_fk (role_id) REFERENCES roles(id)
);

-- tokens table
-- Used to store temporary unique tokens (mainly for password reset facility)
CREATE TABLE tokens (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id INT UNSIGNED UNIQUE NOT NULL,
    token VARCHAR(255) NOT NULL,
    expiry DATETIME NOT NULL,
    PRIMARY KEY tokens_pk (id),
    FOREIGN KEY tokens_id_fk (user_id) REFERENCES users(id)
);

-- departments table
CREATE TABLE departments (
    id TINYINT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(60) UNIQUE NOT NULL,
    PRIMARY KEY departments_pk (id)
);

-- user_details table
-- Stores user information
CREATE TABLE user_details (
    id INT UNSIGNED AUTO_InCREMENT,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    department_id TINYINT UNSIGNED NOT NULL,
    email VARCHAR(60) NOT NULL,
    last_name VARCHAR(60) NOT NULL,
    first_name VARCHAR(60) NOT NULL,
    middle_name VARCHAR(60),
    title VARCHAR(100) NOT NULL,
    office VARCHAR(10),
    extension SMALLINT UNSIGNED,
    PRIMARY KEY user_details_pk (id),
    FOREIGN KEY user_details_user_id_fk (user_id) REFERENCES users(id),
    FOREIGN KEY user_details_department_id_fk (department_id) REFERENCES departments(id)
);

-- managers table
-- Stores a list of managers and their departments
-- A user can only manager one department but a department can have several managers (comanaging, assistant managers, etc)
-- CREATE TABLE managers (
--    user_id INT UNSIGNED,
--    department_id TINYINT UNSIGNED NOT NULL,
--    PRIMARY KEY managers_pk (user_id),
--    FOREIGN KEY managers_user_id_fk (user_id) REFERENCES users(id),
--    FOREIGN KEY managers_department_id_fk (department_id) REFERENCES departments(id)
-- );

-- secretarial_relationships table
-- Defines the relationships between a secretary and a group of users.
CREATE TABLE secretarial_relationships (
    id INT UNSIGNED AUTO_INCREMENT,
    secretary_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    PRIMARY KEY secretarial_relationships_pk (id),
    FOREIGN KEY sec_rel_secretary_id_fk (secretary_id) REFERENCES users(id),
    FOREIGN KEY sec_rel_user_id_fk (user_id) REFERENCES users(id)
);

-- appointments table
-- Stores basic information about an appointment
CREATE TABLE appointments (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    title VARCHAR(127) NOT NULL,
    details TEXT,
    PRIMARY KEY appointments_pk (id),
    FOREIGN KEY appointments_user_id_fk (user_id) REFERENCES users(id)
);

-- ext_appointment table
-- Stores party info when person is external to the organisation.
CREATE TABLE ext_appointments (
    id INT UNSIGNED AUTO_INCREMENT,
    appointment_id INT UNSIGNED NOT NULL UNIQUE,
    party VARCHAR(127) NOT NULL,
    info TINYTEXT,
    PRIMARY KEY ext_appointments_pk (id),
    FOREIGN KEY ext_appointments_appointment_id_fk (appointment_id) REFERENCES appointments(id)
);

-- int_appointments table
-- Stores party info when person is internal to the organisation.
CREATE TABLE int_appointments (
    id INT UNSIGNED AUTO_INCREMENT,
    appointment_id INT UNSIGNED NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    confirmed BOOL NOT NULL DEFAULT FALSE,
    PRIMARY KEY int_appointments_pk (id),
    FOREIGN KEY int_appointments_appointment_id_fk (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY int_appointment_user_id_fk (user_id) REFERENCES users(id)
);

-- messages table
-- Stores user-created messages
CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    date DATETIME NOT NULL,
    subject VARCHAR(63) NOT NULL,
    content TEXT,
    PRIMARY KEY messages_pk (id),
    FOREIGN KEY messages_user_id_fk (user_id) REFERENCES users(id)
);

-- message_attachments table
-- Stores files attached to a message
CREATE TABLE message_attachments (
    id INT UNSIGNED AUTO_INCREMENT,
    message_id INT UNSIGNED NOT NULL,
    filename VARCHAR(127) NOT NULL,
    content MEDIUMBLOB NOT NULL,
    PRIMARY KEY message_attachments_pk (id),
    FOREIGN KEY message_attachments_message_id_fk (message_id) REFERENCES messages(id)
);

-- message_instances table
-- Stores instance of a message (routing information and whether it was seen)
CREATE TABLE message_instances (
    id INT UNSIGNED AUTO_INCREMENT,
    message_id INT UNSIGNED,
    user_id INT UNSIGNED,
    seen DATETIME,
    archived BOOL NOT NULL DEFAULT FALSE,
    PRIMARY KEY message_instances_pk (id),
    FOREIGN KEY message_instances_message_id_fk (message_id) REFERENCES messages(id),
    FOREIGN KEY message_instances_user_id_fk (user_id) REFERENCES users(id),
    UNIQUE mesg_inst_uq (message_id,user_id)
);

-- notes table
-- Stores not information
CREATE TABLE notes (
    id INT UNSIGNED AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    date DATETIME NOT NULL,
    title VARCHAR(127) NOT NULL,
    content TEXT,
    PRIMARY KEY notes_pk (id),
    FOREIGN KEY notes_user_id_fk (user_id) REFERENCES user_details(user_id)
);

-- tags table
-- stores tags which can be associated to a note
CREATE TABLE tags (
    id INT UNSIGNED AUTO_INCREMENT,
    name VARCHAR(31) NOT NULL,
    PRIMARY KEY tags_pk (id)
);

-- note_tags table
-- Stores note/tag relationships
CREATE TABLE note_tags (
    id INT UNSIGNED AUTO_INCREMENT,
    tag_id INT UNSIGNED,
    note_id INT UNSIGNED,
    PRIMARY KEY note_tags_pk (id),
    FOREIGN KEY note_tags_note_id_fk (note_id) REFERENCES notes(id),
    FOREIGN KEY note_tags_tag_id_fk (tag_id) REFERENCES tags(id),
    UNIQUE note_tags_uq (note_id,tag_id)
);

-- Populate the roles table as these are more or less hard coded.
-- Arrange IDs by privilege level with plenty of space between.
INSERT INTO roles
(id, role)
VALUES
(0, 'Inactive'),
(20, 'User'),
(40, 'Secretary'),
(120, 'Manager'),
(220, 'Admin'),
(255, 'SuperAdmin');

