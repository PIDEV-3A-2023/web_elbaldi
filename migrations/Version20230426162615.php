<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426162615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK1');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK2');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_idpaniera');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_1');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_3');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_refproduit');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_idpanier');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY fk_event');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_1');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY CLE_ETRANG');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY cle_etrangere');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE bonplan');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_produit');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE utilisateur ADD reset_token VARCHAR(255) DEFAULT NULL, CHANGE etat etat LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id_avis INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_bonplan INT NOT NULL, note_avis DOUBLE PRECISION NOT NULL, date_avis DATE NOT NULL, INDEX FK1 (id_user), INDEX FK2 (id_bonplan), PRIMARY KEY(id_avis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bonplan (id_bonplan INT AUTO_INCREMENT NOT NULL, titre_bonplan VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description_bonplan VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image_bonplan VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_bonplan)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie (id_categorie INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Description VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX nom_categorie (nom_categorie), PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (id_cmd INT AUTO_INCREMENT NOT NULL, id_panier INT NOT NULL, etat VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT \'Pending\' NOT NULL COLLATE `utf8mb4_general_ci`, date_cmd DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, total DOUBLE PRECISION NOT NULL, adresse VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_idpaniera (id_panier), UNIQUE INDEX id_cmdunique (id_cmd, id_panier), PRIMARY KEY(id_cmd)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commentaire (id_commentaire INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, ref_produit VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, contenu VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_comm DATE NOT NULL, INDEX ref_produit (ref_produit), INDEX id_user (id_user), PRIMARY KEY(id_commentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evenement (id_event INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_debut_event VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COLLATE `utf8mb4_general_ci`, date_fin_event VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COLLATE `utf8mb4_general_ci`, nb_participant INT DEFAULT NULL, awards VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livraison (id_livraison INT AUTO_INCREMENT NOT NULL, id_cmd INT NOT NULL, status_livraison VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT \'Pending\' NOT NULL COLLATE `utf8mb4_general_ci`, adresse_livraison VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_livraison DATE DEFAULT NULL, INDEX id_cmd (id_cmd), PRIMARY KEY(id_livraison)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (id_panier INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, nombre_article INT DEFAULT 0, total_panier DOUBLE PRECISION DEFAULT \'0\', UNIQUE INDEX id_user (id_user), PRIMARY KEY(id_panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier_produit (id_panier INT NOT NULL, ref_produit VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Quantite INT DEFAULT 1 NOT NULL, INDEX fk_refproduit (ref_produit), UNIQUE INDEX id_user (id_panier, ref_produit), INDEX IDX_D31F28A62FBB81F (id_panier), PRIMARY KEY(id_panier, ref_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participation (id_participation INT AUTO_INCREMENT NOT NULL, id_client INT NOT NULL, id_event INT NOT NULL, date DATE NOT NULL, INDEX fk_event (id_event), INDEX id_client (id_client), PRIMARY KEY(id_participation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE produit (ref_produit VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, id_categorie INT NOT NULL, libelle VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix_vente DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, INDEX id_categorie (id_categorie), PRIMARY KEY(ref_produit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promotion (id_promotion INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, code_promo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, taux DOUBLE PRECISION NOT NULL, INDEX id_user (id_user), PRIMARY KEY(id_promotion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question (id_question INT AUTO_INCREMENT NOT NULL, id_quiz INT NOT NULL, difficulte VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, questionn VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reponse1 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reponse2 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reponse3 VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, solution VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX CLE_ETRANG (id_quiz), PRIMARY KEY(id_question)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quiz (id_quiz INT AUTO_INCREMENT NOT NULL, difficulte VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, score INT DEFAULT NULL, nom VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_quiz)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id_reservation INT NOT NULL, id_bonplan INT NOT NULL, id_user INT NOT NULL, nombre_personnes INT NOT NULL, date_reservation DATE NOT NULL, statut_reservation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX cle_etrangere (id_bonplan), INDEX reservation_ibfk_1 (id_user), PRIMARY KEY(id_reservation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK2 FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_idpaniera FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT livraison_ibfk_1 FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_ibfk_3 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_refproduit FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_idpanier FOREIGN KEY (id_panier) REFERENCES panier (id_panier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT fk_event FOREIGN KEY (id_event) REFERENCES evenement (id_event) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT CLE_ETRANG FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT cle_etrangere FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP reset_token, CHANGE etat etat LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
