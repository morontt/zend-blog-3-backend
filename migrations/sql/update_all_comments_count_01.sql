CREATE PROCEDURE update_all_comments_count()
  BEGIN
    DECLARE done BOOLEAN DEFAULT FALSE;
    DECLARE post_id INT UNSIGNED;
    DECLARE cur CURSOR FOR SELECT id FROM posts;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done := TRUE;

    OPEN cur;

    post_loop: LOOP
      FETCH cur INTO post_id;
      IF done THEN
        LEAVE post_loop;
      END IF;
      CALL update_comments_count(post_id);
    END LOOP post_loop;

    CLOSE cur;
  END
