-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 27-Abr-2022 às 03:11
-- Versão do servidor: 10.4.8-MariaDB
-- versão do PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dados_clientes`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `dados`
--

CREATE TABLE `dados` (
  `id` int(11) NOT NULL,
  `data_agendamento` varchar(50) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `cidade` varchar(150) NOT NULL,
  `canal_origem` varchar(50) NOT NULL,
  `tipo_contato` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `motivo` text DEFAULT NULL,
  `area` varchar(100) NOT NULL,
  `fk_usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `dados`
--

INSERT INTO `dados` (`id`, `data_agendamento`, `nome`, `phone`, `estado`, `cidade`, `canal_origem`, `tipo_contato`, `status`, `motivo`, `area`, `fk_usuario`) VALUES
(1, '2022-15-04', 'João Maria', '92981152683', 'Amapá', 'Serra do Navio', '-- Selecione --', 'Tipo contato 1', 'Sim', '--Selecione --', 'Demartologia estética', 'João'),
(2, '2022-15-04', 'jOANA', '92981152683', 'Roraima', 'Amajari', '-- Selecione --', 'Tipo contato 2', 'Sim', '--Selecione --', 'Demartologia clínica', 'Joana'),
(3, '2022-15-03', 'OUtro teste', '92981152683', 'Tocantins', 'Palmeirópolis', '-- Selecione --', 'Tipo contato 1', 'Sim', '--Selecione --', 'Demartologia estética', 'João'),
(4, '2022-15-03', 'OUtro teste', '92981152683', 'Tocantins', 'Palmeirópolis', 'Facebook', 'Tipo contato 1', 'Não', NULL, 'Demartologia clínica', 'João');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario`, `senha`) VALUES
('Joana', 'senha joana'),
('João', 'senha');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `dados`
--
ALTER TABLE `dados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`fk_usuario`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario`),
  ADD KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dados`
--
ALTER TABLE `dados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `dados`
--
ALTER TABLE `dados`
  ADD CONSTRAINT `dados_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
