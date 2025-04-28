CREATE TABLE `agendamentos` (
  `id_agendamento` int(11) NOT NULL AUTO_INCREMENT,
  `id_doacao` int(11) NOT NULL,
  `id_entregador` int(11) DEFAULT NULL,
  `tipo` enum('retirada','entrega') NOT NULL,
  `data_hora` datetime NOT NULL,
  `status` enum('pendente','concluido','cancelado') DEFAULT 'pendente',
  `observacoes` text DEFAULT NULL,
  PRIMARY KEY (`id_agendamento`),
  KEY `id_doacao` (`id_doacao`),
  KEY `id_entregador` (`id_entregador`),
  CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_doacao`) REFERENCES `doacoes` (`id_doacao`),
  CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_entregador`) REFERENCES `entregadores` (`id_entregador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `armarios` (
  `id_armario` int(11) NOT NULL AUTO_INCREMENT,
  `localizacao` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `capacidade` int(11) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  PRIMARY KEY (`id_armario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `doacoes` (
  `id_doacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_item` int(11) NOT NULL,
  `id_doador` int(11) NOT NULL,
  `quantidade_doada` int(11) NOT NULL,
  `data_doacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_armario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_doacao`),
  KEY `id_item` (`id_item`),
  KEY `id_doador` (`id_doador`),
  KEY `id_armario` (`id_armario`),
  CONSTRAINT `doacoes_ibfk_1` FOREIGN KEY (`id_item`) REFERENCES `itens_necessarios` (`id_item`),
  CONSTRAINT `doacoes_ibfk_2` FOREIGN KEY (`id_doador`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `doacoes_ibfk_3` FOREIGN KEY (`id_armario`) REFERENCES `armarios` (`id_armario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `entregadores` (
  `id_entregador` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_entregador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `itens_necessarios` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nome_item` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `prioridade` enum('baixa','media','alta') DEFAULT 'media',
  `data_solicitacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_item`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `itens_necessarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `tipo` enum('instituicao','voluntario') NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;