CREATE VIEW `v_tags` AS
SELECT t.id,
    t.name,
    t.url,
    COALESCE(tt.cnt, 0) AS cnt
FROM tags AS t
LEFT JOIN (
    SELECT t1.id,
        COUNT(rt.post_id) AS cnt
    FROM tags AS t1
        INNER JOIN relation_topictag AS rt ON t1.id = rt.tag_id
    GROUP BY t1.id
) AS tt ON t.id = tt.id
