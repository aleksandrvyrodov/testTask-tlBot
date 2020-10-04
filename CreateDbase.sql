-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Окт 05 2020 г., 00:48
-- Версия сервера: 5.7.24-27
-- Версия PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `u1165283_hystory`
--
CREATE DATABASE IF NOT EXISTS `u1165283_hystory` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `u1165283_hystory`;

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE `message` (
  `id_messege` tinyint(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mode`
--

CREATE TABLE `mode` (
  `id_mode` tinyint(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mode`
--

INSERT INTO `mode` (`id_mode`, `name`) VALUES
(2, 'A'),
(4, 'A-1'),
(6, 'A-10'),
(3, 'B'),
(5, 'B-1'),
(7, 'B-10'),
(1, 'Z');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `count` tinyint(4) NOT NULL DEFAULT '10',
  `id_mode` tinyint(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_messege`,`id_user`) USING BTREE,
  ADD KEY `id_user` (`id_user`);

--
-- Индексы таблицы `mode`
--
ALTER TABLE `mode`
  ADD PRIMARY KEY (`id_mode`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_mode` (`id_mode`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `mode`
--
ALTER TABLE `mode`
  MODIFY `id_mode` tinyint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1355577295;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_mode`) REFERENCES `mode` (`id_mode`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
