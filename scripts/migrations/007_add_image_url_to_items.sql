DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'items'
        AND column_name = 'image_url'
    ) THEN
        ALTER TABLE items ADD COLUMN image_url VARCHAR(255);
    END IF;
END $$; 