-- Add start_time column to tournaments table
ALTER TABLE `tournaments` 
ADD COLUMN `start_time` TIME NULL DEFAULT NULL AFTER `event_date`;

-- Update existing records with a default start time if needed
-- UPDATE `tournaments` SET `start_time` = '19:00:00' WHERE `start_time` IS NULL;
