-- ============================================================
-- TJ Slavoj Mýto – Seed data (vzorová data) – Sezóna 2025/26
-- ============================================================
-- Zdroj: http://slavojmyto.cz/
-- Obsah:
--   • Taxonomie: sezóna, kategorie týmu, stav zápasu, pozice hráče
--   • Týmy: TJ Slavoj Mýto (Muži A) a TJ Slavoj Mýto B (Muži B)
--   • Zápasy: 15× Muži A (Krajský přebor), 13× Muži B (1.B třída sk. C)
--   • Hráči: soupiska TJ Slavoj Mýto B (7 hráčů)
--
-- Použití:
--   mysql -u root -p slavoj_myto < seed-data.sql
--
-- Poznámky:
--   • Skript předpokládá prefix tabulek 'wp_'. Pro jiný prefix
--     proveďte globální náhradu 'wp_' → '<váš_prefix>'.
--   • Skript je idempotentní (INSERT IGNORE na posts/terms/term_taxonomy/
--     term_relationships; podmíněný INSERT pro postmeta).
--   • ID termínů začínají od 1000, posts od 5000 – navrženo pro
--     čistou instalaci, kde tato ID nejsou obsazena.
--   • Skóre je uloženo ve formátu „domácí:hosté" (např. 2:4).
-- ============================================================

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- ============================================================
-- 1. TAXONOMIE – SEZÓNA
-- ============================================================

INSERT IGNORE INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES (1000, '2025/26', '2025-26', 0);

INSERT IGNORE INTO `wp_term_taxonomy`
  (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES (1000, 1000, 'sezona', 'Sezóna 2025/26', 0, 0);

-- ============================================================
-- 2. TAXONOMIE – KATEGORIE TÝMU
-- ============================================================

INSERT IGNORE INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES (1001, 'Muži A', 'muzi-a', 0);

INSERT IGNORE INTO `wp_term_taxonomy`
  (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES (1001, 1001, 'kategorie-tymu', 'Mužský A-tým', 0, 0);

INSERT IGNORE INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES (1002, 'Muži B', 'muzi-b', 0);

INSERT IGNORE INTO `wp_term_taxonomy`
  (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES (1002, 1002, 'kategorie-tymu', 'Mužský B-tým', 0, 0);

-- ============================================================
-- 3. TAXONOMIE – STAV ZÁPASU
-- ============================================================

INSERT IGNORE INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES (1003, 'Odehraný', 'odehrany', 0);

INSERT IGNORE INTO `wp_term_taxonomy`
  (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES (1003, 1003, 'stav-zapasu', 'Zápas byl odehrán, skóre zadáno', 0, 0);

-- ============================================================
-- 4. TAXONOMIE – POZICE HRÁČE
-- ============================================================

INSERT IGNORE INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`)
VALUES (1004, 'Neuvedeno', 'neuvedeno', 0);

INSERT IGNORE INTO `wp_term_taxonomy`
  (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`)
VALUES (1004, 1004, 'pozice-hrace', 'Pozice hráče nebyla zadána', 0, 0);

-- ============================================================
-- 5. TÝMY (CPT: tym)
-- ============================================================

-- Tým: TJ Slavoj Mýto – Muži A (ID 5000)
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5000, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00',
   'Plzeňský krajský přebor – sezóna 2025/26', 'TJ Slavoj Mýto – Muži A',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-muzi-a',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'tym', '', '',
   '', 0, '', 0, 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`)
SELECT 5000, 'tym_slug', 'muzi-a'
WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5000 AND `meta_key` = 'tym_slug');

INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`)
VALUES (5000, 1000, 0), (5000, 1001, 0);

-- Tým: TJ Slavoj Mýto B – Muži B (ID 5001)
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5001, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00',
   'Plzeňský 1.B třída sk. C – sezóna 2025/26', 'TJ Slavoj Mýto B – Muži B',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-muzi-b',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'tym', '', '',
   '', 0, '', 0, 0);

INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`)
SELECT 5001, 'tym_slug', 'muzi-b'
WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5001 AND `meta_key` = 'tym_slug');

INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`)
VALUES (5001, 1000, 0), (5001, 1002, 0);

-- ============================================================
-- 6. ZÁPASY MUŽI A – TJ Slavoj Mýto, Krajský přebor 2025/26
--    (CPT: zapas; všechny zápasy jsou odehrané)
-- ============================================================

-- Pomocná makra pro opakované vložení zápasu a jeho metadat:
-- Každý zápas dostane: datum_zapasu, cas_zapasu, domaci, hoste, skore
-- Taxonomie: sezona(1000), kategorie-tymu dle týmu, stav-zapasu(1003=odehrany)

-- Zápas 5100 – FK Rapid Plzeň vs TJ Slavoj Mýto, 08.08.2025 18:00, skóre 2:4
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5100, 1, '2025-08-08 18:00:00', '2025-08-08 18:00:00', '', 'FK Rapid Plzeň vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'rapid-plzen-vs-tj-slavoj-myto-2025-08-08',
   '2025-08-08 18:00:00', '2025-08-08 18:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5100, 'datum_zapasu', '2025-08-08' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5100 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5100, 'cas_zapasu', '18:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5100 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5100, 'domaci', 'FK Rapid Plzeň' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5100 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5100, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5100 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5100, 'skore', '2:4' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5100 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5100, 1000, 0), (5100, 1001, 0), (5100, 1003, 0);

-- Zápas 5101 – TJ Slavoj Mýto vs SK Nepomuk, 17.08.2025 17:30, skóre 4:0
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5101, 1, '2025-08-17 17:30:00', '2025-08-17 17:30:00', '', 'TJ Slavoj Mýto vs SK Nepomuk',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-sk-nepomuk-2025-08-17',
   '2025-08-17 17:30:00', '2025-08-17 17:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5101, 'datum_zapasu', '2025-08-17' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5101 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5101, 'cas_zapasu', '17:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5101 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5101, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5101 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5101, 'hoste', 'SK Nepomuk' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5101 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5101, 'skore', '4:0' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5101 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5101, 1000, 0), (5101, 1001, 0), (5101, 1003, 0);

-- Zápas 5102 – TJ Vejprnice vs TJ Slavoj Mýto, 23.08.2025 10:15, skóre 3:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5102, 1, '2025-08-23 10:15:00', '2025-08-23 10:15:00', '', 'TJ Vejprnice vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'tj-vejprnice-vs-tj-slavoj-myto-2025-08-23',
   '2025-08-23 10:15:00', '2025-08-23 10:15:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5102, 'datum_zapasu', '2025-08-23' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5102 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5102, 'cas_zapasu', '10:15' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5102 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5102, 'domaci', 'TJ Vejprnice' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5102 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5102, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5102 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5102, 'skore', '3:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5102 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5102, 1000, 0), (5102, 1001, 0), (5102, 1003, 0);

-- Zápas 5103 – TJ Slavoj Mýto vs FK Měcholupy, 30.08.2025 17:30, skóre 5:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5103, 1, '2025-08-30 17:30:00', '2025-08-30 17:30:00', '', 'TJ Slavoj Mýto vs FK Měcholupy',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-fk-mecholupy-2025-08-30',
   '2025-08-30 17:30:00', '2025-08-30 17:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5103, 'datum_zapasu', '2025-08-30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5103 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5103, 'cas_zapasu', '17:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5103 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5103, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5103 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5103, 'hoste', 'FK Měcholupy' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5103 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5103, 'skore', '5:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5103 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5103, 1000, 0), (5103, 1001, 0), (5103, 1003, 0);

-- Zápas 5104 – FK Horní Bříza vs TJ Slavoj Mýto, 06.09.2025 10:15, skóre 3:0
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5104, 1, '2025-09-06 10:15:00', '2025-09-06 10:15:00', '', 'FK Horní Bříza vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'fk-horni-briza-vs-tj-slavoj-myto-2025-09-06',
   '2025-09-06 10:15:00', '2025-09-06 10:15:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5104, 'datum_zapasu', '2025-09-06' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5104 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5104, 'cas_zapasu', '10:15' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5104 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5104, 'domaci', 'FK Horní Bříza' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5104 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5104, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5104 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5104, 'skore', '3:0' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5104 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5104, 1000, 0), (5104, 1001, 0), (5104, 1003, 0);

-- Zápas 5105 – FK Lhota vs TJ Slavoj Mýto, 13.09.2025 17:00, skóre 4:0
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5105, 1, '2025-09-13 17:00:00', '2025-09-13 17:00:00', '', 'FK Lhota vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'fk-lhota-vs-tj-slavoj-myto-2025-09-13',
   '2025-09-13 17:00:00', '2025-09-13 17:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5105, 'datum_zapasu', '2025-09-13' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5105 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5105, 'cas_zapasu', '17:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5105 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5105, 'domaci', 'FK Lhota' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5105 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5105, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5105 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5105, 'skore', '4:0' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5105 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5105, 1000, 0), (5105, 1001, 0), (5105, 1003, 0);

-- Zápas 5106 – TJ Slavoj Mýto vs TJ Horšovský Týn, 21.09.2025 16:45, skóre 3:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5106, 1, '2025-09-21 16:45:00', '2025-09-21 16:45:00', '', 'TJ Slavoj Mýto vs TJ Horšovský Týn',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-tj-horsovsky-tyn-2025-09-21',
   '2025-09-21 16:45:00', '2025-09-21 16:45:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5106, 'datum_zapasu', '2025-09-21' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5106 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5106, 'cas_zapasu', '16:45' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5106 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5106, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5106 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5106, 'hoste', 'TJ Horšovský Týn' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5106 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5106, 'skore', '3:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5106 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5106, 1000, 0), (5106, 1001, 0), (5106, 1003, 0);

-- Zápas 5107 – TJ Chotěšov vs TJ Slavoj Mýto, 27.09.2025 10:15, skóre 2:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5107, 1, '2025-09-27 10:15:00', '2025-09-27 10:15:00', '', 'TJ Chotěšov vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'tj-chotesov-vs-tj-slavoj-myto-2025-09-27',
   '2025-09-27 10:15:00', '2025-09-27 10:15:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5107, 'datum_zapasu', '2025-09-27' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5107 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5107, 'cas_zapasu', '10:15' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5107 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5107, 'domaci', 'TJ Chotěšov' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5107 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5107, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5107 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5107, 'skore', '2:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5107 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5107, 1000, 0), (5107, 1001, 0), (5107, 1003, 0);

-- Zápas 5108 – TJ Slavoj Mýto vs FK Chlumčany, 04.10.2025 16:00, skóre 3:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5108, 1, '2025-10-04 16:00:00', '2025-10-04 16:00:00', '', 'TJ Slavoj Mýto vs FK Chlumčany',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-fk-chlumcany-2025-10-04',
   '2025-10-04 16:00:00', '2025-10-04 16:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5108, 'datum_zapasu', '2025-10-04' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5108 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5108, 'cas_zapasu', '16:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5108 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5108, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5108 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5108, 'hoste', 'FK Chlumčany' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5108 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5108, 'skore', '3:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5108 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5108, 1000, 0), (5108, 1001, 0), (5108, 1003, 0);

-- Zápas 5109 – FK Koloveč vs TJ Slavoj Mýto, 12.10.2025 16:00, skóre 0:1
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5109, 1, '2025-10-12 16:00:00', '2025-10-12 16:00:00', '', 'FK Koloveč vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'fk-kolovec-vs-tj-slavoj-myto-2025-10-12',
   '2025-10-12 16:00:00', '2025-10-12 16:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5109, 'datum_zapasu', '2025-10-12' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5109 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5109, 'cas_zapasu', '16:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5109 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5109, 'domaci', 'FK Koloveč' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5109 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5109, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5109 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5109, 'skore', '0:1' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5109 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5109, 1000, 0), (5109, 1001, 0), (5109, 1003, 0);

-- Zápas 5110 – TJ Slavoj Mýto vs FK Bohemia Kaznějov, 18.10.2025 15:30, skóre 2:1
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5110, 1, '2025-10-18 15:30:00', '2025-10-18 15:30:00', '', 'TJ Slavoj Mýto vs FK Bohemia Kaznějov',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-fk-bohemia-kaznejov-2025-10-18',
   '2025-10-18 15:30:00', '2025-10-18 15:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5110, 'datum_zapasu', '2025-10-18' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5110 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5110, 'cas_zapasu', '15:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5110 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5110, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5110 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5110, 'hoste', 'FK Bohemia Kaznějov' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5110 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5110, 'skore', '2:1' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5110 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5110, 1000, 0), (5110, 1001, 0), (5110, 1003, 0);

-- Zápas 5111 – FK Radnice vs TJ Slavoj Mýto, 26.10.2025 14:30, skóre 0:6
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5111, 1, '2025-10-26 14:30:00', '2025-10-26 14:30:00', '', 'FK Radnice vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'fk-radnice-vs-tj-slavoj-myto-2025-10-26',
   '2025-10-26 14:30:00', '2025-10-26 14:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5111, 'datum_zapasu', '2025-10-26' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5111 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5111, 'cas_zapasu', '14:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5111 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5111, 'domaci', 'FK Radnice' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5111 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5111, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5111 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5111, 'skore', '0:6' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5111 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5111, 1000, 0), (5111, 1001, 0), (5111, 1003, 0);

-- Zápas 5112 – TJ Slavoj Mýto vs FK Nýrsko, 01.11.2025 14:00, skóre 0:1
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5112, 1, '2025-11-01 14:00:00', '2025-11-01 14:00:00', '', 'TJ Slavoj Mýto vs FK Nýrsko',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-fk-nyrsko-2025-11-01',
   '2025-11-01 14:00:00', '2025-11-01 14:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5112, 'datum_zapasu', '2025-11-01' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5112 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5112, 'cas_zapasu', '14:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5112 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5112, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5112 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5112, 'hoste', 'FK Nýrsko' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5112 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5112, 'skore', '0:1' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5112 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5112, 1000, 0), (5112, 1001, 0), (5112, 1003, 0);

-- Zápas 5113 – FK Holýšov vs TJ Slavoj Mýto, 09.11.2025 14:00, skóre 0:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5113, 1, '2025-11-09 14:00:00', '2025-11-09 14:00:00', '', 'FK Holýšov vs TJ Slavoj Mýto',
   '', 'publish', 'closed', 'closed', 'fk-holysov-vs-tj-slavoj-myto-2025-11-09',
   '2025-11-09 14:00:00', '2025-11-09 14:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5113, 'datum_zapasu', '2025-11-09' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5113 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5113, 'cas_zapasu', '14:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5113 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5113, 'domaci', 'FK Holýšov' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5113 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5113, 'hoste', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5113 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5113, 'skore', '0:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5113 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5113, 1000, 0), (5113, 1001, 0), (5113, 1003, 0);

-- Zápas 5114 – TJ Slavoj Mýto vs FK Luby, 15.11.2025 13:30, skóre 1:1
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5114, 1, '2025-11-15 13:30:00', '2025-11-15 13:30:00', '', 'TJ Slavoj Mýto vs FK Luby',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-vs-fk-luby-2025-11-15',
   '2025-11-15 13:30:00', '2025-11-15 13:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5114, 'datum_zapasu', '2025-11-15' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5114 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5114, 'cas_zapasu', '13:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5114 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5114, 'domaci', 'TJ Slavoj Mýto' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5114 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5114, 'hoste', 'FK Luby' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5114 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5114, 'skore', '1:1' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5114 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5114, 1000, 0), (5114, 1001, 0), (5114, 1003, 0);

-- ============================================================
-- 7. ZÁPASY MUŽI B – TJ Slavoj Mýto B, 1.B třída sk. C 2025/26
--    (CPT: zapas; všechny zápasy jsou odehrané)
-- ============================================================

-- Zápas 5200 – TJ Slavoj Mýto B vs FK Úněšov, 24.08.2025 17:30, skóre 4:3
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5200, 1, '2025-08-24 17:30:00', '2025-08-24 17:30:00', '', 'TJ Slavoj Mýto B vs FK Úněšov',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-unesov-2025-08-24',
   '2025-08-24 17:30:00', '2025-08-24 17:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5200, 'datum_zapasu', '2025-08-24' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5200 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5200, 'cas_zapasu', '17:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5200 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5200, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5200 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5200, 'hoste', 'FK Úněšov' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5200 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5200, 'skore', '4:3' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5200 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5200, 1000, 0), (5200, 1002, 0), (5200, 1003, 0);

-- Zápas 5201 – FK Všeruby vs TJ Slavoj Mýto B, 27.08.2025 18:00, skóre 2:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5201, 1, '2025-08-27 18:00:00', '2025-08-27 18:00:00', '', 'FK Všeruby vs TJ Slavoj Mýto B',
   '', 'publish', 'closed', 'closed', 'fk-vseruby-vs-tj-slavoj-myto-b-2025-08-27',
   '2025-08-27 18:00:00', '2025-08-27 18:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5201, 'datum_zapasu', '2025-08-27' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5201 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5201, 'cas_zapasu', '18:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5201 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5201, 'domaci', 'FK Všeruby' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5201 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5201, 'hoste', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5201 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5201, 'skore', '2:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5201 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5201, 1000, 0), (5201, 1002, 0), (5201, 1003, 0);

-- Zápas 5202 – FK Ledce vs TJ Slavoj Mýto B, 31.08.2025 13:30, skóre 6:0
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5202, 1, '2025-08-31 13:30:00', '2025-08-31 13:30:00', '', 'FK Ledce vs TJ Slavoj Mýto B',
   '', 'publish', 'closed', 'closed', 'fk-ledce-vs-tj-slavoj-myto-b-2025-08-31',
   '2025-08-31 13:30:00', '2025-08-31 13:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5202, 'datum_zapasu', '2025-08-31' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5202 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5202, 'cas_zapasu', '13:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5202 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5202, 'domaci', 'FK Ledce' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5202 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5202, 'hoste', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5202 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5202, 'skore', '6:0' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5202 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5202, 1000, 0), (5202, 1002, 0), (5202, 1003, 0);

-- Zápas 5203 – TJ Slavoj Mýto B vs FK Touškov, 07.09.2025 17:00, skóre 1:3
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5203, 1, '2025-09-07 17:00:00', '2025-09-07 17:00:00', '', 'TJ Slavoj Mýto B vs FK Touškov',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-touskov-2025-09-07',
   '2025-09-07 17:00:00', '2025-09-07 17:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5203, 'datum_zapasu', '2025-09-07' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5203 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5203, 'cas_zapasu', '17:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5203 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5203, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5203 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5203, 'hoste', 'FK Touškov' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5203 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5203, 'skore', '1:3' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5203 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5203, 1000, 0), (5203, 1002, 0), (5203, 1003, 0);

-- Zápas 5204 – TJ Zbiroh vs TJ Slavoj Mýto B, 13.09.2025 17:00, skóre 7:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5204, 1, '2025-09-13 17:00:00', '2025-09-13 17:00:00', '', 'TJ Zbiroh vs TJ Slavoj Mýto B',
   '', 'publish', 'closed', 'closed', 'tj-zbiroh-vs-tj-slavoj-myto-b-2025-09-13',
   '2025-09-13 17:00:00', '2025-09-13 17:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5204, 'datum_zapasu', '2025-09-13' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5204 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5204, 'cas_zapasu', '17:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5204 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5204, 'domaci', 'TJ Zbiroh' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5204 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5204, 'hoste', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5204 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5204, 'skore', '7:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5204 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5204, 1000, 0), (5204, 1002, 0), (5204, 1003, 0);

-- Zápas 5205 – TJ Slavoj Mýto B vs FK Plasy, 21.09.2025 10:00, skóre 1:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5205, 1, '2025-09-21 10:00:00', '2025-09-21 10:00:00', '', 'TJ Slavoj Mýto B vs FK Plasy',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-plasy-2025-09-21',
   '2025-09-21 10:00:00', '2025-09-21 10:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5205, 'datum_zapasu', '2025-09-21' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5205 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5205, 'cas_zapasu', '10:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5205 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5205, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5205 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5205, 'hoste', 'FK Plasy' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5205 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5205, 'skore', '1:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5205 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5205, 1000, 0), (5205, 1002, 0), (5205, 1003, 0);

-- Zápas 5206 – TJ Slavoj Mýto B vs FK Raková, 27.09.2025 10:15, skóre 0:1
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5206, 1, '2025-09-27 10:15:00', '2025-09-27 10:15:00', '', 'TJ Slavoj Mýto B vs FK Raková',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-rakova-2025-09-27',
   '2025-09-27 10:15:00', '2025-09-27 10:15:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5206, 'datum_zapasu', '2025-09-27' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5206 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5206, 'cas_zapasu', '10:15' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5206 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5206, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5206 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5206, 'hoste', 'FK Raková' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5206 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5206, 'skore', '0:1' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5206 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5206, 1000, 0), (5206, 1002, 0), (5206, 1003, 0);

-- Zápas 5207 – FK Doubravka B vs TJ Slavoj Mýto B, 05.10.2025 16:00, skóre 5:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5207, 1, '2025-10-05 16:00:00', '2025-10-05 16:00:00', '', 'FK Doubravka B vs TJ Slavoj Mýto B',
   '', 'publish', 'closed', 'closed', 'fk-doubravka-b-vs-tj-slavoj-myto-b-2025-10-05',
   '2025-10-05 16:00:00', '2025-10-05 16:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5207, 'datum_zapasu', '2025-10-05' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5207 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5207, 'cas_zapasu', '16:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5207 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5207, 'domaci', 'FK Doubravka B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5207 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5207, 'hoste', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5207 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5207, 'skore', '5:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5207 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5207, 1000, 0), (5207, 1002, 0), (5207, 1003, 0);

-- Zápas 5208 – TJ Slavoj Mýto B vs FK Volduchy, 11.10.2025 10:00, skóre 5:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5208, 1, '2025-10-11 10:00:00', '2025-10-11 10:00:00', '', 'TJ Slavoj Mýto B vs FK Volduchy',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-volduchy-2025-10-11',
   '2025-10-11 10:00:00', '2025-10-11 10:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5208, 'datum_zapasu', '2025-10-11' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5208 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5208, 'cas_zapasu', '10:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5208 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5208, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5208 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5208, 'hoste', 'FK Volduchy' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5208 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5208, 'skore', '5:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5208 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5208, 1000, 0), (5208, 1002, 0), (5208, 1003, 0);

-- Zápas 5209 – FK Rapid Plzeň B vs TJ Slavoj Mýto B, 19.10.2025 15:30, skóre 7:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5209, 1, '2025-10-19 15:30:00', '2025-10-19 15:30:00', '', 'FK Rapid Plzeň B vs TJ Slavoj Mýto B',
   '', 'publish', 'closed', 'closed', 'fk-rapid-plzen-b-vs-tj-slavoj-myto-b-2025-10-19',
   '2025-10-19 15:30:00', '2025-10-19 15:30:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5209, 'datum_zapasu', '2025-10-19' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5209 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5209, 'cas_zapasu', '15:30' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5209 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5209, 'domaci', 'FK Rapid Plzeň B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5209 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5209, 'hoste', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5209 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5209, 'skore', '7:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5209 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5209, 1000, 0), (5209, 1002, 0), (5209, 1003, 0);

-- Zápas 5210 – TJ Slavoj Mýto B vs FK Horní Bříza B, 25.10.2025 10:15, skóre 2:4
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5210, 1, '2025-10-25 10:15:00', '2025-10-25 10:15:00', '', 'TJ Slavoj Mýto B vs FK Horní Bříza B',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-horni-briza-b-2025-10-25',
   '2025-10-25 10:15:00', '2025-10-25 10:15:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5210, 'datum_zapasu', '2025-10-25' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5210 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5210, 'cas_zapasu', '10:15' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5210 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5210, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5210 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5210, 'hoste', 'FK Horní Bříza B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5210 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5210, 'skore', '2:4' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5210 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5210, 1000, 0), (5210, 1002, 0), (5210, 1003, 0);

-- Zápas 5211 – FK Bolevec vs TJ Slavoj Mýto B, 01.11.2025 14:00, skóre 3:0
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5211, 1, '2025-11-01 14:00:00', '2025-11-01 14:00:00', '', 'FK Bolevec vs TJ Slavoj Mýto B',
   '', 'publish', 'closed', 'closed', 'fk-bolevec-vs-tj-slavoj-myto-b-2025-11-01',
   '2025-11-01 14:00:00', '2025-11-01 14:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5211, 'datum_zapasu', '2025-11-01' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5211 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5211, 'cas_zapasu', '14:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5211 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5211, 'domaci', 'FK Bolevec' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5211 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5211, 'hoste', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5211 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5211, 'skore', '3:0' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5211 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5211, 1000, 0), (5211, 1002, 0), (5211, 1003, 0);

-- Zápas 5212 – TJ Slavoj Mýto B vs FK Příkosice, 08.11.2025 10:00, skóre 3:2
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5212, 1, '2025-11-08 10:00:00', '2025-11-08 10:00:00', '', 'TJ Slavoj Mýto B vs FK Příkosice',
   '', 'publish', 'closed', 'closed', 'tj-slavoj-myto-b-vs-fk-prikosice-2025-11-08',
   '2025-11-08 10:00:00', '2025-11-08 10:00:00', 'zapas', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5212, 'datum_zapasu', '2025-11-08' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5212 AND `meta_key` = 'datum_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5212, 'cas_zapasu', '10:00' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5212 AND `meta_key` = 'cas_zapasu');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5212, 'domaci', 'TJ Slavoj Mýto B' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5212 AND `meta_key` = 'domaci');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5212, 'hoste', 'FK Příkosice' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5212 AND `meta_key` = 'hoste');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5212, 'skore', '3:2' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5212 AND `meta_key` = 'skore');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5212, 1000, 0), (5212, 1002, 0), (5212, 1003, 0);

-- ============================================================
-- 8. HRÁČI – soupiska TJ Slavoj Mýto B, Sezóna 2025/26
--    (CPT: hrac; pozice „Neuvedeno" = term_taxonomy_id 1004)
-- ============================================================

-- Hráč 5300 – Milan Navrátil, #1
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5300, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Milan Navrátil',
   '', 'publish', 'closed', 'closed', 'milan-navratil',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5300, 'cislo', '1' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5300 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5300, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5300 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5300, 1000, 0), (5300, 1002, 0), (5300, 1004, 0);

-- Hráč 5301 – Marek Šobáň, #6
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5301, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Marek Šobáň',
   '', 'publish', 'closed', 'closed', 'marek-soban',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5301, 'cislo', '6' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5301 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5301, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5301 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5301, 1000, 0), (5301, 1002, 0), (5301, 1004, 0);

-- Hráč 5302 – Martin Drábek, #8
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5302, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Martin Drábek',
   '', 'publish', 'closed', 'closed', 'martin-drabek',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5302, 'cislo', '8' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5302 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5302, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5302 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5302, 1000, 0), (5302, 1002, 0), (5302, 1004, 0);

-- Hráč 5303 – Alexandr Čajkovskij, #9
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5303, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Alexandr Čajkovskij',
   '', 'publish', 'closed', 'closed', 'alexandr-cajkovsky',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5303, 'cislo', '9' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5303 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5303, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5303 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5303, 1000, 0), (5303, 1002, 0), (5303, 1004, 0);

-- Hráč 5304 – Matěj Tůma, #10
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5304, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Matěj Tůma',
   '', 'publish', 'closed', 'closed', 'matej-tuma',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5304, 'cislo', '10' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5304 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5304, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5304 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5304, 1000, 0), (5304, 1002, 0), (5304, 1004, 0);

-- Hráč 5305 – Jiří Drábek, #13
-- Poznámka: zdroj (slavojmyto.cz) uvádí číslo 13 pro oba hráče Jiří Drábek i Filip Stejskal.
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5305, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Jiří Drábek',
   '', 'publish', 'closed', 'closed', 'jiri-drabek',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5305, 'cislo', '13' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5305 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5305, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5305 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5305, 1000, 0), (5305, 1002, 0), (5305, 1004, 0);

-- Hráč 5306 – Filip Stejskal, #13
INSERT IGNORE INTO `wp_posts`
  (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`,
   `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_name`,
   `post_modified`, `post_modified_gmt`, `post_type`, `to_ping`, `pinged`,
   `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `comment_count`)
VALUES
  (5306, 1, '2025-08-01 00:00:00', '2025-08-01 00:00:00', '', 'Filip Stejskal',
   '', 'publish', 'closed', 'closed', 'filip-stejskal',
   '2025-08-01 00:00:00', '2025-08-01 00:00:00', 'hrac', '', '', '', 0, '', 0, 0);
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5306, 'cislo', '13' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5306 AND `meta_key` = 'cislo');
INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) SELECT 5306, 'tym_slug', 'muzi-b' WHERE NOT EXISTS (SELECT 1 FROM `wp_postmeta` WHERE `post_id` = 5306 AND `meta_key` = 'tym_slug');
INSERT IGNORE INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES (5306, 1000, 0), (5306, 1002, 0), (5306, 1004, 0);

-- ============================================================
-- 9. AKTUALIZACE POČTU TERMÍNŮ V TAXONOMIÍCH (term_taxonomy.count)
-- ============================================================

-- Sezóna 2025/26: 2 týmy + 28 zápasů + 7 hráčů = 37
UPDATE `wp_term_taxonomy` SET `count` = 37 WHERE `term_taxonomy_id` = 1000;
-- Kategorie Muži A: 1 tým + 15 zápasů = 16
UPDATE `wp_term_taxonomy` SET `count` = 16 WHERE `term_taxonomy_id` = 1001;
-- Kategorie Muži B: 1 tým + 13 zápasů + 7 hráčů = 21
UPDATE `wp_term_taxonomy` SET `count` = 21 WHERE `term_taxonomy_id` = 1002;
-- Stav Odehraný: 28 zápasů
UPDATE `wp_term_taxonomy` SET `count` = 28 WHERE `term_taxonomy_id` = 1003;
-- Pozice Neuvedeno: 7 hráčů
UPDATE `wp_term_taxonomy` SET `count` = 7  WHERE `term_taxonomy_id` = 1004;

-- ============================================================
-- Konec seed scriptu
-- ============================================================
