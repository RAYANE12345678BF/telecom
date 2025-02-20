-- Table Departements
DROP TABLE IF EXISTS `departements`;
CREATE TABLE departements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Table Services
DROP TABLE IF EXISTS `services`;
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Table Departements_Services (junction table)
DROP TABLE IF EXISTS `departements_services`;
CREATE TABLE `departements_services` (
    service_id INT,
    departement_id INT,
    FOREIGN KEY (departement_id) REFERENCES departements(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Table Roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Table Utilisateurs
DROP TABLE IF EXISTS `employees`;
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(255) NOT NULL,
    nom VARCHAR(255) NOT NULL,
    email_professionnel VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    departement_id INT,
    service_id INT,
    role_id INT,
    compte_valid BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (departement_id) REFERENCES departements(id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
