USE level_academy;

-- First drop the foreign key constraint
ALTER TABLE articles
DROP FOREIGN KEY articles_ibfk_1;

-- Then drop the old author_id column
ALTER TABLE articles
DROP COLUMN author_id;

-- Add the new author column
ALTER TABLE articles
ADD COLUMN author VARCHAR(100) NOT NULL AFTER content;

-- Update existing articles to set default author as 'admin'
UPDATE articles
SET author = 'admin'
WHERE author IS NULL;
