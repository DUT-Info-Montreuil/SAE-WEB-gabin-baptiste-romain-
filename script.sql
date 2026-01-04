-- Utilisateurs du système
CREATE TABLE Utilisateur (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  code_qr VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Buvettes
CREATE TABLE Buvette (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  adresse VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Produits vendus dans une buvette
CREATE TABLE Produit (
  id INT NOT NULL AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  prix_vente DECIMAL(10, 2) NOT NULL,
  stock_actuel INT DEFAULT 0,
  id_buvette INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_produit_buvette FOREIGN KEY (id_buvette) REFERENCES Buvette (id) ON DELETE CASCADE
);

-- Entrées de stock
CREATE TABLE Entree_Stock (
  id INT NOT NULL AUTO_INCREMENT,
  nom_fournisseur VARCHAR(150) DEFAULT NULL,
  id_buvette INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_entree_buvette FOREIGN KEY (id_buvette) REFERENCES Buvette (id) ON DELETE CASCADE
);

-- Inventaires
CREATE TABLE Inventaire (
  id INT NOT NULL AUTO_INCREMENT,
  date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_buvette INT NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_inventaire_buvette FOREIGN KEY (id_buvette) REFERENCES Buvette (id) ON DELETE CASCADE
);

-- Commandes clients
CREATE TABLE Commande (
  id INT NOT NULL AUTO_INCREMENT,
  date_heure DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  montant_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  id_client INT NOT NULL,
  id_serveur INT DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_commande_client FOREIGN KEY (id_client) REFERENCES Utilisateur (id),
  CONSTRAINT fk_commande_serveur FOREIGN KEY (id_serveur) REFERENCES Utilisateur (id)
);

-- Lien utilisateur / buvette
CREATE TABLE etre_membre (
  id_utilisateur INT NOT NULL,
  id_buvette INT NOT NULL,
  role VARCHAR(50) DEFAULT NULL,
  solde DECIMAL(10, 2) DEFAULT 0.00,
  PRIMARY KEY (id_utilisateur, id_buvette),
  CONSTRAINT fk_membre_user FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur (id) ON DELETE CASCADE,
  CONSTRAINT fk_membre_buvette FOREIGN KEY (id_buvette) REFERENCES Buvette (id) ON DELETE CASCADE
);

-- Détails des commandes
CREATE TABLE composer (
  id_commande INT NOT NULL,
  id_produit INT NOT NULL,
  quantite INT NOT NULL DEFAULT 1,
  prix_unit DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (id_commande, id_produit),
  CONSTRAINT fk_composer_commande FOREIGN KEY (id_commande) REFERENCES Commande (id) ON DELETE CASCADE,
  CONSTRAINT fk_composer_produit FOREIGN KEY (id_produit) REFERENCES Produit (id)
);

-- Détails des entrées de stock
CREATE TABLE ligne_ent (
  id_entree INT NOT NULL,
  id_produit INT NOT NULL,
  quantite INT NOT NULL,
  prix DECIMAL(10, 2) DEFAULT NULL,
  PRIMARY KEY (id_entree, id_produit),
  CONSTRAINT fk_ligne_ent_entree FOREIGN KEY (id_entree) REFERENCES Entree_Stock (id) ON DELETE CASCADE,
  CONSTRAINT fk_ligne_ent_produit FOREIGN KEY (id_produit) REFERENCES Produit (id)
);

-- Détails des inventaires
CREATE TABLE ligne_inv (
  id_inventaire INT NOT NULL,
  id_produit INT NOT NULL,
  ecart INT DEFAULT 0,
  reel INT NOT NULL,
  PRIMARY KEY (id_inventaire, id_produit),
  CONSTRAINT fk_ligne_inv_inventaire FOREIGN KEY (id_inventaire) REFERENCES Inventaire (id) ON DELETE CASCADE,
  CONSTRAINT fk_ligne_inv_produit FOREIGN KEY (id_produit) REFERENCES Produit (id)
);