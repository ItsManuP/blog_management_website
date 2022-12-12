-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Dic 10, 2022 alle 11:55
-- Versione del server: 10.4.22-MariaDB
-- Versione PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `basididati`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `argomento`
--

CREATE TABLE `argomento` (
  `nome` varchar(255) NOT NULL,
  `idargomento` int(10) NOT NULL,
  `id_blog_riferimento` int(20) NOT NULL,
  `id_post_riferimento` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `argomento`
--

INSERT INTO `argomento` (`nome`, `idargomento`, `id_blog_riferimento`, `id_post_riferimento`) VALUES
('Cinefili', 13, 257, NULL),
('Tolkien', 23, 257, 101),
('Musica', 24, 258, NULL),
('Battisti', 26, 258, 102),
('Pianoforte', 27, 258, 103),
('Moda', 64, 284, NULL),
('Storia', 65, 285, NULL),
('Libri', 66, 286, NULL),
('Recensione', 76, 286, 120);

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE `blog` (
  `titolo` varchar(255) NOT NULL,
  `idblog` int(20) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `coautore` int(20) DEFAULT NULL,
  `autoreblog` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`titolo`, `idblog`, `descrizione`, `coautore`, `autoreblog`) VALUES
('Film', 257, 'Blog dedicato ai cinefili', 300, 298),
('Musica', 258, 'Per chi ama fare le cose con una musica di sottofondo', NULL, 298),
('Moda', 284, 'Amanti della moda', 298, 300),
('Storia', 285, 'Amo la storia medievale', NULL, 300),
('Un libro al giorno', 286, 'Libri che vanno letti una volta nella vita', NULL, 300);

-- --------------------------------------------------------

--
-- Struttura della tabella `commento`
--

CREATE TABLE `commento` (
  `idcommento` smallint(6) NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `autorecommento` int(20) NOT NULL,
  `codice_post` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `commento`
--

INSERT INTO `commento` (`idcommento`, `descrizione`, `data`, `autorecommento`, `codice_post`) VALUES
(608, 'Tolkien è un alieno', '2022-12-01 13:01:07', 298, 101);

-- --------------------------------------------------------

--
-- Struttura della tabella `grafica`
--

CREATE TABLE `grafica` (
  `idimgbackground` int(20) NOT NULL,
  `id_img_riferimento_blog` int(20) DEFAULT NULL,
  `id_img_riferimento_post` int(20) DEFAULT NULL,
  `pathimmagine` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `grafica`
--

INSERT INTO `grafica` (`idimgbackground`, `id_img_riferimento_blog`, `id_img_riferimento_post`, `pathimmagine`) VALUES
(197, 257, NULL, '../img/upload/gandalf.jpg'),
(201, NULL, 101, '../img/postupload/Gandalf.jpg'),
(202, 258, NULL, '../img/upload/console.jpg'),
(204, NULL, 102, '../img/postupload/defaultimgpost.jpg'),
(205, NULL, 103, '../img/postupload/pianoforte.jpg'),
(241, 284, NULL, '../img/upload/moda.jpg'),
(242, 285, NULL, '../img/upload/cesare.jpg'),
(243, 286, NULL, '../img/upload/libro.jpg'),
(253, NULL, 120, '../img/postupload/la_sottile_arte.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `post`
--

CREATE TABLE `post` (
  `titolo` varchar(255) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `testo` mediumtext NOT NULL,
  `idpost` int(20) NOT NULL,
  `autorepost` int(20) NOT NULL,
  `codiceblog` int(20) NOT NULL,
  `numero_likes` int(10) NOT NULL DEFAULT 0,
  `argomento_post` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `post`
--

INSERT INTO `post` (`titolo`, `data`, `testo`, `idpost`, `autorepost`, `codiceblog`, `numero_likes`, `argomento_post`) VALUES
('La compagnia dell\'Anello', '2022-12-01 12:58:54', 'Acclamato dalla critica e dal pubblico, ottenne vari riconoscimenti tra cui 4 premi Oscar su 13 candidature e 4 British Academy Film Awards. Nel 2007, l\'AFI lo inserì al cinquantesimo posto nella classifica dei cento migliori film statunitensi di tutti i tempi e al secondo posto nella classifica dei dieci migliori film fantasy di tutti i tempi nell\'AFI\'s 10 Top 10 della American Film Institute. Il film occupa il 24º posto nella lista del 2008 dell\'Empire dei 500 migliori film di tutti i tempi. Nel 2021 è stato scelto per la conservazione nel National Film Registry della Biblioteca del Congresso degli Stati Uniti.', 101, 298, 257, 2, 23),
('Umanamente uomo: il sogno di Lucio Battisti, il primo dei due album che nel 1972 cambiarono la musica pop italiana', '2022-12-01 13:18:13', 'A volte, quando si ascolta un nuovo cantautore, capita di pensare «Ehi, questo qui è bravo, ricorda Battisti». Io, ad esempio, l’ho pensato quando ho sentito per la prima volta Fulminacci. Questo perché Battisti è un punto di riferimento, un termine di paragone, come solo i grandi autori possono essere.  Il suo modo di fare musica produsse una piccola rivoluzione nel pop nostrano, perché seppe amalgamare la classica melodia italiana con le sonorità d’oltreoceano (dal rhythm & blues al progressive rock, fino alla disco music).  Molti importanti cantautori italiani hanno dichiarato di essersi ispirati a Lucio Battisti, come Eros Ramazzotti, Zucchero Fornaciari, Claudio Baglioni, Ligabue, Gianluca Grignani, Tiziano Ferro, mentre Francesco De Gregori ha eseguito spesso sue canzoni nei concerti e nella coda orchestrale di La leva calcistica della classe ’68 c’è una citazione di Vento nel vento, brano tratto da Il mio canto libero.  Ma Battisti era ed è stimato anche da molti artisti stranieri.', 102, 298, 258, 1, 26),
('Una breve storia sul pianoforte afroamericano', '2022-12-01 13:24:06', 'Quando la produzione in serie di pianoforti assemblati diventa economicamente conveniente, il piano entra nelle possibilità anche degli artisti itineranti: prima tappa, nei saloon del Midwest, a rallegrare le serate dei boscaioli e dei manovali delle ferrate. Lo scopo è sostituire con efficacia gli strumenti tipici della folk song da ballo bianca, fiddle e banjo: la mano sinistra si incarica del ritmo, come il banjo, mentre la destra crea melodie, temi e improvvisazioni, sostituendosi al fiddle. É l’atto fondativo del Fast Western Piano, lo stile da cui deriva gran parte del lessico del pianoforte afroamericano: una maniera che si nutre di una frizione, di una tensione permanente fra lo strato più liquido della mano destra, e quello più solido della sinistra.', 103, 298, 258, 0, 27),
('La sottile arte di fare quello che c***o ti pare', '2022-12-09 15:51:51', 'Per decenni ci hanno ripetuto che il pensiero positivo è la chiave per avere una vita intensa e felice. «Fan***o la positività», afferma Mark Manson. «Cerchiamo di essere onesti, ogni tanto le cose non vanno come avremmo voluto, ma dobbiamo imparare ad accettarlo». L’autore, blogger seguitissimo, dice le cose come stanno: una dose di cruda, rinfrescante, pura verità. Il concetto sostenuto nel libro, avvalorato da studi accademici e arricchito da aneddoti di vita reali, è che migliorare la nostra vita non dipende dalla nostra capacità di affrontare con falsa positività le difficoltà che incontriamo, ma dall’imparare a riconoscerle. Una volta che abbracciamo le nostre paure, i difetti, le incertezze, possiamo cominciare a trovare il coraggio, la responsabilità, la curiosità, e il perdono che cerchiamo. La sottile arte di fare quello che c***o ti pare è uno schiaffo in faccia a chi non vede l’ora di risvegliarsi da un triste torpore e vivere secondo le proprie aspirazioni.', 120, 300, 286, 0, 76);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(20) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `documento` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `telefono`, `username`, `password`, `email`, `documento`) VALUES
(7, '3498283012', 'Pippo Baudo', '$2y$10$1f2DlWDSwc4FDAYAIt', 'pippobaudo@gmail.com', 'TP1402029'),
(19, '3213213213', 'GianFilippo', '$2y$10$LbnapJSgjtOG87oXF35pQebrZYTZQ4cMgoIDa/Uxx5OZzfu2WJ75i', 'provanumeroduetest@gmail.com', 'AD2424673'),
(298, '3482998002', 'Filippo Esposito', '$2y$10$jZgF4gpvPDfaP6PuyV9t1O4NQcwxQZFzaHk0ctLUV9DVMtWqLWOpe', 'filippoesposito@gmail.com', 'AD3344444'),
(300, '3213231232', 'testtest', '$2y$10$xlCG.4iRWj2BtnqBzjzNzuwjkJw2cVsmspG1Odp4R2Gxsl2oeZtnG', 'testtest@gmail.com', 'AD3333333');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti_like`
--

CREATE TABLE `utenti_like` (
  `id` int(10) NOT NULL,
  `id_utente` int(20) NOT NULL,
  `id_post` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti_like`
--

INSERT INTO `utenti_like` (`id`, `id_utente`, `id_post`) VALUES
(79, 298, 101);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `argomento`
--
ALTER TABLE `argomento`
  ADD PRIMARY KEY (`idargomento`),
  ADD KEY `id_blog_riferimento` (`id_blog_riferimento`),
  ADD KEY `argomento_ibfk_2` (`id_post_riferimento`);

--
-- Indici per le tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`idblog`),
  ADD KEY `blog_ibfk_1` (`autoreblog`),
  ADD KEY `blog_ibfk_2` (`coautore`);

--
-- Indici per le tabelle `commento`
--
ALTER TABLE `commento`
  ADD PRIMARY KEY (`idcommento`),
  ADD KEY `commento_ibfk_1` (`autorecommento`),
  ADD KEY `commento_ibfk_2` (`codice_post`);

--
-- Indici per le tabelle `grafica`
--
ALTER TABLE `grafica`
  ADD PRIMARY KEY (`idimgbackground`),
  ADD KEY `id_img_riferimento_blog` (`id_img_riferimento_blog`),
  ADD KEY `id_img_riferimento_post` (`id_img_riferimento_post`);

--
-- Indici per le tabelle `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`idpost`),
  ADD KEY `post_ibfk_2` (`codiceblog`),
  ADD KEY `post_ibfk_3` (`argomento_post`),
  ADD KEY `post_ibfk_1` (`autorepost`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti_like`
--
ALTER TABLE `utenti_like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utenti_like_ibfk_1` (`id_post`),
  ADD KEY `utenti_like_ibfk_2` (`id_utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `argomento`
--
ALTER TABLE `argomento`
  MODIFY `idargomento` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT per la tabella `blog`
--
ALTER TABLE `blog`
  MODIFY `idblog` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=293;

--
-- AUTO_INCREMENT per la tabella `commento`
--
ALTER TABLE `commento`
  MODIFY `idcommento` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=615;

--
-- AUTO_INCREMENT per la tabella `grafica`
--
ALTER TABLE `grafica`
  MODIFY `idimgbackground` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT per la tabella `post`
--
ALTER TABLE `post`
  MODIFY `idpost` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;

--
-- AUTO_INCREMENT per la tabella `utenti_like`
--
ALTER TABLE `utenti_like`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `argomento`
--
ALTER TABLE `argomento`
  ADD CONSTRAINT `argomento_ibfk_1` FOREIGN KEY (`id_blog_riferimento`) REFERENCES `blog` (`idblog`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `argomento_ibfk_2` FOREIGN KEY (`id_post_riferimento`) REFERENCES `post` (`idpost`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limiti per la tabella `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`autoreblog`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `blog_ibfk_2` FOREIGN KEY (`coautore`) REFERENCES `utenti` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Limiti per la tabella `commento`
--
ALTER TABLE `commento`
  ADD CONSTRAINT `commento_ibfk_1` FOREIGN KEY (`autorecommento`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `commento_ibfk_2` FOREIGN KEY (`codice_post`) REFERENCES `post` (`idpost`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `grafica`
--
ALTER TABLE `grafica`
  ADD CONSTRAINT `grafica_ibfk_1` FOREIGN KEY (`id_img_riferimento_blog`) REFERENCES `blog` (`idblog`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grafica_ibfk_2` FOREIGN KEY (`id_img_riferimento_post`) REFERENCES `post` (`idpost`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`autorepost`) REFERENCES `utenti` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`codiceblog`) REFERENCES `blog` (`idblog`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_3` FOREIGN KEY (`argomento_post`) REFERENCES `argomento` (`idargomento`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- Limiti per la tabella `utenti_like`
--
ALTER TABLE `utenti_like`
  ADD CONSTRAINT `utenti_like_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`idpost`) ON DELETE CASCADE,
  ADD CONSTRAINT `utenti_like_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utenti` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
