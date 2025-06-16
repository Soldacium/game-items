-- Password for both users is 'password123'
INSERT INTO users (email, password_hash, role) 
VALUES 
    ('user@demo.io', '$argon2id$v=19$m=65536,t=4,p=1$WHpBWVFHYkxHR2ZxTDRodA$TH3mBTQWOA7wMFWyXWZ3FHviEwkUFE3T7KKW5/FvXvE', 'user'),
    ('admin@demo.io', '$argon2id$v=19$m=65536,t=4,p=1$WHpBWVFHYkxHR2ZxTDRodA$TH3mBTQWOA7wMFWyXWZ3FHviEwkUFE3T7KKW5/FvXvE', 'admin')
ON CONFLICT (email) DO NOTHING; 