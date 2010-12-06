ALTER TABLE blog_posts ADD input_format VARCHAR(255) NOT NULL, DROP comment_count;
UPDATE blog_posts SET input_format = 'html';