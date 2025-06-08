-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 08, 2025 at 10:16 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sklep_kasyno`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `adresy_uzytkownikow`
--

CREATE TABLE `adresy_uzytkownikow` (
  `id` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `miasto` varchar(50) NOT NULL,
  `ulica` varchar(50) NOT NULL,
  `kod_pocztowy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `imie_nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `temat` varchar(255) NOT NULL,
  `wiadomosc` text NOT NULL,
  `data_stworzenia` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `imie_nazwisko`, `email`, `temat`, `wiadomosc`, `data_stworzenia`) VALUES
(1, 'dsda', 'Lodek145@gmail.com', 'sdadsad', 'dsad', '2025-06-08 19:54:55'),
(2, 'sdada', 'dsaddad@gmail.com', 'sadasdasdad', 'sdadsaddsdadsda', '2025-06-08 21:07:27'),
(3, 'dsadsad', 'dasdsdad@gmail.com', 'dsadsad', 'dsadadsad', '2025-06-08 21:07:32');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `karuzela`
--

CREATE TABLE `karuzela` (
  `id` int(11) NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `caption_title` varchar(255) NOT NULL,
  `caption_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kategorie`
--

CREATE TABLE `kategorie` (
  `id` int(11) NOT NULL,
  `kategoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategorie`
--

INSERT INTO `kategorie` (`id`, `kategoria`) VALUES
(1, 'Karty'),
(2, 'Tokeny'),
(4, 'Poker'),
(5, 'Blackjack');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kolory`
--

CREATE TABLE `kolory` (
  `id` int(11) NOT NULL,
  `kolor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kolory`
--

INSERT INTO `kolory` (`id`, `kolor`) VALUES
(2, 'biały'),
(1, 'czarny'),
(3, 'czerwony'),
(4, 'zielony'),
(5, 'żółty');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty`
--

CREATE TABLE `produkty` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `opis` text NOT NULL,
  `kolor_id` int(11) DEFAULT NULL,
  `id_kategorii` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `cena` float NOT NULL,
  `img_path` varchar(255) NOT NULL,
  `znizka` int(11) NOT NULL,
  `ocena` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkty`
--

INSERT INTO `produkty` (`id`, `nazwa`, `opis`, `kolor_id`, `id_kategorii`, `ilosc`, `cena`, `img_path`, `znizka`, `ocena`) VALUES
(7, 'dsads', 'dasda', 2, 1, 1, 22, 'prod_6840ac6c70b702.97781906.png', 22, 0.0),
(8, 'xzxzxz', 'zxzxzx', 3, 2, 1, 11, 'prod_6840afab927f57.35987851.webp', 11, 0.0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `recenzje`
--

CREATE TABLE `recenzje` (
  `id` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `ocena` int(11) NOT NULL,
  `tresc` text NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uprawnienia`
--

CREATE TABLE `uprawnienia` (
  `id` int(11) NOT NULL,
  `uprawnienia` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uprawnienia`
--

INSERT INTO `uprawnienia` (`id`, `uprawnienia`) VALUES
(1, 'Użytkownik'),
(2, 'Administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(64) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownik`
--

CREATE TABLE `uzytkownik` (
  `id` int(11) NOT NULL,
  `imie_nazwisko` varchar(50) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(64) DEFAULT NULL,
  `uprawnienia_id` int(11) NOT NULL,
  `profil_img` varchar(255) DEFAULT NULL,
  `cover_img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzytkownik`
--

INSERT INTO `uzytkownik` (`id`, `imie_nazwisko`, `haslo`, `email`, `telefon`, `uprawnienia_id`, `profil_img`, `cover_img`) VALUES
(15, 'Skibidi sigma', '$2y$10$du6mUcp8EJt7QrpbQbA7VuLqJlI5n6FcbECwvsRoPKt0NxJhj0DHq', 'johnpaul2@gmail.com', NULL, 2, 'zdjecia/profiles/profile_15_1749063221.webp', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `id_adresu` int(11) NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `opis` varchar(500) NOT NULL,
  `data_zoenia` datetime NOT NULL,
  `koszt` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienie_produkty`
--

CREATE TABLE `zamowienie_produkty` (
  `id` int(11) NOT NULL,
  `id_zamowienia` int(11) NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL DEFAULT 1,
  `cena` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `adresy_uzytkownikow`
--
ALTER TABLE `adresy_uzytkownikow`
  ADD PRIMARY KEY (`id`),
  ADD KEY `adresy_uzytkownikow_fk1` (`id_uzytkownika`);

--
-- Indeksy dla tabeli `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `karuzela`
--
ALTER TABLE `karuzela`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `kategorie`
--
ALTER TABLE `kategorie`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `kolory`
--
ALTER TABLE `kolory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nazwa` (`kolor`);

--
-- Indeksy dla tabeli `produkty`
--
ALTER TABLE `produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produkty_fk2` (`id_kategorii`),
  ADD KEY `kolor` (`kolor_id`);

--
-- Indeksy dla tabeli `recenzje`
--
ALTER TABLE `recenzje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`),
  ADD KEY `id_produktu` (`id_produktu`);

--
-- Indeksy dla tabeli `uprawnienia`
--
ALTER TABLE `uprawnienia`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksy dla tabeli `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `uzytkownik_fk4` (`uprawnienia_id`),
  ADD KEY `email` (`email`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zamowienia_fk1` (`id_uzytkownika`),
  ADD KEY `zamowienia_fk2` (`id_adresu`);

--
-- Indeksy dla tabeli `zamowienie_produkty`
--
ALTER TABLE `zamowienie_produkty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_zamowienia` (`id_zamowienia`),
  ADD KEY `id_produktu` (`id_produktu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adresy_uzytkownikow`
--
ALTER TABLE `adresy_uzytkownikow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `karuzela`
--
ALTER TABLE `karuzela`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kolory`
--
ALTER TABLE `kolory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recenzje`
--
ALTER TABLE `recenzje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `uprawnienia`
--
ALTER TABLE `uprawnienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `uzytkownik`
--
ALTER TABLE `uzytkownik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `zamowienie_produkty`
--
ALTER TABLE `zamowienie_produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adresy_uzytkownikow`
--
ALTER TABLE `adresy_uzytkownikow`
  ADD CONSTRAINT `adresy_uzytkownikow_fk1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `produkty`
--
ALTER TABLE `produkty`
  ADD CONSTRAINT `produkty_fk2` FOREIGN KEY (`id_kategorii`) REFERENCES `kategorie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `produkty_ibfk_1` FOREIGN KEY (`kolor_id`) REFERENCES `kolory` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `recenzje`
--
ALTER TABLE `recenzje`
  ADD CONSTRAINT `recenzje_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recenzje_ibfk_2` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD CONSTRAINT `user_activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD CONSTRAINT `uzytkownik_fk4` FOREIGN KEY (`uprawnienia_id`) REFERENCES `uprawnienia` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD CONSTRAINT `zamowienia_fk1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zamowienia_fk2` FOREIGN KEY (`id_adresu`) REFERENCES `adresy_uzytkownikow` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zamowienia_fk3` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zamowienie_produkty`
--
ALTER TABLE `zamowienie_produkty`
  ADD CONSTRAINT `zamowienie_produkty_ibfk_1` FOREIGN KEY (`id_zamowienia`) REFERENCES `zamowienia` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `zamowienie_produkty_ibfk_2` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
