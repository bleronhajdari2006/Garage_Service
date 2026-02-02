-- Sample data for Garage_Service
-- Run after migrations/schema.sql

-- Insert admin user
INSERT INTO users (username, email, password_hash, role, created_at) VALUES 
('admin', 'admin@garage.local', '$2y$10$7tK6FyWrq5qR8sQ2fH5H/e0VvN8PxFpR8K8q0qL0m5A2Z5B5C5C5', 'admin', NOW());

-- Insert sample pages
INSERT INTO pages (slug, title, content, created_by, created_at) VALUES 
('home', 'Welcome to Garage Service', 'We provide professional automotive maintenance and repair services. Our expert technicians are ready to service your vehicle with quality and care.', 1, NOW()),
('about', 'About Our Garage', 'Garage Service has been serving the community for over 10 years with reliable, professional automotive care. We use only the highest quality parts and maintain the highest standards of workmanship.', 1, NOW()),
('services', 'Our Services', 'We offer comprehensive automotive services including brake service, engine diagnostics, oil changes, tire replacement, suspension repair, and much more. Visit our shop or call for an appointment.', 1, NOW()),
('news', 'Latest News & Updates', 'Stay updated with the latest news from our garage, including service specials, maintenance tips, and industry updates.', 1, NOW());

-- Insert sample news items
INSERT INTO news (title, body, created_by, created_at) VALUES 
('Spring Maintenance Special', 'Get your vehicle ready for spring. 20% off brake inspections this month.', 1, NOW()),
('New Diagnostic Equipment', 'We have upgraded our diagnostic equipment to provide faster, more accurate service.', 1, NOW());

-- Insert sample products/services
INSERT INTO products (title, description, created_by, created_at) VALUES 
('Oil Change Service', 'Professional oil and filter change with quality automotive oil. Includes 5-point inspection.', 1, NOW()),
('Brake System Service', 'Complete brake inspection, pad replacement, rotor resurfacing, and fluid check.', 1, NOW()),
('Tire Services', 'Tire replacement, balancing, alignment, and repair services.', 1, NOW());
