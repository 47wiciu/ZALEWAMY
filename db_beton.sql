-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Maj 26, 2025 at 01:42 AM
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
-- Database: `db_beton`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `beton`
--

CREATE TABLE `beton` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(50) NOT NULL,
  `cena` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beton`
--

INSERT INTO `beton` (`id`, `nazwa`, `cena`) VALUES
(1, 'lekki', 350.00),
(2, 'ciezki', 800.00),
(3, 'zwykly', 500.00),
(4, 'wysokowytrzymalosciowy', 900.00),
(5, 'architektoniczny', 700.00),
(6, 'samozageszczalny', 600.00),
(7, 'wodoodporny', 750.00),
(8, 'drogowy', 480.00),
(9, 'posadzkowy', 650.00),
(10, 'komorkowy', 590.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienia`
--

CREATE TABLE `zamowienia` (
  `id` int(11) NOT NULL,
  `imie` varchar(70) NOT NULL,
  `nazwisko` varchar(70) NOT NULL,
  `miejscowosc` varchar(70) NOT NULL,
  `adres` varchar(70) NOT NULL,
  `telefon` int(9) NOT NULL,
  `mail` varchar(70) NOT NULL,
  `ilość betonu - lekki` varchar(45) DEFAULT NULL,
  `ilość betonu - ciezki` varchar(45) DEFAULT NULL,
  `ilość betonu - zwykly` varchar(45) DEFAULT NULL,
  `ilość betonu - wysokowytrzymalosciowy` varchar(45) DEFAULT NULL,
  `ilość betonu - architektoniczny` varchar(45) DEFAULT NULL,
  `ilość betonu - samozageszczalny` varchar(45) DEFAULT NULL,
  `ilość betonu - wodoodporny` varchar(45) DEFAULT NULL,
  `ilość betonu - drogowy` varchar(45) DEFAULT NULL,
  `ilość betonu - posadzkowy` varchar(45) DEFAULT NULL,
  `ilość betonu - komorkowy` varchar(45) DEFAULT NULL,
  `cena_calkowita` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zamowienia`
--

INSERT INTO `zamowienia` (`id`, `imie`, `nazwisko`, `miejscowosc`, `adres`, `telefon`, `mail`, `ilość betonu - lekki`, `ilość betonu - ciezki`, `ilość betonu - zwykly`, `ilość betonu - wysokowytrzymalosciowy`, `ilość betonu - architektoniczny`, `ilość betonu - samozageszczalny`, `ilość betonu - wodoodporny`, `ilość betonu - drogowy`, `ilość betonu - posadzkowy`, `ilość betonu - komorkowy`, `cena_calkowita`) VALUES
(1, '', '', '', '', 0, '', '0', '4', '0', '0', '0', '0', '0', '0', '0', '0', 3200.00),
(2, 'Wiktor', 'Wójcik', 'Ożarów', 'Osiedle Wzgórze 34/1', 535791207, 'narutokox8@gmail.com', '20', '0', '12', '3', '0', '0', '0', '0', '0', '22', 28680.00);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `beton`
--
ALTER TABLE `beton`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zamowienia`
--
ALTER TABLE `zamowienia`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beton`
--
ALTER TABLE `beton`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `zamowienia`
--
ALTER TABLE `zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
