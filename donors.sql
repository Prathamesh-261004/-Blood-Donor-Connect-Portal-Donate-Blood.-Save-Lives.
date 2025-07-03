-- Database: blood_donor

CREATE TABLE donors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  phone VARCHAR(20),
  password VARCHAR(255),
  city VARCHAR(50),
  pincode VARCHAR(10),
  blood_group VARCHAR(5),
  verified TINYINT(1) DEFAULT 0,
  otp_code VARCHAR(6),
  last_donated DATE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE blood_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT,
  requester_name VARCHAR(100),
  requester_email VARCHAR(100),
  requester_phone VARCHAR(20),
  message TEXT,
  status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
  requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (donor_id) REFERENCES donors(id)
);

CREATE TABLE donation_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT,
  donated_on DATE,
  hospital_name VARCHAR(100),
  city VARCHAR(50),
  proof_file VARCHAR(255),
  FOREIGN KEY (donor_id) REFERENCES donors(id)
);
ALTER TABLE donors ADD active BOOLEAN DEFAULT 1;
