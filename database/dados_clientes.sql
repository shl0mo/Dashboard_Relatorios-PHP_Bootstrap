-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 08-Jun-2022 às 02:39
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
  `motivo_agendamento` text DEFAULT NULL,
  `motivo_cancelamento` text DEFAULT NULL,
  `motivo_comparecimento` text DEFAULT NULL,
  `outros_agendamento` text DEFAULT NULL,
  `outros_cancelamento` text DEFAULT NULL,
  `outros_comparecimento` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `dados`
--

INSERT INTO `dados` (`id`, `data_agendamento`, `nome`, `telefone`, `estado`, `cidade`, `canal_origem`, `tipo_contato`, `status`, `area`, `fk_usuario`, `motivo_agendamento`, `motivo_cancelamento`, `motivo_comparecimento`, `outros_agendamento`, `outros_cancelamento`, `outros_comparecimento`) VALUES
(1, '06/06/2022', 'João', '92981152683', 'Amapá', 'Serra do Navio', 'Google', 'Tipo contato 1', 'Não agendado', 'Dermatologia estética', 'João', 'Outros', NULL, NULL, 'ok', NULL, NULL),
(2, '2022/15/04', 'Maria', '92981152683', 'Roraima', 'Amajari', 'Google', 'Tipo contato 2', 'Sim', 'Dermatologia clínica', 'Joana', '', '', '', '', '', ''),
(3, '2022/15/03', 'Joana', '92981152683', 'Tocantins', 'Palmeirópolis', 'Google', 'Tipo contato 1', 'Agendado', 'Dermatologia clínica', 'João', '', '', '', '', '', ''),
(4, '2022/15/03', 'Teste', '92981152683', 'Tocantins', 'Palmeirópolis', 'Facebook', 'Tipo contato 1', 'Agendado', 'Dermatologia clínica', 'João', '', '', '', '', '', ''),
(5, '26/04/2022', 'Ricardo', '92981152683', 'Amapá', 'Serra do Navio', 'Facebook', 'Tipo contato 2', 'Agendado', 'Dermatologia estética', 'João', '', '', '', '', '', ''),
(6, '26/04/2022', 'Paulo', '92981152683', 'Amapá', 'Serra do Navio', 'Facebook', 'Tipo contato 2', 'Cancelado', 'Dermatologia estética', 'João', '', 'Cancelou', '', '', '', ''),
(7, '27/04/2022', 'Geovana', '3123123', 'Acre', 'Assis Brasil', 'Instagram', 'Tipo contato 2', 'Não agendado', 'Dermatologia estética', 'João', 'Não agendou', '', '', '', '', ''),
(8, '27/04/2022', 'teste teste', '123123', 'Ceará', 'Antonina do Norte', 'Facebook', 'Tipo contato 2', 'Cancelado', 'Dermatologia estética', 'João', '', NULL, '', '', '', ''),
(9, '28/04/2022', 'Beto', '0898', 'Roraima', 'Caracaraí', 'Indicação', 'Tipo contato 1', 'Não compareceu', 'Dermatologia clínica', 'João', NULL, NULL, NULL, NULL, NULL, NULL),
(10, '28/04/2022', 'Teste 1231', '0898', 'Roraima', 'Caracaraí', 'Indicação', 'Tipo contato 1', 'Não compareceu', 'Dermatologia clínica', 'João', NULL, NULL, NULL, NULL, NULL, NULL),
(11, '28/04/2022', 'Carlos', '123', 'Bahia', 'Angical', 'Doctoralia', 'Tipo contato 1', 'Não agendado', 'Dermatologia estética', 'João', 'Data', NULL, NULL, NULL, NULL, NULL),
(12, '28/04/2022', 'Julia', '988908', 'Amapá', 'Calçoene', 'Indicação', 'Tipo contato 1', 'Não agendado', 'Dermatologia clínica', 'João', 'Outros', NULL, NULL, 'Não agendou', NULL, NULL),
(13, '28/04/2022', 'ok1', '23', 'Ceará', 'Arneiroz', 'Doctoralia', 'Tipo contato 2', 'Não compareceu', 'Dermatologia clínica', 'João', 'ok', NULL, 'Não compareceu', NULL, NULL, NULL),
(14, '28/04/2022', 'ok4', '123123', 'Amazonas', 'Atalaia do Norte', 'Facebook', 'Tipo contato 2', 'Cancelado', 'Dermatologia estética', 'João', NULL, 'Outros', NULL, 'CAncelado', NULL, NULL),
(15, '28/04/2022', 'ok5', '1231233', 'Roraima', 'Boa Vista', 'Facebook', 'Tipo contato 2', 'Não compareceu', 'Dermatologia estética', 'João', NULL, NULL, 'Outros', NULL, NULL, 'Faltou'),
(16, '28/04/2022', 'ok6', '123123123', 'Pará', 'Abel Figueiredo', 'Instagram', 'Tipo contato 1', 'Cancelado', 'Dermatologia clínica', 'João', NULL, 'Outros', NULL, NULL, 'Cancelamento', NULL),
(17, '27/04/2022', 'André', '123123', 'Ceará', 'Antonina do Norte', 'Facebook', 'Tipo contato 2', 'Cancelado', 'Dermatologia estética', 'João', NULL, NULL, NULL, NULL, NULL, NULL),
(18, '14/05/2022', 'Gabriela', '1231231312', 'São Paulo', 'Altair', 'Instagram', 'Tipo contato 2', 'Agendado', 'Dermatologia estética', 'João', NULL, NULL, NULL, NULL, NULL, NULL),
(19, '14/05/2022', 'Juliana', '92981152683', 'Sergipe', 'Canhoba', 'Indicação', 'Tipo contato 2', 'Agendado', 'Dermatologia estética', 'João', NULL, NULL, NULL, NULL, NULL, NULL),
(20, '14/05/2022', 'Jonas', '92981152683', 'Sergipe', 'Canhoba', 'Indicação', 'Tipo contato 2', 'Agendado', 'Dermatologia estética', 'João', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `motivos`
--

CREATE TABLE `motivos` (
  `id` int(11) NOT NULL,
  `motivo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `motivos`
--

INSERT INTO `motivos` (`id`, `motivo`) VALUES
(1, 'Convênio que não atende'),
(2, 'Criança'),
(3, 'Data'),
(4, 'Só queria informações'),
(5, 'Tratamento/procedimento que não faz');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos`
--

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL,
  `tipo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tipos`
--

INSERT INTO `tipos` (`id`, `tipo`) VALUES
(1, 'Dermatologia clínica'),
(2, 'Dermatologia estética');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `tipo_usuario` varchar(100) DEFAULT NULL,
  `nome_usuario` varchar(200) NOT NULL,
  `sexo` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`usuario`, `senha`, `tipo_usuario`, `nome_usuario`, `sexo`) VALUES
('123', 'senha', 'Dermatologia clínica', 'Teste', 'Masculino'),
('admin', 'admin', 'Administrador', '', NULL),
('armando123', 'senhaarmando', 'Dermatologia clínica', 'Armando', 'Masculino'),
('carlos', 'senhacarlos', 'Dermatologia clínica', 'Carlos', 'Masculino'),
('Joana', 'senha joana', 'Dermatologia estética', 'Joana Silva', 'Feminino'),
('joanasilva', '123', 'Dermatologia estética', 'Joana', 'Feminino'),
('João', 'senha', 'Dermatologia clínica', 'João Silva', 'Masculino'),
('joaosilva', 'senhadojoao', 'Dermatologia clínica', 'João Silva', 'Masculino'),
('jonas', '123', 'Dermatologia clínica', 'Jonas', 'Masculino'),
('julia', '123', 'Dermatologia clínica', 'Julia', 'Feminino'),
('julianadavid', 'julianasenha', 'Dermatologia clínica', 'Juliana David', 'Feminino'),
('marcossilva', 'senhamarcos', 'Dermatologia clínica', 'Marcos Silva', 'Masculino'),
('ok', 'ok', 'Dermatologia clínica', 'ok', 'Masculino');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_motivos`
--

CREATE TABLE `usuarios_motivos` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `motivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios_motivos`
--

INSERT INTO `usuarios_motivos` (`id`, `usuario`, `motivo`) VALUES
(1, 'julia', 1),
(2, 'julia', 3),
(3, 'julia', 4),
(4, 'armando123', 1),
(5, 'armando123', 4),
(6, 'jonas', 2),
(7, 'jonas', 3),
(8, 'julia', 1),
(9, 'julia', 1),
(10, '123', 1),
(11, 'marcossilva', 2),
(12, 'marcossilva', 3),
(13, 'marcossilva', 4),
(14, 'ok', 1);

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
-- Índices para tabela `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `motivos`
--
ALTER TABLE `motivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios_motivos`
--
ALTER TABLE `usuarios_motivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  ADD CONSTRAINT `usuarios_motivos_ibfk_2` FOREIGN KEY (`motivo`) REFERENCES `motivos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
