-- MySQL dump 10.13  Distrib 8.0.27, for Linux (x86_64)
--
-- Host: localhost    Database: pomodoro
-- ------------------------------------------------------
-- Server version	8.0.27-0ubuntu0.21.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `email` varchar(45) NOT NULL,
  `pomodoro_duration` int NOT NULL,
  `break_duration` int NOT NULL,
  `long_break_duration` int NOT NULL,
  `pomodoros_until_break` int NOT NULL,
  `break_auto_start` int NOT NULL,
  `pomodoro_auto_start` int NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('zalavakas@gmail.com1',5,1,15,4,1,0),('zalavakas@gmail.com1123123',25,5,15,4,1,0),('zalavakas@gmail.com12',25,5,15,4,1,0),('zalavakas@gmail.com12311',25,5,15,4,1,0),('zalavakas@gmail.com123sad',25,5,15,4,1,0),('zalavakas@gmail.com213123',25,5,15,4,1,0),('zalavakas@gmail.comasd',25,5,15,4,1,0),('zalavakas@gmail.comasdasd',25,5,15,4,1,0),('zilvinas.vysniauskas99@gmail.com',25,5,15,4,1,0);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `email` varchar(45) NOT NULL,
  `password` varchar(450) NOT NULL,
  `tableId` varchar(450) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `tableId_UNIQUE` (`tableId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('zalavakas@gmail.com1','$2y$12$iaWnbLDGSWiFfRc7pdTcDu/fjiMTyDr8ajpk8o.v7evZyUSgPO5mi','userStatistics61d9b3d74aa040_72009745');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userStatistics61d9b3d74aa040_72009745`
--

DROP TABLE IF EXISTS `userStatistics61d9b3d74aa040_72009745`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `userStatistics61d9b3d74aa040_72009745` (
  `task` varchar(45) NOT NULL,
  `date` date NOT NULL,
  `time` int NOT NULL,
  PRIMARY KEY (`task`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userStatistics61d9b3d74aa040_72009745`
--

LOCK TABLES `userStatistics61d9b3d74aa040_72009745` WRITE;
/*!40000 ALTER TABLE `userStatistics61d9b3d74aa040_72009745` DISABLE KEYS */;
INSERT INTO `userStatistics61d9b3d74aa040_72009745` VALUES ('123123','2022-01-10',0),('cxcxc','2022-01-10',0),('finaly','2022-01-08',0),('none','2022-01-08',205),('none','2022-01-09',15),('none','2022-01-10',5),('none','2022-01-11',5),('none','2022-01-12',5),('random','2022-01-08',1000),('test','2022-01-07',81),('test','2022-01-08',10),('testTask','2022-01-10',0),('xxxx','2022-01-10',0);
/*!40000 ALTER TABLE `userStatistics61d9b3d74aa040_72009745` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-01-12 10:23:55
