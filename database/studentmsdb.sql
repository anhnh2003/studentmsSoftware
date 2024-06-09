-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 05:32 PM
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
-- Table structure for table `tblattendance`
--

CREATE TABLE `tblattendance` (
  `ID` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `Secret` varchar(100) DEFAULT NULL,
  `CreationTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastGeneratedTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Scholarship Year 2024', 'Results for this year\'s Scholarship is being sent through student\'s email! Due date for any inquiry is May 20th.', '2024-04-02 19:04:10');

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
  `is2FA` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`ID`, `StudentName`, `Email`, `Gender`, `ContactNumber`, `UserName`, `Password`, `CreationTime`, `role_id`, `StuID`, `is2FA`) VALUES
(30000, 'Nguyen A', 'nguyena@gmail.com', 'Male', NULL, 'nguyena', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2022-01-13 14:09:04', 3, '20210001', 0),
(30001, 'Tran B', 'tranb@gmail.com', 'Male', NULL, 'tranb', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2022-01-16 06:23:33', 3, '20220001', 0),
(30002, 'Nguyen C', 'nguyenc@gmali.com', 'Female', NULL, 'nguyenc', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2022-01-19 15:24:52', 3, '20230001', 0),
(30003, 'Nguyen Quoc Huy', 'nguyenquochuy712@gmail.com', 'Male', 343868519, 'nguyenhuy', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', '2024-05-15 04:14:35', 3, '20210427', 0),
(30004, 'huy', 'a@b', 'Male', 1, 'huy', '$2y$10$73BQRavfaPK0RfIXh/ZBR.yGK5Gt5Gg2owY00HFiaPxJuxelhVKtu', '2024-06-04 15:37:07', 3, '20210000', 0);

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
-- Table structure for table `tblstudent_class`
--

CREATE TABLE `tblstudent_class` (
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent_class`
--

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
-- Table structure for table `tblstudent_question`
--

CREATE TABLE `tblstudent_question` (
  `student_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `Step1` varchar(255) DEFAULT NULL,
  `Step2` varchar(255) DEFAULT NULL,
  `Step3` varchar(255) DEFAULT NULL,
  `Step4` varchar(255) DEFAULT NULL,
  `Step5` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent_question`
--

INSERT INTO `tblstudent_question` (`student_id`, `question_id`, `Step1`, `Step2`, `Step3`, `Step4`, `Step5`) VALUES
(30003, 80004, '2           \r\n                      ', '                        ', '                        ', '                        ', '                        '),
(30003, 80005, '                             \r\n                      ', '                        ', '                        ', '                        ', '                        '),
(30003, 80007, '2', NULL, NULL, NULL, NULL),
(30003, 80009, 'hello', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_test`
--

CREATE TABLE `tblstudent_test` (
  `TID` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `TotalPoint` int(11) DEFAULT NULL,
  `StartTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `SubmitTime` timestamp NULL DEFAULT NULL,
  `IP` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent_test`
--

INSERT INTO `tblstudent_test` (`TID`, `student_id`, `test_id`, `TotalPoint`, `StartTime`, `SubmitTime`, `IP`) VALUES
(1, 30003, 60001, 10, '2024-04-15 09:05:00', '2024-04-15 09:55:59', NULL),
(2, 30002, 60003, NULL, '2024-05-20 09:40:00', NULL, NULL),
(3, 30003, 60004, 0, '2024-06-09 01:44:16', '2024-06-09 13:49:14', '::1'),
(4, 30003, 60004, NULL, '2024-06-09 13:49:44', NULL, '::1'),
(5, 30003, 60004, NULL, '2024-06-09 13:49:49', NULL, '::1'),
(6, 30003, 60005, NULL, '2024-06-09 14:18:29', NULL, '::1'),
(7, 30003, 60004, NULL, '2024-06-09 14:25:37', NULL, '::1'),
(8, 30003, 60005, NULL, '2024-06-09 14:25:52', NULL, '::1'),
(9, 30003, 60006, 0, '2024-06-09 14:55:46', '2024-06-09 15:27:11', '::1');

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
  `is2FA` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteacher`
--

INSERT INTO `tblteacher` (`ID`, `TeacherName`, `Email`, `Gender`, `UserName`, `Password`, `ContactNumber`, `CreationTime`, `role_id`, `TeaID`, `is2FA`) VALUES
(20000, 'Nguyen Anh', 'hwisigninguprandomthing@gmail.com', 'Male', 'anh', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', NULL, '2024-05-15 03:18:58', 2, '20000', 0),
(20001, 'Nguyen Be', 'b@gmail.com', 'Male', 'be', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', NULL, '2024-05-16 02:35:52', 2, '20001', 0),
(20002, 'Duong Nam', 'c@gmail.com', 'Male', 'nam', '$2y$10$9B09BrFure7jRVEbDTbh0.DVTTcK4djM8.dvcfI4Ahj54Rev9FQ2u', 113, '2024-05-18 01:05:42', 2, '20002', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbltest`
--

CREATE TABLE `tbltest` (
  `ID` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `TestName` varchar(100) NOT NULL,
  `CreationTime` timestamp NULL DEFAULT current_timestamp(),
  `StartTime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `EndTime` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltest`
--

INSERT INTO `tbltest` (`ID`, `class_id`, `TestName`, `CreationTime`, `StartTime`, `EndTime`) VALUES
(60000, 40000, 'Chap 1 Revision - Speaking Skills', '2024-05-12 00:00:00', '2024-03-14 14:00:00', '2024-03-14 14:30:00'),
(60001, 40001, 'Midterm Test - Python', '2024-05-13 00:00:00', '2024-04-15 09:00:00', '2024-04-15 10:30:00'),
(60002, 40001, 'Final Test - Secure Coding', '2024-05-14 00:00:00', '2024-06-20 09:00:00', '2024-06-20 11:00:00'),
(60003, 40001, 'Revision Test - Java', '2024-05-15 00:00:00', '2024-05-19 17:00:00', '2024-05-26 17:00:00'),
(60004, 40001, 'Calculus', '2024-06-09 00:59:07', '2024-06-08 00:59:00', '2024-06-28 00:59:00'),
(60005, 40001, 'Simple math', '2024-06-09 14:08:01', '2024-06-02 14:07:00', '2024-06-29 14:07:00'),
(60006, 40001, 'Some easy', '2024-06-09 14:54:33', '2024-05-26 14:54:00', '2024-08-10 14:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbltest_question`
--

CREATE TABLE `tbltest_question` (
  `ID` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `Question` mediumtext NOT NULL,
  `Point` int(11) NOT NULL DEFAULT 1,
  `Step1Des` varchar(255) NOT NULL,
  `Step1Sol` varchar(255) NOT NULL,
  `Step2Des` varchar(255) DEFAULT NULL,
  `Step2Sol` varchar(255) DEFAULT NULL,
  `Step3Des` varchar(255) DEFAULT NULL,
  `Step3Sol` varchar(255) DEFAULT NULL,
  `Step4Des` varchar(255) DEFAULT NULL,
  `Step4Sol` varchar(255) DEFAULT NULL,
  `Step5Des` varchar(255) DEFAULT NULL,
  `Step5Sol` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbltest_question`
--

INSERT INTO `tbltest_question` (`ID`, `test_id`, `Question`, `Point`, `Step1Des`, `Step1Sol`, `Step2Des`, `Step2Sol`, `Step3Des`, `Step3Sol`, `Step4Des`, `Step4Sol`, `Step5Des`, `Step5Sol`) VALUES
(80000, 60003, '1 + 1 = ?', 1, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80001, 60003, 'What is the correct answer?', 2, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80002, 60003, 'Untitled Question', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80003, 60003, 'Untitled Question', 0, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80004, 60004, 'Calculate 1+1', 2, 'Just write the answer', '1                      ', NULL, '                        ', NULL, '                        ', NULL, '                        ', NULL, '                        '),
(80005, 60004, 'Calculate 2+2', 1, 'Just write the answer', '4                             \r\n                      ', NULL, '                        ', NULL, '                        ', NULL, '                        ', NULL, '                        '),
(80006, 60004, 'Untitled Question', 0, 'Untitled', 'Untitled', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80007, 60005, 'Calculate 1+1', 0, 'just write the answer (e.g: 1)', '2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80009, 60006, 'Just type \'hello\'', 1, 'just type \'hello\' (without the quotes)', 'hello', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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

--
-- Dumping data for table `tbltoken`
--

INSERT INTO `tbltoken` (`UserToken`, `UserID`, `CreationTime`, `role_id`) VALUES
('10e9cb2ad2f8e5377d1a6d27b833e67d3bf4c01a4c1c980f43ddd6b41d77c956', 30003, '2024-05-21 10:18:14', 3),
('1ce43b9a938d9219a00e0a6cdc9be1017d313dd533617c4da0e40a1847ee8cfb', 20000, '2024-06-04 22:33:25', 2),
('211b1c2632b7ccce417a2067b6ece0ea6019781fc6327c58c99c0883b9296ad0', 30003, '2024-06-09 13:55:40', 3),
('30b64d87bfced5a911e19953950e108a583b59e0222d17ef39b312a8ba2e7836', 20000, '2024-06-04 22:48:01', 2),
('3842d7c0728cab11fe933dce403ea2bd977a1cf50b28ffcc487b3bf7bd8c570e', 20000, '2024-06-09 07:56:58', 2),
('3c0f8a17659e20e73758a40b6bb2efcaf2eec69c64e52194873720e65758e338', 20000, '2024-05-20 08:31:10', 2),
('3f56854404d05a55f9a624ec03a57e3593611417e39d26e058a67169a6d3a48b', 30004, '2024-06-04 22:37:25', 3),
('405a646ab0828ffde067e0220616b7533c28c8113682b8d2931a76649eb09b9c', 10000, '2024-06-04 22:47:40', 1),
('6b22f0138aa86b08feac4063b9dddc00f36f70fe6e1545e6d819be73ee9c643b', 20000, '2024-06-04 22:47:21', 2),
('73c829588942154259ad362a6acf64e1790645c1b53412c394a1551c18d8d774', 20000, '2024-06-09 19:47:07', 2),
('83b1dcf806d4909382a636c62dda082984e35f2e00b1a86b9336f62715add718', 20000, '2024-06-09 20:22:48', 2),
('8830d40fe639da3a83d43acac7d2b6c273e7610b813f55bbaad7f738831646b7', 30003, '2024-06-09 22:13:49', 3),
('921ba220720ff9116a8cef4b17e9dfb775953138fdb6fa57981af76a95d7352e', 10000, '2024-06-04 22:33:52', 1),
('94f0c38df28874b64ce8b277b499a4dc17e74e01936bc09fd0661aa0503a56b7', 30003, '2024-06-09 08:42:49', 3),
('a37a59039b04c01a00390bf66bf57bf25cc7f4915b5a9003e559c2ebf7ebe14b', 30003, '2024-06-09 14:15:19', 3),
('a7dfc130a68b6c29ef362eba1f93edd2e24980df044a329341b24617a4f25789', 30003, '2024-05-20 08:33:46', 3),
('add001f329bc1c4a27355d0a7e52951a8b63acc375ab4ba23b96c3c2355c1c1c', 20000, '2024-05-20 08:31:19', 2),
('c39ca508ed1b313b412e55b1b14744652c35a95c6d5857ff07a8bc0ce0141753', 30003, '2024-05-20 07:53:32', 3),
('c7d88c847362e3b833df9bd2b02a9f59c4df43ce5ef7a5dc0e2cc4958cf303fa', 20000, '2024-06-09 21:35:05', 2),
('d853f03b33ab3913a628028f4faacdb26a78d8691fe17ec6ae985d69e5a66698', 20000, '2024-05-21 10:17:11', 2),
('e0ffb7060faa0d44c4d213e0838404e9028715f932abf4b20bc20dcc908e37dd', 20000, '2024-06-09 20:34:26', 2),
('e5dbc925809b59020a0a99f0e633d8b8f6ede28c37e505338fab455aefe003ed', 30003, '2024-05-20 08:34:09', 3),
('f650c4b9800eb9653d5fc0d6c65353b5ae835fe30e7ab5f484cd45f07a53f14b', 20000, '2024-06-04 22:27:00', 2),
('fa1fdc7d6dfb3c958ad6e6e747d595a3adc87fa4ef479be23b71d842aaeb52f9', 20000, '2024-06-09 22:15:41', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `tblattendance_ibfk_1` (`class_id`);

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
-- Indexes for table `tblstudent_attendance`
--
ALTER TABLE `tblstudent_attendance`
  ADD KEY `tblstudent_attendance_ibfk_1` (`student_id`),
  ADD KEY `tblstudent_attendance_ibfk_2` (`attendance_id`);

--
-- Indexes for table `tblstudent_class`
--
ALTER TABLE `tblstudent_class`
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `tblstudent_question`
--
ALTER TABLE `tblstudent_question`
  ADD KEY `tblstudent_question_ibfk_1` (`student_id`),
  ADD KEY `tblstudent_question_ibfk_2` (`question_id`);

--
-- Indexes for table `tblstudent_test`
--
ALTER TABLE `tblstudent_test`
  ADD PRIMARY KEY (`TID`),
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
-- Indexes for table `tbltest_question`
--
ALTER TABLE `tbltest_question`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `tbltest_question_ibfk_1` (`test_id`);

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
-- AUTO_INCREMENT for table `tblattendance`
--
ALTER TABLE `tblattendance`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70000;

--
-- AUTO_INCREMENT for table `tblclass`
--
ALTER TABLE `tblclass`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40004;

--
-- AUTO_INCREMENT for table `tblnotice`
--
ALTER TABLE `tblnotice`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30005;

--
-- AUTO_INCREMENT for table `tblstudent_test`
--
ALTER TABLE `tblstudent_test`
  MODIFY `TID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20003;

--
-- AUTO_INCREMENT for table `tbltest`
--
ALTER TABLE `tbltest`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60007;

--
-- AUTO_INCREMENT for table `tbltest_question`
--
ALTER TABLE `tbltest_question`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80010;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblattendance`
--
ALTER TABLE `tblattendance`
  ADD CONSTRAINT `tblattendance_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`ID`);

--
-- Constraints for table `tblclass`
--
ALTER TABLE `tblclass`
  ADD CONSTRAINT `tblclass_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `tblteacher` (`ID`);

--
-- Constraints for table `tblstudent_attendance`
--
ALTER TABLE `tblstudent_attendance`
  ADD CONSTRAINT `tblstudent_attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`),
  ADD CONSTRAINT `tblstudent_attendance_ibfk_2` FOREIGN KEY (`attendance_id`) REFERENCES `tblattendance` (`ID`);

--
-- Constraints for table `tblstudent_class`
--
ALTER TABLE `tblstudent_class`
  ADD CONSTRAINT `tblstudent_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`),
  ADD CONSTRAINT `tblstudent_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `tblclass` (`ID`);

--
-- Constraints for table `tblstudent_question`
--
ALTER TABLE `tblstudent_question`
  ADD CONSTRAINT `tblstudent_question_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`ID`),
  ADD CONSTRAINT `tblstudent_question_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `tbltest_question` (`ID`);

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

--
-- Constraints for table `tbltest_question`
--
ALTER TABLE `tbltest_question`
  ADD CONSTRAINT `tbltest_question_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tbltest` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
