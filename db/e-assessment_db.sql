-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Erstellungszeit: 11. Jul 2021 um 20:05
-- Server-Version: 5.7.34-0ubuntu0.18.04.1
-- PHP-Version: 7.4.20

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `e-assessment_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `answers`
--

CREATE TABLE `answers`
(
    `id`            int(11) NOT NULL,
    `userId`        int(11) NOT NULL,
    `questionId`    int(11) NOT NULL,
    `correctness`   tinyint(1) NOT NULL,
    `solved_at`     date NOT NULL,
    `misconception` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `answers`
--

INSERT INTO `answers` (`id`, `userId`, `questionId`, `correctness`, `solved_at`, `misconception`)
VALUES (1, 1, 0, 1, '2021-07-08', 0),
       (2, 1, 1, 0, '2021-07-08', 0),
       (3, 1, 2, 0, '2021-07-08', 0),
       (4, 1, 3, 1, '2021-07-08', 0),
       (5, 1, 4, 0, '2021-07-08', 0),
       (6, 1, 5, 0, '2021-07-08', 0),
       (7, 1, 6, 0, '2021-07-08', 0),
       (8, 1, 7, 0, '2021-07-08', 0),
       (9, 1, 0, 0, '2021-07-09', 0),
       (10, 1, 1, 0, '2021-07-09', 0),
       (11, 1, 2, 1, '2021-07-09', 0),
       (12, 1, 3, 1, '2021-07-09', 0),
       (13, 1, 4, 0, '2021-07-09', 0),
       (14, 1, 5, 0, '2021-07-09', 0),
       (15, 1, 6, 1, '2021-07-09', 0),
       (16, 1, 7, 1, '2021-07-09', 0),
       (17, 1, 0, 1, '2021-07-09', 0),
       (18, 1, 1, 0, '2021-07-09', 0),
       (19, 1, 2, 0, '2021-07-09', 0),
       (20, 1, 3, 0, '2021-07-09', 0),
       (21, 1, 4, 0, '2021-07-09', 0),
       (22, 1, 5, 0, '2021-07-09', 0),
       (23, 1, 6, 1, '2021-07-09', 0),
       (24, 1, 7, 0, '2021-07-09', 0),
       (25, 1, 0, 1, '2021-07-09', 0),
       (26, 1, 1, 0, '2021-07-09', 0),
       (27, 1, 2, 0, '2021-07-09', 0),
       (28, 1, 3, 0, '2021-07-09', 0),
       (29, 1, 4, 0, '2021-07-09', 0),
       (30, 1, 5, 0, '2021-07-09', 0),
       (31, 1, 6, 1, '2021-07-09', 0),
       (32, 1, 7, 0, '2021-07-09', 0),
       (33, 2, 0, 1, '2021-07-09', 0),
       (34, 2, 1, 0, '2021-07-09', 0),
       (35, 2, 2, 1, '2021-07-09', 0),
       (36, 2, 3, 0, '2021-07-09', 0),
       (37, 2, 4, 0, '2021-07-09', 0),
       (38, 2, 5, 0, '2021-07-09', 0),
       (39, 2, 6, 0, '2021-07-09', 0),
       (40, 2, 7, 0, '2021-07-09', 0),
       (41, 2, 0, 0, '2021-07-07', 0),
       (42, 2, 1, 0, '2021-07-07', 0),
       (43, 2, 2, 1, '2021-07-07', 0),
       (44, 2, 3, 0, '2021-07-07', 0),
       (45, 2, 4, 0, '2021-07-07', 0),
       (46, 2, 5, 0, '2021-07-07', 0),
       (47, 2, 6, 0, '2021-07-07', 0),
       (48, 2, 7, 0, '2021-07-07', 0),
       (49, 2, 0, 1, '2021-07-09', 0),
       (50, 2, 1, 0, '2021-07-09', 0),
       (51, 2, 2, 0, '2021-07-09', 0),
       (52, 2, 3, 0, '2021-07-09', 0),
       (53, 2, 4, 0, '2021-07-09', 0),
       (54, 2, 5, 1, '2021-07-09', 0),
       (55, 2, 6, 1, '2021-07-09', 0),
       (56, 2, 7, 1, '2021-07-09', 0),
       (57, 3, 0, 1, '2021-07-09', 0),
       (58, 3, 1, 0, '2021-07-09', 0),
       (59, 3, 2, 0, '2021-07-09', 0),
       (60, 3, 3, 0, '2021-07-09', 0),
       (61, 3, 4, 0, '2021-07-09', 0),
       (62, 3, 5, 0, '2021-07-09', 0),
       (63, 3, 6, 1, '2021-07-09', 0),
       (64, 3, 7, 1, '2021-07-09', 0),
       (65, 1, 0, 1, '2021-07-10', 0),
       (66, 1, 1, 1, '2021-07-10', 0),
       (67, 1, 2, 1, '2021-07-10', 0),
       (68, 1, 3, 1, '2021-07-10', 0),
       (69, 1, 4, 1, '2021-07-10', 0),
       (70, 1, 5, 1, '2021-07-10', 0),
       (71, 1, 6, 1, '2021-07-10', 0),
       (72, 1, 7, 1, '2021-07-10', 0),
       (73, 1, 0, 1, '2021-07-10', 0),
       (74, 1, 1, 1, '2021-07-10', 0),
       (75, 1, 2, 1, '2021-07-10', 0),
       (76, 1, 3, 0, '2021-07-10', 0),
       (77, 1, 4, 0, '2021-07-10', 0),
       (78, 1, 5, 0, '2021-07-10', 0),
       (79, 1, 6, 0, '2021-07-10', 0),
       (80, 1, 7, 1, '2021-07-10', 0),
       (81, 1, 0, 1, '2021-07-10', 0),
       (82, 1, 1, 1, '2021-07-10', 0),
       (83, 1, 2, 0, '2021-07-10', 0),
       (84, 1, 3, 1, '2021-07-10', 0),
       (85, 1, 4, 0, '2021-07-10', 0),
       (86, 1, 5, 0, '2021-07-10', 0),
       (87, 1, 6, 1, '2021-07-10', 0),
       (88, 1, 7, 1, '2021-07-10', 0),
       (89, 3, 0, 1, '2021-07-10', 0),
       (90, 3, 1, 1, '2021-07-10', 0),
       (91, 3, 2, 1, '2021-07-10', 0),
       (92, 3, 3, 0, '2021-07-10', 0),
       (93, 3, 4, 1, '2021-07-10', 0),
       (94, 3, 5, 1, '2021-07-10', 0),
       (95, 3, 6, 1, '2021-07-10', 0),
       (96, 3, 7, 1, '2021-07-10', 0),
       (97, 3, 0, 1, '2021-07-11', 0),
       (98, 3, 1, 1, '2021-07-11', 0),
       (99, 3, 2, 1, '2021-07-11', 0),
       (100, 3, 3, 1, '2021-07-11', 0),
       (101, 3, 4, 1, '2021-07-11', 0),
       (102, 3, 5, 1, '2021-07-11', 0),
       (103, 3, 6, 1, '2021-07-11', 0),
       (104, 3, 7, 1, '2021-07-11', 0),
       (105, 1, 0, 1, '2021-07-11', 0),
       (106, 1, 1, 1, '2021-07-11', 0),
       (107, 1, 2, 1, '2021-07-11', 0),
       (108, 1, 3, 1, '2021-07-11', 0),
       (109, 1, 5, 1, '2021-07-11', 0),
       (110, 1, 6, 0, '2021-07-11', 0),
       (111, 1, 7, 0, '2021-07-11', 0),
       (112, 1, 0, 1, '2021-07-11', 0),
       (113, 1, 1, 0, '2021-07-11', 0),
       (114, 1, 2, 0, '2021-07-11', 0),
       (115, 1, 3, 0, '2021-07-11', 0),
       (116, 1, 5, 1, '2021-07-11', 0),
       (117, 1, 6, 0, '2021-07-11', 0),
       (118, 1, 7, 0, '2021-07-11', 0),
       (119, 1, 0, 1, '2021-07-11', 0),
       (120, 1, 1, 0, '2021-07-11', 0),
       (121, 1, 2, 1, '2021-07-11', 0),
       (122, 1, 3, 0, '2021-07-11', 0),
       (123, 1, 5, 1, '2021-07-11', 0),
       (124, 1, 6, 0, '2021-07-11', 0),
       (125, 1, 7, 0, '2021-07-11', 0),
       (126, 1, 4, 0, '2021-07-11', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tests`
--

CREATE TABLE `tests`
(
    `userId`  int(11) NOT NULL,
    `date`    date NOT NULL,
    `amount`  int(11) NOT NULL,
    `correct` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `tests`
--

INSERT INTO `tests` (`userId`, `date`, `amount`, `correct`)
VALUES (1, '2021-07-04', 6, 4),
       (1, '2021-07-05', 3, 2),
       (1, '2021-07-06', 2, 3),
       (1, '2021-07-09', 16, 4),
       (1, '2021-07-10', 3, 8),
       (1, '2021-07-11', 4, 2),
       (2, '2021-07-09', 3, 5),
       (3, '2021-07-09', 1, 4),
       (3, '2021-07-10', 1, 9),
       (3, '2021-07-11', 1, 12);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users`
(
    `id`              int(11) NOT NULL,
    `username`        varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `passwort`        varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `created_at`      timestamp                            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `amount_of_tests` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `passwort`, `created_at`, `amount_of_tests`)
VALUES (1, 'Sophia', '$2y$10$jSKfNUqNE7HvHVkRSSHmjOhPWyivjxOWumjIGeEARXjy4BvLHoVlW', '2021-06-27 12:55:28', 38),
       (2, 'Test', '$2y$10$2ADN9elJ89E5FbwVEQx4hOFobShoEb6V/UHeA6ihDIRsqZA2AXIlW', '2021-07-09 18:55:46', 3),
       (3, 'Teresa', '$2y$10$9hU1OLE7b0dmVsDQtB8U1.k47aqcusJNljtMt8gsznDBiNs.IElQW', '2021-07-09 20:01:33', 3);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `answers`
--
ALTER TABLE `answers`
    ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `tests`
--
ALTER TABLE `tests`
    ADD PRIMARY KEY (`userId`, `date`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE;

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `answers`
--
ALTER TABLE `answers`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
    MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
