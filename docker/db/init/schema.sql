-- Drop existing tables if they exist
DROP TABLE IF EXISTS item_log CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS blobs CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- Create users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    account_name VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create blobs table for storing thumbnails
CREATE TABLE blobs (
    id SERIAL PRIMARY KEY,
    data BYTEA NOT NULL,
    mime_type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create items table
CREATE TABLE items (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    rarity VARCHAR(50) NOT NULL,
    act VARCHAR(50) NOT NULL,
    description TEXT,
    thumbnail_id INTEGER REFERENCES blobs(id) ON DELETE SET NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create item_log table for change history
CREATE TABLE item_log (
    id SERIAL PRIMARY KEY,
    item_id INTEGER NOT NULL REFERENCES items(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    action VARCHAR(50) NOT NULL,
    data JSONB NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Create trigger function for updating updated_at column
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create triggers for updated_at
DROP TRIGGER IF EXISTS update_users_updated_at ON users;
CREATE TRIGGER update_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

DROP TRIGGER IF EXISTS update_items_updated_at ON items;
CREATE TRIGGER update_items_updated_at
    BEFORE UPDATE ON items
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

-- Insert sample users
INSERT INTO users (email, password_hash, role, account_name)
VALUES 
    ('admin@demo.io', '$argon2id$v=19$m=65536,t=4,p=1$MXZ2OFU4S3FtM0FXaDAuVQ$7qzfbJIpjt4FdxBGfrta9C4BpsDalYk4JYs3h7rHc68', 'admin', 'Admin User'),
    ('user@demo.io', '$argon2id$v=19$m=65536,t=4,p=1$MXZ2OFU4S3FtM0FXaDAuVQ$7qzfbJIpjt4FdxBGfrta9C4BpsDalYk4JYs3h7rHc68', 'user', 'Demo User')
ON CONFLICT (email) DO UPDATE 
SET account_name = EXCLUDED.account_name;

-- Create indexes
CREATE INDEX IF NOT EXISTS idx_items_type ON items(type);
CREATE INDEX IF NOT EXISTS idx_items_rarity ON items(rarity);
CREATE INDEX IF NOT EXISTS idx_items_act ON items(act);
CREATE INDEX IF NOT EXISTS idx_item_log_item_id ON item_log(item_id);
CREATE INDEX IF NOT EXISTS idx_item_log_user_id ON item_log(user_id); 