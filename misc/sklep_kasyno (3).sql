-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 10, 2025 at 11:47 AM
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

--
-- Dumping data for table `adresy_uzytkownikow`
--

INSERT INTO `adresy_uzytkownikow` (`id`, `id_uzytkownika`, `miasto`, `ulica`, `kod_pocztowy`) VALUES
(10, 18, 'Racibórz', 'Zamkowa 7', '47-400');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dziennik`
--

CREATE TABLE `dziennik` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `akcja` varchar(64) NOT NULL,
  `detale` text DEFAULT NULL,
  `data_stworzenia` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dziennik`
--

INSERT INTO `dziennik` (`id`, `user_id`, `akcja`, `detale`, `data_stworzenia`) VALUES
(17, 18, 'Zmiana telefonu', 'Nowy numer: 123123123', '2025-06-10 11:03:20'),
(18, 18, 'Zmiana emaila', 'Nowy email: johnpork@skibidi.p', '2025-06-10 11:03:25'),
(19, 18, 'Zmiana hasła', 'Hasło zostało zmienione', '2025-06-10 11:04:19');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `karuzela`
--

CREATE TABLE `karuzela` (
  `id` int(11) NOT NULL,
  `img_sciezka` varchar(255) NOT NULL,
  `img_tytul` varchar(255) NOT NULL,
  `img_tekst` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karuzela`
--

INSERT INTO `karuzela` (`id`, `img_sciezka`, `img_tytul`, `img_tekst`) VALUES
(13, 'carousel__1749544862.png', 'BlackusJackus', 'Sprawdź nasze nowe stoły do Blackjacka'),
(14, 'carousel__1749544870.png', 'Spin to Gazilions', 'Świeża dostawa stołów do ruletki'),
(15, 'carousel__1749544878.png', 'Metalowe Tokeny', 'Ciężkie o pięknej barwie metalowe tokeny');

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
(6, 'niebieski'),
(4, 'zielony'),
(5, 'żółty');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyk`
--

CREATE TABLE `koszyk` (
  `id` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `id_produktu` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL DEFAULT 1,
  `data_dodania` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(13, 'Eames Playing Cards', 'The Little Toy Edition 4', 2, 1, 1, 200, 'prod_68477305382554.64922325.webp', 0, 0.0),
(14, 'Eames Playing Cards', 'Starburst Edition 1', 6, 1, 1, 400, 'prod_6847725d175a69.54208306.webp', 15, 0.0),
(17, 'Eames Playing Cards', 'Hang-It-All Edition 2', 5, 1, 1, 500, 'prod_684774b9059718.65763782.webp', 0, 0.0),
(19, 'Obsession', 'Grace to Best', 1, 1, 1, 1200, 'prod_6847752f5944d9.60562939.webp', 5, 0.0),
(20, 'Two-Tone', 'Płótno do stołu Pokera', 4, 4, 1, 200, 'prod_684775b42d8c63.16271705.jpg', 15, 0.0),
(21, 'Two-Tone', 'Płótno do Pokera', 3, 4, 1, 400, 'prod_684775d23854b7.67226191.webp', 10, 0.0),
(22, 'BJ-BK PRO', 'Warstwa do stołu Blackjacka', 1, 5, 1, 600, 'prod_68477619af5db6.00185080.webp', 0, 0.0),
(23, 'BJ-BK PRO', 'Warstwa do stołu Blackjacka', 6, 5, 1, 1000, 'prod_684776693c5b37.51849971.webp', 18, 0.0),
(24, 'Casino Royale', 'Tokeny 14g', 2, 2, 1, 400, 'prod_684777259af805.67838819.webp', 12, 0.0),
(25, 'Casino Royale', 'Tokeny 20g', 6, 2, 1, 600, 'prod_68477755817504.99085066.webp', 20, 0.0),
(27, 'Imperial', 'Tokeny 60g', 5, 2, 1, 2000, 'prod_684777f3113a78.47370417.webp', 0, 5.0);

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

--
-- Dumping data for table `recenzje`
--

INSERT INTO `recenzje` (`id`, `id_uzytkownika`, `id_produktu`, `ocena`, `tresc`, `data`) VALUES
(10, 18, 27, 5, 'bardzo fajne', '2025-06-10 11:04:58');

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
(18, 'John Pork', '$2y$10$JPBQ2JXv8qHb/I1Eh7QGpeRx8fggXcCVhhVwNdXRL/xHN2zKrtKRa', 'johnpork@skibidi.p', '123123123', 1, NULL, ''),
(19, 'Skibidi Admin', '$2y$10$oJybqoFDOHEARGvWPiG6Hu3jtRLRKdRFfEx8TxJD/mreqlTo5c/o6', 'skibidi@gmail.com', NULL, 2, NULL, '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wiadomosci`
--

CREATE TABLE `wiadomosci` (
  `id` int(11) NOT NULL,
  `imie_nazwisko` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `temat` varchar(255) NOT NULL,
  `wiadomosc` text NOT NULL,
  `data_stworzenia` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id`, `id_uzytkownika`, `id_adresu`, `id_produktu`, `nazwa`, `opis`, `data_zoenia`, `koszt`) VALUES
(12, 18, 10, 27, 'Imperial', 'Tokeny 60g', '2025-06-10 11:07:15', 6000);

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
-- Dumping data for table `zamowienie_produkty`
--

INSERT INTO `zamowienie_produkty` (`id`, `id_zamowienia`, `id_produktu`, `ilosc`, `cena`) VALUES
(12, 12, 27, 3, 2000.00);

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
-- Indeksy dla tabeli `dziennik`
--
ALTER TABLE `dziennik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indeksy dla tabeli `koszyk`
--
ALTER TABLE `koszyk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`),
  ADD KEY `id_produktu` (`id_produktu`);

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
-- Indeksy dla tabeli `uzytkownik`
--
ALTER TABLE `uzytkownik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `uzytkownik_fk4` (`uprawnienia_id`),
  ADD KEY `email` (`email`);

--
-- Indeksy dla tabeli `wiadomosci`
--
ALTER TABLE `wiadomosci`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zamowienia_fk1` (`id_uzytkownika`),
  ADD KEY `zamowienia_fk2` (`id_adresu`),
  ADD KEY `zamowienia_fk3` (`id_produktu`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dziennik`
--
ALTER TABLE `dziennik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `karuzela`
--
ALTER TABLE `karuzela`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kategorie`
--
ALTER TABLE `kategorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kolory`
--
ALTER TABLE `kolory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `koszyk`
--
ALTER TABLE `koszyk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `produkty`
--
ALTER TABLE `produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `recenzje`
--
ALTER TABLE `recenzje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `uprawnienia`
--
ALTER TABLE `uprawnienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `uzytkownik`
--
ALTER TABLE `uzytkownik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `wiadomosci`
--
ALTER TABLE `wiadomosci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `zamowienie_produkty`
--
ALTER TABLE `zamowienie_produkty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adresy_uzytkownikow`
--
ALTER TABLE `adresy_uzytkownikow`
  ADD CONSTRAINT `adresy_uzytkownikow_fk1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dziennik`
--
ALTER TABLE `dziennik`
  ADD CONSTRAINT `dziennik_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `koszyk`
--
ALTER TABLE `koszyk`
  ADD CONSTRAINT `koszyk_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `uzytkownik` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `koszyk_ibfk_2` FOREIGN KEY (`id_produktu`) REFERENCES `produkty` (`id`) ON DELETE CASCADE;

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
