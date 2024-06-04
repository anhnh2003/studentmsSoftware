-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2024 at 03:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studentmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `AdminName` varchar(50) DEFAULT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `ContactNumber` bigint(15) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL DEFAULT 1,
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`AdminName`, `UserName`, `ContactNumber`, `Email`, `Password`, `CreationTime`, `role_id`, `ID`) VALUES
('Admin', 'admin', 8979555558, 'minihoanganh@gmail.com', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2019-10-11 04:36:52', 1, 10000);

-- --------------------------------------------------------

--
-- Table structure for table `tblclass`
--

CREATE TABLE `tblclass` (
  `ID` int(11) NOT NULL,
  `ClassName` varchar(100) DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp(),
  `Room` varchar(20) DEFAULT NULL,
  `JoinCode` varchar(10) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclass`
--

INSERT INTO `tblclass` (`ID`, `ClassName`, `CreationTime`, `Room`, `JoinCode`, `teacher_id`) VALUES
(40000, 'Soft Skills', '2024-01-10 10:42:14', 'TC-204', '72nci1', 20000),
(40001, 'Introduction to Programming', '2024-01-10 10:45:35', 'B1-403', '9dn2dc', 20000),
(40002, 'Cryptography', '2024-01-12 10:42:41', 'B1-203', 'abc81h', 20001),
(40003, 'Web Programming', '2024-01-15 10:42:47', 'D8-108', '8d2n1d', 20002);

-- --------------------------------------------------------

--
-- Table structure for table `tblnotice`
--

CREATE TABLE `tblnotice` (
  `ID` int(5) NOT NULL,
  `NoticeTitle` mediumtext DEFAULT NULL,
  `ClassId` int(10) DEFAULT NULL,
  `NoticeMsg` mediumtext DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblnotice`
--

INSERT INTO `tblnotice` (`ID`, `NoticeTitle`, `ClassId`, `NoticeMsg`, `CreationTime`) VALUES
(1, 'Marks of Unit Test.', 40000, 'Meet your class teacher for seeing copies of unit test', '2022-01-19 06:35:58'),
(2, 'Schedule for Midterm Test', 40002, 'The Midterm test is currently being delayed. We will discuss further and inform you all soon.', '2024-04-30 18:17:03'),
(3, 'Schedule for Midterm Test', 40001, 'Our class will be testing on May 20th, on the same classroom.', '2024-05-02 18:17:03');

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(100) DEFAULT NULL,
  `PageTitle` mediumtext DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `ContactNumber` bigint(10) DEFAULT NULL,
  `UpdationDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `ContactNumber`, `UpdationDate`) VALUES
(1, 'aboutus', 'About Us', '<div style=\"text-align: start;\"><font color=\"#7b8898\" face=\"Mercury SSm A, Mercury SSm B, Georgia, Times, Times New Roman, Microsoft YaHei New, Microsoft Yahei, ????, ??, SimSun, STXihei, ????, serif\" size=\"6\"><b style=\"\">Project 13 - Secure Web Programming Course</b></font></div><div style=\"text-align: start;\"><font color=\"#7b8898\" face=\"Mercury SSm A, Mercury SSm B, Georgia, Times, Times New Roman, Microsoft YaHei New, Microsoft Yahei, ????, ??, SimSun, STXihei, ????, serif\" size=\"5\">Members:</font></div><div style=\"text-align: start;\"><ul><li><font color=\"#7b8898\" face=\"Mercury SSm A, Mercury SSm B, Georgia, Times, Times New Roman, Microsoft YaHei New, Microsoft Yahei, ????, ??, SimSun, STXihei, ????, serif\" size=\"5\">Nguyen Quoc Huy</font></li><li><font color=\"#7b8898\" face=\"Mercury SSm A, Mercury SSm B, Georgia, Times, Times New Roman, Microsoft YaHei New, Microsoft Yahei, ????, ??, SimSun, STXihei, ????, serif\" size=\"5\">Duong Hong Nam</font></li><li><font color=\"#7b8898\" face=\"Mercury SSm A, Mercury SSm B, Georgia, Times, Times New Roman, Microsoft YaHei New, Microsoft Yahei, ????, ??, SimSun, STXihei, ????, serif\" size=\"5\">Nguyen Hoang Anh</font></li><li><font color=\"#7b8898\" face=\"Mercury SSm A, Mercury SSm B, Georgia, Times, Times New Roman, Microsoft YaHei New, Microsoft Yahei, ????, ??, SimSun, STXihei, ????, serif\" size=\"5\">Le Duc Dung</font></li></ul></div>', NULL, NULL, NULL),
(2, 'contactus', 'Contact Us', '890,Sector 62, Gyan Sarovar, GAIL Noida(Delhi/NCR)', 'infodata@gmail.com', 7896541236, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpublicnotice`
--

CREATE TABLE `tblpublicnotice` (
  `ID` int(5) NOT NULL,
  `NoticeTitle` varchar(100) DEFAULT NULL,
  `NoticeMessage` mediumtext DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpublicnotice`
--

INSERT INTO `tblpublicnotice` (`ID`, `NoticeTitle`, `NoticeMessage`, `CreationTime`) VALUES
(1, 'School will re-open', 'Please prepare your learning resource.', '2024-01-20 09:11:57'),
(2, 'Scholarship Year 2024', "Results for this year's Scholarship is being sent through student's email! Due date for any inquiry is May 20th.", '2024-04-02 19:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `tblroles`
--

CREATE TABLE `tblroles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblroles`
--

INSERT INTO `tblroles` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Teacher'),
(3, 'Student');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `ID` int(11) NOT NULL,
  `StudentName` varchar(50) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Gender` varchar(7) DEFAULT NULL,
  `ContactNumber` bigint(15) DEFAULT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL DEFAULT 3,
  `StuID` varchar(10) DEFAULT NULL,
  `is2FA` boolean NOT NULL DEFAULT false
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`ID`, `StudentName`, `Email`, `Gender`, `ContactNumber`, `UserName`, `Password`, `CreationTime`, `role_id`, `StuID`) VALUES
(30000, 'Nguyen A', 'nguyena@gmail.com', 'Male', NULL, 'nguyena', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2022-01-13 14:09:04', 3, '20210001'),
(30001, 'Tran B', 'tranb@gmail.com', 'Male', NULL, 'tranb', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2022-01-16 06:23:33', 3, '20220001'),
(30002, 'Nguyen C', 'nguyenc@gmali.com', 'Female', NULL, 'nguyenc', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2022-01-19 15:24:52', 3, '20230001'),
(30003, 'Nguyen Quoc Huy', 'nguyenquochuy712@gmail.com', 'Male', 343868519, 'nguyenhuy', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2024-05-15 04:14:35', 3, '20210427');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_class`
--

CREATE TABLE `tblstudent_class` (
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tblstudent_class` (`student_id`, `class_id`) VALUES
(30003, 40001),
(30002, 40001),
(30001, 40001),
(30000, 40001),
(30000, 40000),
(30003, 40000),
(30002, 40000),
(30001, 40000),
(30000, 40002),
(30001, 40002),
(30000, 40003);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_test`
--

CREATE TABLE `tblstudent_test` (
  `TID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `TotalPoint` int(11) DEFAULT NULL,
  `StartTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `SubmitTime` timestamp NULL DEFAULT NULL,
  `IP` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tblstudent_test` (`student_id`, `test_id`, `TotalPoint`, `StartTime`, `SubmitTime`) VALUES
(30003, 60001, 10, '2024-04-15 09:05:00', '2024-04-15 09:55:59'),
(30002, 60003, NULL, '2024-05-20 09:40:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher`
--

CREATE TABLE `tblteacher` (
  `ID` int(11) NOT NULL,
  `TeacherName` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `Email` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `Gender` varchar(7) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `UserName` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `Password` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `ContactNumber` bigint(15) DEFAULT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL DEFAULT 2,
  `TeaID` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `is2FA` boolean DEFAULT false
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteacher`
--

INSERT INTO `tblteacher` (`ID`, `TeacherName`, `Email`, `Gender`, `Username`, `Password`, `ContactNumber`, `CreationTime`, `role_id`, `TeaID`) VALUES
(20000, 'Nguyen Anh', 'hwisigninguprandomthing@gmail.com', 'Male', 'anh', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', NULL, '2024-05-15 03:18:58', 2, '20000'),
(20001, 'Nguyen Be', 'b@gmail.com', 'Male', 'be', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', NULL, '2024-05-16 02:35:52', 2, '20001'),
(20002, 'Duong Nam', 'c@gmail.com', 'Male', 'nam', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', 113, '2024-05-18 01:05:42', 2, '20002');

-- --------------------------------------------------------

--
-- Table structure for table `tbltest`
--

CREATE TABLE `tbltest` (
  `ID` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `TestName` varchar(100) NOT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp(),
  `StartTime` timestamp NOT NULL,
  `EndTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbltest` (`ID`, `class_id`, `TestName`, `CreationTime`, `StartTime`, `EndTime`) VALUES
(60000, 40000, 'Chap 1 Revision - Speaking Skills', '2024-05-12 00:00:00', '2024-03-14 14:00:00', '2024-03-14 14:30:00'),
(60001, 40001, 'Midterm Test - Python', '2024-05-13 00:00:00', '2024-04-15 09:00:00', '2024-04-15 10:30:00'),
(60002, 40001, 'Final Test - Secure Coding', '2024-05-14 00:00:00', '2024-06-20 09:00:00', '2024-06-20 11:00:00'),
(60003, 40001, 'Revision Test - Java', '2024-05-15 00:00:00', '2024-05-19 17:00:00', '2024-05-26 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `ID` int(11) NOT NULL PRIMARY KEY,
  `class_id` int(11) NOT NULL,
  `Secret` varchar(100) DEFAULT NULL,
  `CreationTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastGeneratedTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_attendance`
--

CREATE TABLE `tblstudent_attendance` (
  `student_id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- --------------------------------------------------------

--
-- Table structure for table `tbltoken`
--

CREATE TABLE `tbltoken` (
  `UserToken` varchar(255) NOT NULL,
  `UserID` int(11) NOT NULL,
  `CreationTime` datetime NOT NULL DEFAULT current_timestamp(),
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbltest_question` (
  `ID` int(11) NOT NULL PRIMARY KEY,
  `test_id` int(11) NOT NULL,
  `Question` mediumtext NOT NULL,
  `AnsA` mediumtext NOT NULL,
  `AnsB` mediumtext DEFAULT NULL,
  `AnsC` mediumtext DEFAULT NULL,
  `AnsD` mediumtext DEFAULT NULL,
  `CorrectAns` varchar(1) NOT NULL,
  `Point` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbltest_question` (`ID`, `test_id`, `Question`, `AnsA`, `AnsB`, `AnsC`, `AnsD`, `CorrectAns`, `Point`) VALUES
(80000, 60003, '1 + 1 = ?', '2', '1', NULL, NULL, 'A', 1),
(80001, 60003, 'What is the correct answer?', 'Not this', 'This', 'Not this', 'Not this', 'B', 2);


CREATE TABLE `tblstudent_question` (
  `student_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `ChooseAns` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tblnotice`
--
ALTER TABLE `tblnotice`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpublicnotice`
--
ALTER TABLE `tblpublicnotice`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblroles`
--
ALTER TABLE `tblroles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblstudent_class`
--
ALTER TABLE `tblstudent_class`
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `tblstudent_test`
--
ALTER TABLE `tblstudent_test`
  ADD KEY `student_id` (`student_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Indexes for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbltest`
--
ALTER TABLE `tbltest`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `tbltoken`
--
ALTER TABLE `tbltoken`
  ADD PRIMARY KEY (`UserToken`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10001;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40003;

--
-- AUTO_INCREMENT for table `tblnotice`
--
ALTER TABLE `tblnotice`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblpublicnotice`
--
ALTER TABLE `tblpublicnotice`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblroles`
--
ALTER TABLE `tblroles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30004;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20003;

--
-- AUTO_INCREMENT for table `tbltest`
--
ALTER TABLE `tbltest`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60004;

ALTER TABLE `tblattendance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70000;

ALTER TABLE `tbltest_question`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80002;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD CONSTRAINT `tblclass_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `tblteacher` (`ID`);

--
-- Constraints for table `tblstudent_class`
--
ALTER TABLE `tblstudent_class`
  ADD CONSTRAINT `tblstudent_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`),
  ADD CONSTRAINT `tblstudent_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`ID`);

--
-- Constraints for table `tblstudent_test`
--
ALTER TABLE `tblstudent_test`
  ADD CONSTRAINT `tblstudent_test_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`),
  ADD CONSTRAINT `tblstudent_test_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tbltest` (`ID`);

--
-- Constraints for table `tbltest`
--
ALTER TABLE `tbltest`
  ADD CONSTRAINT `tbltest_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`ID`);
COMMIT;

ALTER TABLE `tblattendance`
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`ID`);

ALTER TABLE `tbltest_question`
  ADD CONSTRAINT `tbltest_question_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tbltest` (`ID`);

ALTER TABLE `tblstudent_question`
  ADD CONSTRAINT `tblstudent_question_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`),
  ADD CONSTRAINT `tblstudent_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `tbltest_question` (`ID`);
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
