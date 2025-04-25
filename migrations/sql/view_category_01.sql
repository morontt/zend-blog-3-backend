CREATE VIEW `v_category` AS
SELECT
    c.id,
    c.parent_id,
    c.name,
    c.url,
    c.tree_left_key,
    c.tree_right_key,
    c.tree_depth,
    COALESCE(cc.cnt, 0) AS cnt
FROM category AS c
LEFT JOIN (
    SELECT
        c1.id,
        COUNT(p.id) AS cnt
    FROM category AS c1, category AS c2
    INNER JOIN posts AS p ON c2.id = p.category_id
    WHERE p.hide = 0
        AND c2.tree_left_key >= c1.tree_left_key
        AND c2.tree_right_key <= c1.tree_right_key
    GROUP BY c1.id
) AS cc ON c.id = cc.id
