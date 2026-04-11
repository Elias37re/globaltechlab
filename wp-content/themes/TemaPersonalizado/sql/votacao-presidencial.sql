-- Active: 1768434856746@@127.0.0.1@3306@db_pesquisa
-- Execute no phpMyAdmin no MESMO banco do WordPress (o prefixo wp_ pode ser outro no seu site).
-- Troque wpgtl_ pelo prefixo real. Recomendado em wp-config: $table_prefix = 'wpgtl_'; (com _ no fim).
-- Se o prefixo for só 'wpgtl' sem underscore, o tema usa na mesma o nome wpgtl_votacao_* ao gravar dados.

CREATE DATABASE db_pesquisa;


CREATE DATABASE nome_do_seu_banco 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;


USE db_pesquisa;


CREATE TABLE IF NOT EXISTS `wpgtl_votacao_presidencial` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `participante_id` bigint(20) UNSIGNED DEFAULT NULL,
  `candidato` varchar(50) NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_candidato` (`candidato`),
  KEY `idx_participante_id` (`participante_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `wpgtl_votacao_presidencial_participantes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome_completo` varchar(255) NOT NULL,
  `cidade` varchar(120) NOT NULL,
  `estado` char(2) NOT NULL COMMENT 'UF (ex.: SP, RJ)',
  `email` varchar(255) NOT NULL,
  `voto_token` varchar(64) DEFAULT NULL COMMENT 'Token secreto até o voto ser registrado',
  `votou_em` datetime DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_email` (`email`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Consultas úteis:
-- SELECT candidato, COUNT(*) AS total FROM wp_votacao_presidencial GROUP BY candidato;
