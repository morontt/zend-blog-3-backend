CREATE PROCEDURE tracking_to_archive()
  BEGIN
    DECLARE max_date DATETIME;

    SET max_date = (SELECT DATE_SUB(CURRENT_TIMESTAMP(), INTERVAL 15 DAY));

    INSERT INTO `tracking_archive` (`post_id`, `user_agent_id`, `ip_addr`, `time_created`)
      SELECT
        CASE WHEN `post_id` IS NOT NULL THEN `post_id` ELSE 0 END AS post_id,
        `user_agent_id`,
        `ip_addr`,
        `time_created`
      FROM `tracking`
      WHERE `time_created` < max_date ORDER BY `id`;

    DELETE FROM `tracking` WHERE `time_created` < max_date;
  END
