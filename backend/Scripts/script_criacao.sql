CREATE TABLE `armarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `localizacao` varchar(255) NOT NULL,
  `senha_acesso` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `doacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instituicao_id` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data_doacao` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `instituicao_id` (`instituicao_id`),
  CONSTRAINT `doacoes_ibfk_1` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `instituicoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instituicao_id` int(11) DEFAULT NULL,
  `nome_item` varchar(255) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `status` enum('pendente','recebido') DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `instituicao_id` (`instituicao_id`),
  CONSTRAINT `itens_ibfk_1` FOREIGN KEY (`instituicao_id`) REFERENCES `instituicoes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;