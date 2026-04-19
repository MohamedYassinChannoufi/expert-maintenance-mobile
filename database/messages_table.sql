-- =====================================================
-- Table: messages
-- Description: Messages between employees/technicians
-- =====================================================

CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `expediteur_id` INT NOT NULL,
  `destinataire_id` INT NOT NULL,
  `sujet` VARCHAR(200) NOT NULL,
  `contenu` TEXT NOT NULL,
  `dateenvoi` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `datelecture` DATETIME NULL,
  `lu` TINYINT(1) NOT NULL DEFAULT 0,
  `valsync` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  INDEX `idx_expediteur` (`expediteur_id`),
  INDEX `idx_destinataire` (`destinataire_id`),
  INDEX `idx_dateenvoi` (`dateenvoi`),
  INDEX `idx_lu` (`lu`),
  INDEX `idx_valsync` (`valsync`),
  FOREIGN KEY (`expediteur_id`) REFERENCES `employes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`destinataire_id`) REFERENCES `employes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Données de test pour les messages
-- =====================================================

INSERT INTO `messages` (`id`, `expediteur_id`, `destinataire_id`, `sujet`, `contenu`, `dateenvoi`, `datelecture`, `lu`, `valsync`) VALUES
(1, 1, 2, 'Bienvenue dans l\'équipe', 'Bonjour Enzo, Bienvenue dans l\'équipe de maintenance. N''hésite pas à me contacter si tu as des questions.', '2024-01-15 09:00:00', '2024-01-15 09:30:00', 1, 1),
(2, 2, 1, 'Question sur intervention', 'Bonjour Jean, J''ai une question concernant l''intervention chez le client ABC. Peux-tu me rappeler ?', '2024-01-16 14:30:00', NULL, 0, 1),
(3, 1, 2, 'RE: Question sur intervention', 'Bien sûr, je t''appelle dans 10 minutes. Cordialement, Jean', '2024-01-16 14:45:00', '2024-01-16 15:00:00', 1, 1),
(4, 1, 2, 'Nouvelle intervention urgente', 'Salut Enzo, Peux-tu prendre en charge l''intervention urgente chez Client XYZ ? C''est en priorité haute.', '2024-01-17 08:00:00', NULL, 0, 1),
(5, 2, 1, 'Rapport intervention terminée', 'Bonjour Jean, L''intervention chez ABC est terminée. Tout s''est bien passé. Rapport envoyé.', '2024-01-17 11:30:00', NULL, 0, 1);

-- =====================================================
-- Vérification
-- =====================================================

SELECT 'Table messages créée avec succès !' AS status;
SELECT COUNT(*) AS total_messages FROM messages;
