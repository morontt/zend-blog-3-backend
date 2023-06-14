-- Drop migrations with procedures

DELETE FROM migration_versions
  WHERE version = '20230614091935' -- tracking_to_archive
    OR version = '20171113212500'  -- update_all_comments_count
    OR version = '20230516083348'  -- update_comments_count
;
