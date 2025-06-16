DO $$
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = 'items'
        AND column_name = 'notes'
    ) THEN
        ALTER TABLE items ADD COLUMN notes TEXT;
    END IF;
END $$; 