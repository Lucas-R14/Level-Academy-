-- Alter tournaments table to change entry_fee and prize columns
ALTER TABLE tournaments
MODIFY COLUMN entry_fee FLOAT NOT NULL DEFAULT 0.00,
MODIFY COLUMN prize BOOLEAN NOT NULL DEFAULT FALSE;