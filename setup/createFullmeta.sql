CREATE TABLE `fullmeta` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(256) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `album` varchar(256) DEFAULT NULL,
  `genre` varchar(45) NOT NULL,
  `artist` varchar(256) DEFAULT NULL,
  `year` int DEFAULT NULL,
  `filesize` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename_UNIQUE` (`filename`),
  KEY `artist` (`title`,`artist`),
  KEY `filename` (`filename`),
  FULLTEXT KEY `genreFULLTEXT` (`genre`)
) ENGINE=InnoDB AUTO_INCREMENT=7421 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;