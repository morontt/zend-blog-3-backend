-- Drop migrations with procedures

DELETE FROM doctrine_migrations
  WHERE version = 'Application\\Migrations\\Version20240720141951' -- tracking_to_archive
    OR version = 'Application\\Migrations\\Version20171113212500'  -- update_all_comments_count
    OR version = 'Application\\Migrations\\Version20230516083348'  -- update_comments_count
;
