CREATE PROCEDURE update_comments_count(IN topicID INT UNSIGNED)
  BEGIN
    DECLARE count_comments INT DEFAULT 0;

    SELECT COUNT(`id`)
    INTO count_comments
    FROM `comments` WHERE (`post_id` = topicID) AND (`deleted` = 0);

    UPDATE `posts` SET `comments_count` = count_comments
    WHERE `posts`.`id` = topicID;

    SELECT count_comments;
  END
