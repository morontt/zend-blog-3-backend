CREATE PROCEDURE update_comments_count(IN topicID INT UNSIGNED)
  BEGIN
    DECLARE count_comments INT DEFAULT 0;

    SELECT COUNT(DISTINCT c1.id) INTO count_comments
    FROM
      comments AS c1,
      comments AS c2
    WHERE
      c1.post_id = topicID
      AND c2.post_id = topicID
      AND c1.tree_left_key <= c2.tree_left_key
      AND c1.tree_right_key >= c2.tree_right_key
      AND c2.deleted = 0;

    UPDATE `posts` SET `comments_count` = count_comments
    WHERE `posts`.`id` = topicID;

    SELECT count_comments;
  END
