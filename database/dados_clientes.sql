-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 28-Abr-2022 às 11:53
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
  `telefone` varchar(50) DEFAULT NULL,
  `estado` varchar(100) NOT NULL,
  `cidade` varchar(150) NOT NULL,
  `canal_origem` varchar(50) NOT NULL,
  `tipo_contato` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `area` varchar(100) NOT NULL,
  `fk_usuario` varchar(50) NOT NULL,
  `motivo_agendamento` text NOT NULL,
  `motivo_cancelamento` text NOT NULL,
  `motivo_comparecimento` text NOT NULL,
  `outros_agendamento` text NOT NULL,
  `outros_cancelamento` text NOT NULL,
  `outros_comparecimento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `dados`
--

INSERT INTO `dados` (`id`, `data_agendamento`, `nome`, `telefone`, `estado`, `cidade`, `canal_origem`, `tipo_contato`, `status`, `area`, `fk_usuario`, `motivo_agendamento`, `motivo_cancelamento`, `motivo_comparecimento`, `outros_agendamento`, `outros_cancelamento`, `outros_comparecimento`) VALUES
(1, '2022/15/04', 'João Maria', '92981152683', 'Amapá', 'Serra do Navio', '-- Selecione --', 'Tipo contato 1', 'Não compareceu', 'Dermartologia estética', 'João', '', '', '', '', '', ''),
(2, '2022/15/04', 'jOANA', '92981152683', 'Roraima', 'Amajari', '-- Selecione --', 'Tipo contato 2', 'Sim', 'Dermatologia clínica', 'Joana', '', '', '', '', '', ''),
(3, '2022/15/03', 'OUtro teste', '92981152683', 'Tocantins', 'Palmeirópolis', '-- Selecione --', 'Tipo contato 1', 'Agendado', 'Dermatologia clínica', 'João', '', '', '', '', '', ''),
(4, '2022/15/03', 'OUtro teste', '92981152683', 'Tocantins', 'Palmeirópolis', 'Facebook', 'Tipo contato 1', 'Agendado', 'Dermatologia clínica', 'João', '', '', '', '', '', ''),
(5, '26/04/2022', 'Salomão Da Costa Cruz', '92981152683', 'Amapá', 'Serra do Navio', 'Facebook', 'Tipo contato 2', 'Agendado', 'Dermatologia estética', 'João', '', '', '', '', '', ''),
(6, '26/04/2022', 'Salomão Da Costa Cruz', '92981152683', 'Amapá', 'Serra do Navio', 'Facebook', 'Tipo contato 2', 'Cancelado', 'Dermatologia estética', 'João', '', '', '', '', '', ''),
(7, '27/04/2022', 'qweqwe', '3123123', 'Acre', 'Assis Brasil', 'Instagram', 'Tipo contato 2', 'Não agendado', 'Dermatologia estética', 'João', '', '', '', '', '', ''),
(8, '27/04/2022', 'teste teste', '123123', 'Ceará', 'Antonina do Norte', 'Facebook', 'Tipo contato 2', 'Cancelado', 'Dermatologia estética', 'João', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `motivos`
--

CREATE TABLE `motivos` (
  `id` int(11) NOT NULL,
  `motivo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_motivos`
--

CREATE TABLE `usuarios_motivos` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `motivo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Índices para tabela `motivos`
--
ALTER TABLE `motivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `motivo` (`motivo`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario`),
  ADD KEY `usuario` (`usuario`);

--
-- Índices para tabela `usuarios_motivos`
--
ALTER TABLE `usuarios_motivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `motivo` (`motivo`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `dados`
--
ALTER TABLE `dados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `motivos`
--
ALTER TABLE `motivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios_motivos`
--
ALTER TABLE `usuarios_motivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `dados`
--
ALTER TABLE `dados`
  ADD CONSTRAINT `dados_ibfk_1` FOREIGN KEY (`fk_usuario`) REFERENCES `usuarios` (`usuario`);

--
-- Limitadores para a tabela `usuarios_motivos`
--
ALTER TABLE `usuarios_motivos`
  ADD CONSTRAINT `usuarios_motivos_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`),
  ADD CONSTRAINT `usuarios_motivos_ibfk_2` FOREIGN KEY (`motivo`) REFERENCES `motivos` (`motivo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
