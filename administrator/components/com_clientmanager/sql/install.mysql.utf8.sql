-- Tabela Principal de Clientes
CREATE TABLE IF NOT EXISTS `#__clientmanager_clients` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome_completo` VARCHAR(255) NOT NULL,
  `apelido` VARCHAR(255) NOT NULL,
  `data_nascimento` DATE NULL,
  `sexo` VARCHAR(50) NULL,
  `cpf` VARCHAR(14) NULL,
  `estado_civil` VARCHAR(100) NULL,
  `nome_conjuge` VARCHAR(255) NULL,
  `indicado_por` INT NOT NULL DEFAULT 0,
  `cns` VARCHAR(20) NULL COMMENT 'Cartão do SUS',
  `titulo_eleitor` VARCHAR(20) NULL,
  `zona_eleitoral` VARCHAR(10) NULL,
  `secao_eleitoral` VARCHAR(10) NULL,
  `tel_fixo` VARCHAR(20) NULL,
  `celular_whatsapp` VARCHAR(20) NULL,
  `email` VARCHAR(150) NULL,
  `instagram` VARCHAR(100) NULL,
  `rua` VARCHAR(255) NULL,
  `numero` VARCHAR(20) NULL,
  `complemento` VARCHAR(100) NULL,
  `bairro` VARCHAR(100) NULL,
  `cidade` VARCHAR(100) NULL,
  `cep` VARCHAR(10) NULL,
  `tipo_residencia` VARCHAR(100) NULL,
  `escolaridade` VARCHAR(100) NULL,
  `profissao` VARCHAR(150) NULL,
  `local_trabalho` VARCHAR(255) NULL,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_by` INT NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `idx_cpf` (`cpf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- Tabela de Demandas (associada aos clientes)
CREATE TABLE IF NOT EXISTS `#__clientmanager_demands` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cliente_id` INT UNSIGNED NOT NULL,
  `titulo` VARCHAR(255) NULL,
  `local_captacao` VARCHAR(255) NULL,
  `descricao` TEXT NULL,
  `tipo_demanda` VARCHAR(150) NULL,
  `setor_encaminhado` VARCHAR(150) NULL,
  `protocolo` VARCHAR(50) NULL,
  `oficio` VARCHAR(50) NULL,
  `requerimento` VARCHAR(50) NULL,
  `acompanhamento` TEXT NULL,
  `descricao_conclusao` TEXT NULL,
  `data_conclusao` DATETIME NULL,
  `custo` DECIMAL(14, 2) NULL,
  `status`  TINYINT NOT NULL DEFAULT 0,
  `published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_by` INT NOT NULL DEFAULT 0,
  `created` DATETIME NOT NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_cliente_id` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- Tabela de Filhos (associada aos clientes)
CREATE TABLE IF NOT EXISTS `#__clientmanager_client_children` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cliente_id` INT UNSIGNED NOT NULL,
  `nome_completo` VARCHAR(255) NOT NULL,
  `data_nascimento` DATE NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_cliente_id` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
