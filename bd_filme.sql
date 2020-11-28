-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Nov-2020 às 21:24
-- Versão do servidor: 10.4.16-MariaDB
-- versão do PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_filme`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ator`
--

CREATE TABLE `ator` (
  `CodAtor` int(5) NOT NULL,
  `NomeAtor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `ator`
--

INSERT INTO `ator` (`CodAtor`, `NomeAtor`) VALUES
(0, 'Vin Diesel'),
(1, 'Dwayne Johnson'),
(2, 'Jason Statham'),
(3, 'Tom Hanks'),
(4, 'Robin Wright'),
(5, 'Gary Sinise'),
(50, 'Robert Downey Jr.'),
(51, 'Stan Lee'),
(52, 'Jon Favreau'),
(53, 'Ben Affleck'),
(54, 'Gal Gadot'),
(55, 'Henry Cavill'),
(56, 'Jason Momoa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `elenco`
--

CREATE TABLE `elenco` (
  `CodFilme` int(5) NOT NULL,
  `CodAtor` int(5) NOT NULL,
  `Papel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `elenco`
--

INSERT INTO `elenco` (`CodFilme`, `CodAtor`, `Papel`) VALUES
(0, 0, 'Protagonista'),
(0, 1, 'Ator secundário'),
(0, 2, 'Ator secundário'),
(1, 3, 'Protagonista'),
(1, 4, 'Ator secundário'),
(1, 5, 'Ator secundário'),
(50, 50, 'Ator Principal'),
(50, 51, 'Part. Especial'),
(50, 52, 'Ator Secundário'),
(51, 53, 'Ator Principal'),
(51, 54, 'Ator Principal'),
(51, 55, 'Ator Principal'),
(51, 56, 'Ator Principal');

-- --------------------------------------------------------

--
-- Estrutura da tabela `estudio`
--

CREATE TABLE `estudio` (
  `CodEst` int(5) NOT NULL,
  `Nome` varchar(45) NOT NULL,
  `Logo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `estudio`
--

INSERT INTO `estudio` (`CodEst`, `Nome`, `Logo`) VALUES
(0, 'Universal Studios', 'https://img2.gratispng.com/20180403/gue/kisspng-universal-orlando-universal-studios-hollywood-univ-studio-5ac34b4ac6a306.9192322215227482348136.jpg'),
(1, 'Paramount Pictures', 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Paramount.png'),
(50, 'Marvel Studios', 'https://d13ezvd6yrslxm.cloudfront.net/wp/wp-content/images/marvel-intro-e1556625768997.jpg'),
(51, 'Warner Bros', 'http://sentaai.com/wp-content/uploads/2018/09/warner-bros-logo-800x445.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `filme`
--

CREATE TABLE `filme` (
  `CodFilme` int(5) NOT NULL,
  `NomeFilme` varchar(50) NOT NULL,
  `AnoProd` date NOT NULL,
  `CodEst` int(5) NOT NULL,
  `Diretor` varchar(50) NOT NULL,
  `Capa` varchar(500) NOT NULL,
  `Source` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `filme`
--

INSERT INTO `filme` (`CodFilme`, `NomeFilme`, `AnoProd`, `CodEst`, `Diretor`, `Capa`, `Source`) VALUES
(0, 'VELOZES & FURIOSOS 8', '2017-04-13', 0, 'F. Gary Gray', 'https://upload.wikimedia.org/wikipedia/pt/1/15/Velozes_e_Furiosos_8_p%C3%B4ster.jpg', 'KvSlvtPnZTo'),
(1, 'Forrest Gump', '1994-07-06', 1, 'Robert Zemeckis', 'https://upload.wikimedia.org/wikipedia/pt/c/c0/ForrestGumpPoster.jpg', 'p0p5CQUjTxI'),
(50, 'Homem De Ferro', '2008-01-01', 50, 'Jon Favreau', 'https://upload.wikimedia.org/wikipedia/pt/0/00/Iron_Man_poster.jpg', '8ugaeA-nMTc'),
(51, 'Liga da Justiça', '2017-06-01', 51, 'Zack Snyder', 'https://br.web.img2.acsta.net/pictures/17/10/23/19/55/0260439.jpg', '1ZRBL9PwG5E');

-- --------------------------------------------------------

--
-- Estrutura da tabela `filme_genero`
--

CREATE TABLE `filme_genero` (
  `CodFilme` int(5) NOT NULL,
  `Nome` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `filme_genero`
--

INSERT INTO `filme_genero` (`CodFilme`, `Nome`) VALUES
(0, 'Ação'),
(0, 'Suspense'),
(1, 'Comédia'),
(1, 'Drama'),
(50, 'Ação'),
(50, 'Herói'),
(51, 'Ação'),
(51, 'Crossover'),
(51, 'Herói');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `ator`
--
ALTER TABLE `ator`
  ADD PRIMARY KEY (`CodAtor`);

--
-- Índices para tabela `elenco`
--
ALTER TABLE `elenco`
  ADD PRIMARY KEY (`CodFilme`,`CodAtor`),
  ADD KEY `FK_ATOR` (`CodAtor`);

--
-- Índices para tabela `estudio`
--
ALTER TABLE `estudio`
  ADD PRIMARY KEY (`CodEst`);

--
-- Índices para tabela `filme`
--
ALTER TABLE `filme`
  ADD PRIMARY KEY (`CodFilme`),
  ADD KEY `FK_ESTUDIO` (`CodEst`);

--
-- Índices para tabela `filme_genero`
--
ALTER TABLE `filme_genero`
  ADD PRIMARY KEY (`CodFilme`,`Nome`);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `elenco`
--
ALTER TABLE `elenco`
  ADD CONSTRAINT `FK_ATOR` FOREIGN KEY (`CodAtor`) REFERENCES `ator` (`CodAtor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ELENCO_FILME` FOREIGN KEY (`CodFilme`) REFERENCES `filme` (`CodFilme`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `filme`
--
ALTER TABLE `filme`
  ADD CONSTRAINT `FK_ESTUDIO` FOREIGN KEY (`CodEst`) REFERENCES `estudio` (`CodEst`);

--
-- Limitadores para a tabela `filme_genero`
--
ALTER TABLE `filme_genero`
  ADD CONSTRAINT `FK_FILME` FOREIGN KEY (`CodFilme`) REFERENCES `filme` (`CodFilme`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
