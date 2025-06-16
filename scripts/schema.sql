-- Drop existing tables if they exist
DROP TABLE IF EXISTS item_log;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS blobs;
DROP TABLE IF EXISTS users;

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'user',
    account_name VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blobs (
    id SERIAL PRIMARY KEY,
    data BYTEA NOT NULL,
    mime_type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS items (
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

CREATE TABLE IF NOT EXISTS item_log (
    id SERIAL PRIMARY KEY,
    item_id INTEGER NOT NULL REFERENCES items(id) ON DELETE CASCADE,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    action VARCHAR(50) NOT NULL,
    data JSONB NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_items_updated_at
    BEFORE UPDATE ON items
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();

INSERT INTO users (email, password_hash, role, account_name)
VALUES 
    ('admin@demo.io', '$argon2id$v=19$m=65536,t=4,p=1$MXZ2OFU4S3FtM0FXaDAuVQ$7qzfbJIpjt4FdxBGfrta9C4BpsDalYk4JYs3h7rHc68', 'admin', 'Admin User'),
    ('user@demo.io', '$argon2id$v=19$m=65536,t=4,p=1$MXZ2OFU4S3FtM0FXaDAuVQ$7qzfbJIpjt4FdxBGfrta9C4BpsDalYk4JYs3h7rHc68', 'user', 'Demo User')
ON CONFLICT (email) DO UPDATE 
SET account_name = EXCLUDED.account_name;

DO $$ 
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_indexes WHERE indexname = 'idx_items_type') THEN
        CREATE INDEX idx_items_type ON items(type);
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_indexes WHERE indexname = 'idx_items_rarity') THEN
        CREATE INDEX idx_items_rarity ON items(rarity);
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_indexes WHERE indexname = 'idx_items_act') THEN
        CREATE INDEX idx_items_act ON items(act);
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_indexes WHERE indexname = 'idx_item_log_item_id') THEN
        CREATE INDEX idx_item_log_item_id ON item_log(item_id);
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_indexes WHERE indexname = 'idx_item_log_user_id') THEN
        CREATE INDEX idx_item_log_user_id ON item_log(user_id);
    END IF;
END $$;

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Health Potion', 'Consumable', 'Common', '1', 'Restores 100 health points'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Health Potion');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Mana Potion', 'Consumable', 'Common', '1', 'Restores 100 mana points'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Mana Potion');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Sword of Light', 'Weapon', 'Rare', '2', 'A powerful sword that glows with divine light'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Sword of Light');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Dragon Scale Armor', 'Armor', 'Epic', '3', 'Armor forged from ancient dragon scales'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Dragon Scale Armor');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Ring of Power', 'Accessory', 'Legendary', '4', 'Increases all attributes by 10'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Ring of Power');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Scroll of Teleport', 'Consumable', 'Uncommon', '2', 'Teleports you to the nearest town'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Scroll of Teleport');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Staff of Fire', 'Weapon', 'Epic', '3', 'A staff imbued with the power of fire'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Staff of Fire');

INSERT INTO items (name, type, rarity, act, description)
SELECT 'Amulet of Protection', 'Accessory', 'Rare', '2', 'Increases defense by 20'
WHERE NOT EXISTS (SELECT 1 FROM items WHERE name = 'Amulet of Protection');