CREATE PROCEDURE tracking_to_archive()
  BEGIN
    DECLARE max_date DATETIME;

    SET max_date = (SELECT DATE_SUB(CURRENT_TIMESTAMP(), INTERVAL 31 DAY));

    INSERT INTO `tracking_archive` (`post_id`, `user_agent_id`, `ip_addr`,
                                    `time_created`, `is_cdn`, `request_uri`,
                                    `ip_long`, `status_code`)
      SELECT
        COALESCE(`post_id`, 0) AS post_id,
        `user_agent_id`,
        `ip_addr`,
        `time_created`,
        `is_cdn`,
        `request_uri`,
        `ip_long`,
        `status_code`
      FROM `tracking`
      WHERE `time_created` < max_date ORDER BY `id`;

    DELETE FROM `tracking` WHERE `time_created` < max_date;
  END
