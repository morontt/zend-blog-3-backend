-- Drop migrations with procedures

DELETE FROM migration_versions
  WHERE version = '20170831214559'
    OR version = '20171113212500'
    OR version = '20230516083348';
