-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/02/2025 às 22:03
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `personal_trainer`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `evolucao_alunos`
--

CREATE TABLE `evolucao_alunos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `percentual_gordura` decimal(5,2) DEFAULT NULL,
  `braco` decimal(5,2) DEFAULT NULL,
  `perna` decimal(5,2) DEFAULT NULL,
  `cintura` decimal(5,2) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `exercicios`
--

CREATE TABLE `exercicios` (
  `id` int(11) NOT NULL,
  `treino_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `series` int(11) NOT NULL,
  `repeticoes` int(11) NOT NULL,
  `carga` decimal(5,2) DEFAULT NULL,
  `descanso` time DEFAULT NULL,
  `nome_exercicio` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exercicios`
--

INSERT INTO `exercicios` (`id`, `treino_id`, `nome`, `series`, `repeticoes`, `carga`, `descanso`, `nome_exercicio`) VALUES
(5, 5, '', 2, 2, 0.00, NULL, 'TESTE 1'),
(6, 5, '', 3, 3, 0.00, NULL, 'TESTE 2'),
(7, 6, '', 4, 8, 0.00, NULL, 'SUPINO INCLINADO'),
(8, 6, '', 4, 8, 0.00, NULL, 'SUPINO 45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `progresso`
--

CREATE TABLE `progresso` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `percentual_gordura` decimal(5,2) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinos`
--

CREATE TABLE `treinos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `nome_treino` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `personal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `treinos`
--

INSERT INTO `treinos` (`id`, `aluno_id`, `nome_treino`, `descricao`, `data_criacao`, `personal_id`) VALUES
(5, 8, 'FICHA A', NULL, '2025-02-11 19:25:56', 5),
(6, 4, 'FICHA A', NULL, '2025-02-11 19:42:22', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','personal','aluno') NOT NULL DEFAULT 'aluno',
  `personal_id` int(11) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expira` datetime DEFAULT NULL,
  `token_recuperacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `personal_id`, `data_criacao`, `reset_token`, `reset_expira`, `token_recuperacao`) VALUES
(3, 'Administrador', 'admin@email.com', '$2y$10$iQC2jUECM0c2E.sIzFH0YetZspuv610TQ3N3nLwQCcaLZY8VQpv76', 'admin', NULL, '2025-02-11 16:06:20', NULL, NULL, NULL),
(4, 'teste', 'aluno@aluno.com', '$2y$10$fvc/OLGb388s3bzH5Y2NXOpk4ORiGYUAR/OLzoDBCcpXKvwlU4s5O', 'aluno', 5, '2025-02-11 17:24:09', NULL, NULL, NULL),
(5, 'personal', 'personal@teste.com', '$2y$10$IBeUw7PU9yte.nm9QNHS4OGNB1cnvd9KC0WJ3M89WpVMwNAQxRZly', 'personal', NULL, '2025-02-11 17:42:31', NULL, NULL, NULL),
(8, 'teste2', 'aluno2@aluno2.com', '$2y$10$EOQd44hIhqOQXyPs9auEROuKV3mxiRyz/LPykA6wn4T3p1z6XnPWu', 'aluno', 5, '2025-02-11 19:13:21', NULL, NULL, NULL),
(9, 'arthur', 'cod4152412@gmail.com', '$2y$10$zpyNLJm6cQR7hj/bdk.6L.o.ZL1tIerlRzQuVVj0tVT6kp3YznMS.', 'aluno', 5, '2025-02-11 20:17:44', 'bc53d9c4928172e08afe9d7cd560b5ced88806f872f709056c1d23cac9a0b025220468931937a34f9817f98c82a8db651589', '2025-02-11 22:17:53', 'e17c341cc2efb3a1c145bedbb80f54ecf54f8147');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `evolucao_alunos`
--
ALTER TABLE `evolucao_alunos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- Índices de tabela `exercicios`
--
ALTER TABLE `exercicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treino_id` (`treino_id`);

--
-- Índices de tabela `progresso`
--
ALTER TABLE `progresso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- Índices de tabela `treinos`
--
ALTER TABLE `treinos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `fk_personal` (`personal_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `evolucao_alunos`
--
ALTER TABLE `evolucao_alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `exercicios`
--
ALTER TABLE `exercicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `progresso`
--
ALTER TABLE `progresso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `treinos`
--
ALTER TABLE `treinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `evolucao_alunos`
--
ALTER TABLE `evolucao_alunos`
  ADD CONSTRAINT `evolucao_alunos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `exercicios`
--
ALTER TABLE `exercicios`
  ADD CONSTRAINT `exercicios_ibfk_1` FOREIGN KEY (`treino_id`) REFERENCES `treinos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `progresso`
--
ALTER TABLE `progresso`
  ADD CONSTRAINT `progresso_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `treinos`
--
ALTER TABLE `treinos`
  ADD CONSTRAINT `fk_personal` FOREIGN KEY (`personal_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `treinos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
