-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 17 2019 г., 21:50
-- Версия сервера: 8.0.12
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tests_app_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answerstypeeasy`
--

CREATE TABLE `answerstypeeasy` (
  `ID` int(11) NOT NULL,
  `Answer` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Image` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `qtypecheckboxanswers`
--

CREATE TABLE `qtypecheckboxanswers` (
  `ID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `AnswerID` int(11) NOT NULL,
  `IsTrue` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `qtypeeditanswers`
--

CREATE TABLE `qtypeeditanswers` (
  `ID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `AnswerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `qtyperadioanswers`
--

CREATE TABLE `qtyperadioanswers` (
  `ID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `AnswerID` int(11) NOT NULL,
  `IsTrue` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `ID` int(11) NOT NULL,
  `Title` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Question` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Image` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Type` enum('QTypeRadio','QTypeEdit','QTypeCheckbox') COLLATE utf8mb4_general_ci NOT NULL,
  `Time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `testquestions`
--

CREATE TABLE `testquestions` (
  `TestID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `Number` int(11) NOT NULL,
  `Mark` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `ID` int(11) NOT NULL,
  `Name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `MinMarkPercent` int(11) NOT NULL DEFAULT '0',
  `hide` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `FirstName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `LastName` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Login` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Role` enum('user','admin') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `usertestquestions`
--

CREATE TABLE `usertestquestions` (
  `ID` int(11) NOT NULL,
  `UserTestID` int(11) NOT NULL,
  `Question` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `IsTrue` int(1) NOT NULL DEFAULT '0',
  `Mark` int(11) NOT NULL DEFAULT '0',
  `Time` int(11) DEFAULT '0',
  `StartTime` int(11) DEFAULT NULL,
  `EndTime` int(11) DEFAULT NULL,
  `QuestionID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `usertests`
--

CREATE TABLE `usertests` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TestID` int(11) NOT NULL,
  `StartTime` int(11) NOT NULL,
  `EndTime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `usertokens`
--

CREATE TABLE `usertokens` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `IP` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Device` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answerstypeeasy`
--
ALTER TABLE `answerstypeeasy`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `qtypecheckboxanswers`
--
ALTER TABLE `qtypecheckboxanswers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `QTRA_Answer_idx` (`AnswerID`),
  ADD KEY `QTRC_QTC_idx` (`QuestionID`);

--
-- Индексы таблицы `qtypeeditanswers`
--
ALTER TABLE `qtypeeditanswers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `QTEA_ATE_idx` (`AnswerID`),
  ADD KEY `QTEA_QTE_idx` (`QuestionID`);

--
-- Индексы таблицы `qtyperadioanswers`
--
ALTER TABLE `qtyperadioanswers`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `QTRA_Answer_idx` (`AnswerID`),
  ADD KEY `QTRdA_QTR_idx` (`QuestionID`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `testquestions`
--
ALTER TABLE `testquestions`
  ADD PRIMARY KEY (`TestID`,`QuestionID`),
  ADD KEY `TQ_Test_idx` (`TestID`),
  ADD KEY `TQ_Question_idx` (`QuestionID`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Login_UNIQUE` (`Login`);

--
-- Индексы таблицы `usertestquestions`
--
ALTER TABLE `usertestquestions`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UTQ_UT_idx` (`UserTestID`),
  ADD KEY `UTQ_Q_idx` (`QuestionID`);

--
-- Индексы таблицы `usertests`
--
ALTER TABLE `usertests`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UT_User_idx` (`UserID`),
  ADD KEY `UT_Test_idx` (`TestID`);

--
-- Индексы таблицы `usertokens`
--
ALTER TABLE `usertokens`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Token_UNIQUE` (`Token`),
  ADD KEY `UT_User_idx` (`UserID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answerstypeeasy`
--
ALTER TABLE `answerstypeeasy`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `qtypecheckboxanswers`
--
ALTER TABLE `qtypecheckboxanswers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `qtypeeditanswers`
--
ALTER TABLE `qtypeeditanswers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `qtyperadioanswers`
--
ALTER TABLE `qtyperadioanswers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `usertestquestions`
--
ALTER TABLE `usertestquestions`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `usertests`
--
ALTER TABLE `usertests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `usertokens`
--
ALTER TABLE `usertokens`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `qtypecheckboxanswers`
--
ALTER TABLE `qtypecheckboxanswers`
  ADD CONSTRAINT `QTRC_ATE` FOREIGN KEY (`AnswerID`) REFERENCES `answerstypeeasy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `QTRC_QTC` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `qtypeeditanswers`
--
ALTER TABLE `qtypeeditanswers`
  ADD CONSTRAINT `QTEA_ATE` FOREIGN KEY (`AnswerID`) REFERENCES `answerstypeeasy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `QTEA_QTE` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `qtyperadioanswers`
--
ALTER TABLE `qtyperadioanswers`
  ADD CONSTRAINT `QTRdA_ATE` FOREIGN KEY (`AnswerID`) REFERENCES `answerstypeeasy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `QTRdA_QTR` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `testquestions`
--
ALTER TABLE `testquestions`
  ADD CONSTRAINT `TQ_Question` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `TQ_Test` FOREIGN KEY (`TestID`) REFERENCES `tests` (`id`);

--
-- Ограничения внешнего ключа таблицы `usertestquestions`
--
ALTER TABLE `usertestquestions`
  ADD CONSTRAINT `UTQ_Q` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `UTQ_UT` FOREIGN KEY (`UserTestID`) REFERENCES `usertests` (`id`);

--
-- Ограничения внешнего ключа таблицы `usertests`
--
ALTER TABLE `usertests`
  ADD CONSTRAINT `UT_Test` FOREIGN KEY (`TestID`) REFERENCES `tests` (`id`),
  ADD CONSTRAINT `UT_User` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `usertokens`
--
ALTER TABLE `usertokens`
  ADD CONSTRAINT `UTk_User` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
