-- ============================================================
--  PharmaFEFO — Script de base de données
--  Moteur : MySQL 8+
-- ============================================================

CREATE DATABASE IF NOT EXISTS farmafefo
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

DROP DATABASE farmafefo;
USE farmafefo ;

-- ---- UTILISATEUR ----
CREATE TABLE UTILISATEUR (
    id          VARCHAR(36)  PRIMARY KEY,
    nom         VARCHAR(255) NOT NULL,
    email       VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,                          -- CORRECTION : colonne manquante
    role        VARCHAR(50)  NOT NULL
                CHECK (role IN ('PREPARATEUR', 'PHARMACIEN', 'ADMIN')),
    actif       BOOLEAN      NOT NULL DEFAULT TRUE
);

-- ---- MEDICAMENT ----
CREATE TABLE MEDICAMENT (
    id                  VARCHAR(36)    PRIMARY KEY,
    nom_commercial      VARCHAR(255)   NOT NULL,
    code_cip13          VARCHAR(13)    UNIQUE NOT NULL,
    prix_achat_unitaire DECIMAL(10,2)  NOT NULL,
    seuil_alerte_stock  INT            NOT NULL
);

-- ---- COMMANDE ----
CREATE TABLE COMMANDE (
    id               VARCHAR(36)  PRIMARY KEY,
    reference        VARCHAR(100) UNIQUE NOT NULL,
    fournisseur      VARCHAR(255) NOT NULL,
    statut           VARCHAR(50)  NOT NULL
                     CHECK (statut IN ('EN_ATTENTE', 'RECEPTIONNEE')),
    date_reception   DATE,
    receptionnee_par VARCHAR(36),
    CONSTRAINT fk_commande_utilisateur
        FOREIGN KEY (receptionnee_par) REFERENCES UTILISATEUR(id)
);

-- ---- LOT ----
CREATE TABLE LOT (
    id                  VARCHAR(36)  PRIMARY KEY,
    medicament_id       VARCHAR(36)  NOT NULL,
    numero_lot          VARCHAR(100) NOT NULL,
    date_peremption     DATE         NOT NULL,
    quantite_disponible INT          NOT NULL CHECK (quantite_disponible >= 0),
    statut              VARCHAR(50)  NOT NULL
                        CHECK (statut IN ('DISPONIBLE','ALERTE_ORANGE','ALERTE_ROUGE','PERIME','DETRUIT')),
    cree_par            VARCHAR(36)  NOT NULL,
    CONSTRAINT fk_lot_medicament  FOREIGN KEY (medicament_id) REFERENCES MEDICAMENT(id),
    CONSTRAINT fk_lot_utilisateur FOREIGN KEY (cree_par)      REFERENCES UTILISATEUR(id)
);

-- ---- MOUVEMENT_STOCK ----
CREATE TABLE MOUVEMENT_STOCK (
    id            VARCHAR(36) PRIMARY KEY,
    lot_id        VARCHAR(36) NOT NULL,
    medicament_id VARCHAR(36) NOT NULL,
    type          VARCHAR(50) NOT NULL
                  CHECK (type IN ('ENTREE','SORTIE_VENTE','SORTIE_DISPENSATION','PERTE_PEREMPTION')),
    quantite      INT         NOT NULL CHECK (quantite > 0),
    effectue_par  VARCHAR(36) NOT NULL,
    effectue_le   TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mouvement_lot        FOREIGN KEY (lot_id)        REFERENCES LOT(id),
    CONSTRAINT fk_mouvement_medicament FOREIGN KEY (medicament_id) REFERENCES MEDICAMENT(id),
    CONSTRAINT fk_mouvement_utilisateur FOREIGN KEY (effectue_par) REFERENCES UTILISATEUR(id)
);

-- ============================================================
--  Données de test
-- ============================================================

-- Mots de passe hashés avec password_hash('password123', PASSWORD_DEFAULT)
-- Utilise ce hash PHP pour tester : password123
INSERT INTO UTILISATEUR (id, nom, email, mot_de_passe, role, actif) VALUES
(
    'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
    'Jean Dupont',
    'jean.dupont@pharmacie.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'PHARMACIEN',
    TRUE
),
(
    'b2c3d4e5-f67a-8b9c-0d1e-2f3a4b5c6d7e',
    'Alice Martin',
    'alice.martin@pharmacie.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'PREPARATEUR',
    TRUE
),
(
    'c0000000-0000-0000-0000-000000000001',
    'Admin Système',
    'admin@pharmacie.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'ADMIN',
    TRUE
);

INSERT INTO MEDICAMENT (id, nom_commercial, code_cip13, prix_achat_unitaire, seuil_alerte_stock) VALUES
(
    'c3d4e5f6-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
    'Doliprane 1000mg',
    '3400935555551',
    1.50,
    50
),
(
    'd4e5f67a-8b9c-0d1e-2f3a-4b5c6d7e8f9a',
    'Amoxicilline 500mg',
    '3400936666662',
    3.20,
    20
);

INSERT INTO COMMANDE (id, reference, fournisseur, statut, date_reception, receptionnee_par) VALUES
(
    'e5f67a8b-9c0d-1e2f-3a4b-5c6d7e8f9a0b',
    'CMD-2026-001',
    'Grossiste PharmaPlus',
    'RECEPTIONNEE',
    '2026-06-10',
    'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d'
);

INSERT INTO LOT (id, medicament_id, numero_lot, date_peremption, quantite_disponible, statut, cree_par) VALUES
(
    'f67a8b9c-0d1e-2f3a-4b5c-6d7e8f9a0b1c',
    'c3d4e5f6-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
    'LOT-DOLI001',
    '2028-12-31',
    100,
    'DISPONIBLE',
    'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d'
),
(
    'f67a8b9c-0d1e-2f3a-4b5c-6d7e8f9a0b2d',
    'c3d4e5f6-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
    'LOT-DOLI-ALERTE',
    '2026-07-10',
    30,
    'ALERTE_ROUGE',
    'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d'
);

INSERT INTO MOUVEMENT_STOCK (id, lot_id, medicament_id, type, quantite, effectue_par, effectue_le) VALUES
(
    '7a8b9c0d-1e2f-3a4b-5c6d-7e8f9a0b1c2d',
    'f67a8b9c-0d1e-2f3a-4b5c-6d7e8f9a0b1c',
    'c3d4e5f6-7a8b-9c0d-1e2f-3a4b5c6d7e8f',
    'ENTREE',
    100,
    'a1b2c3d4-e5f6-7a8b-9c0d-1e2f3a4b5c6d',
    '2026-06-10 14:30:00'
);
