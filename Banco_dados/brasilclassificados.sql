-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2018 at 02:56 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `brasilclassificados`
--

-- --------------------------------------------------------

--
-- Table structure for table `categoria`
--

CREATE TABLE `categoria` (
  `cod` int(6) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `link` varchar(100) NOT NULL,
  `categoria_cod` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `classificado`
--

CREATE TABLE `classificado` (
  `cod` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `tipo` varchar(1) NOT NULL COMMENT 'Venda, troca, doação ou outro',
  `valor` decimal(10,2) NOT NULL,
  `status` int(1) NOT NULL,
  `perfil` int(1) NOT NULL,
  `usuario_cod` int(6) NOT NULL,
  `categoria_cod` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comentario`
--

CREATE TABLE `comentario` (
  `cod` int(11) NOT NULL,
  `mensagem` varchar(500) NOT NULL,
  `data` datetime NOT NULL,
  `status` int(1) NOT NULL,
  `classificado_cod` int(11) NOT NULL,
  `usuario_cod` int(6) NOT NULL,
  `comentario_cod` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contato`
--

CREATE TABLE `contato` (
  `cod` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `assunto` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `data` datetime NOT NULL,
  `ip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `endereco`
--

CREATE TABLE `endereco` (
  `cod` int(11) NOT NULL,
  `rua` varchar(100) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `bairro` varchar(50) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `complemento` varchar(20) NOT NULL,
  `cep` varchar(20) NOT NULL,
  `usuario_cod` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `imagens`
--

CREATE TABLE `imagens` (
  `cod` int(11) NOT NULL,
  `imagem` varchar(100) NOT NULL,
  `classificado_cod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `telefone`
--

CREATE TABLE `telefone` (
  `cod` int(11) NOT NULL,
  `tipo` int(1) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `usuario_cod` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `cod` int(6) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cpf` varchar(20) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `nascimento` date NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `status` int(1) NOT NULL,
  `permissao` int(1) NOT NULL,
  `ip` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`cod`, `nome`, `email`, `cpf`, `usuario`, `senha`, `nascimento`, `sexo`, `status`, `permissao`, `ip`) VALUES
(0, 'Gunnar Correa', 'gunnercorrea@gmail.com', '309.073.040-52', 'gunnarcorrea', '25f9e794323b453885f5181f1b624d0b', '1992-08-21', 'm', 1, 1, '::1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_categoria_categoria1_idx` (`categoria_cod`);

--
-- Indexes for table `classificado`
--
ALTER TABLE `classificado`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_classificado_usuario1_idx` (`usuario_cod`),
  ADD KEY `fk_classificado_categoria1_idx` (`categoria_cod`);

--
-- Indexes for table `comentario`
--
ALTER TABLE `comentario`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_comentario_classificado1_idx` (`classificado_cod`),
  ADD KEY `fk_comentario_usuario1_idx` (`usuario_cod`),
  ADD KEY `fk_comentario_comentario1_idx` (`comentario_cod`);

--
-- Indexes for table `contato`
--
ALTER TABLE `contato`
  ADD PRIMARY KEY (`cod`);

--
-- Indexes for table `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_endereco_usuario_idx` (`usuario_cod`);

--
-- Indexes for table `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_imagens_classificado1_idx` (`classificado_cod`);

--
-- Indexes for table `telefone`
--
ALTER TABLE `telefone`
  ADD PRIMARY KEY (`cod`),
  ADD KEY `fk_telefone_usuario1_idx` (`usuario_cod`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cod`),
  ADD UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `usuario_UNIQUE` (`usuario`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `fk_categoria_categoria1` FOREIGN KEY (`categoria_cod`) REFERENCES `categoria` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `classificado`
--
ALTER TABLE `classificado`
  ADD CONSTRAINT `fk_classificado_categoria1` FOREIGN KEY (`categoria_cod`) REFERENCES `categoria` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_classificado_usuario1` FOREIGN KEY (`usuario_cod`) REFERENCES `usuario` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `comentario`
--
ALTER TABLE `comentario`
  ADD CONSTRAINT `fk_comentario_classificado1` FOREIGN KEY (`classificado_cod`) REFERENCES `classificado` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comentario_comentario1` FOREIGN KEY (`comentario_cod`) REFERENCES `comentario` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comentario_usuario1` FOREIGN KEY (`usuario_cod`) REFERENCES `usuario` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `fk_endereco_usuario` FOREIGN KEY (`usuario_cod`) REFERENCES `usuario` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `fk_imagens_classificado1` FOREIGN KEY (`classificado_cod`) REFERENCES `classificado` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `telefone`
--
ALTER TABLE `telefone`
  ADD CONSTRAINT `fk_telefone_usuario1` FOREIGN KEY (`usuario_cod`) REFERENCES `usuario` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
