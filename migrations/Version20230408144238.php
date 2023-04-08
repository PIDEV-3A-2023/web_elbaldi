<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230408144238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK2');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK1');
        $this->addSql('DROP TABLE avis');
        $this->addSql('ALTER TABLE command_produit DROP FOREIGN KEY command_produit_ibfk_2');
        $this->addSql('ALTER TABLE command_produit CHANGE id_cmd id_cmd INT DEFAULT NULL');
        $this->addSql('ALTER TABLE command_produit ADD CONSTRAINT FK_C6CFBFC4B1F8F49A FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd)');
        $this->addSql('ALTER TABLE commande CHANGE id_panier id_panier INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT DEFAULT NULL, CHANGE ref_produit ref_produit VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCEDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_1');
        $this->addSql('ALTER TABLE livraison CHANGE id_cmd id_cmd INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FB1F8F49A FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_3');
        $this->addSql('ALTER TABLE panier CHANGE id_user id_user INT DEFAULT NULL, CHANGE nombre_article nombre_article INT DEFAULT NULL, CHANGE total_panier total_panier DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_refproduit');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_idpanier');
        $this->addSql('DROP INDEX id_user ON panier_produit');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_refproduit');
        $this->addSql('ALTER TABLE panier_produit DROP Quantite');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A62FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6EDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('DROP INDEX fk_refproduit ON panier_produit');
        $this->addSql('CREATE INDEX IDX_D31F28A6EDB1BFF7 ON panier_produit (ref_produit)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_refproduit FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY fk_event');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1');
        $this->addSql('ALTER TABLE participation CHANGE id_client id_client INT DEFAULT NULL, CHANGE id_event id_event INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FD52B4B97 FOREIGN KEY (id_event) REFERENCES evenement (id_event)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_1');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE quantite quantite INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1');
        $this->addSql('ALTER TABLE promotion CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY CLE_ETRANG');
        $this->addSql('ALTER TABLE question CHANGE id_quiz id_quiz INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E2F32E690 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY cle_etrangere');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('ALTER TABLE reservation CHANGE id_bonplan id_bonplan INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557C66DB00 FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id_avis INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_bonplan INT NOT NULL, note_avis DOUBLE PRECISION NOT NULL, date_avis DATE NOT NULL, INDEX FK1 (id_user), INDEX FK2 (id_bonplan), PRIMARY KEY(id_avis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK2 FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commande CHANGE id_panier id_panier INT NOT NULL');
        $this->addSql('ALTER TABLE command_produit DROP FOREIGN KEY FK_C6CFBFC4B1F8F49A');
        $this->addSql('ALTER TABLE command_produit CHANGE id_cmd id_cmd INT NOT NULL');
        $this->addSql('ALTER TABLE command_produit ADD CONSTRAINT command_produit_ibfk_2 FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC6B3CA4B');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCEDB1BFF7');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT NOT NULL, CHANGE ref_produit ref_produit VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FB1F8F49A');
        $this->addSql('ALTER TABLE livraison CHANGE id_cmd id_cmd INT NOT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT livraison_ibfk_1 FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF26B3CA4B');
        $this->addSql('ALTER TABLE panier CHANGE id_user id_user INT NOT NULL, CHANGE nombre_article nombre_article INT DEFAULT 0, CHANGE total_panier total_panier DOUBLE PRECISION DEFAULT \'0\'');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_ibfk_3 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A62FBB81F');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6EDB1BFF7');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6EDB1BFF7');
        $this->addSql('ALTER TABLE panier_produit ADD Quantite INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_refproduit FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_idpanier FOREIGN KEY (id_panier) REFERENCES panier (id_panier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX id_user ON panier_produit (id_panier, ref_produit)');
        $this->addSql('DROP INDEX idx_d31f28a6edb1bff7 ON panier_produit');
        $this->addSql('CREATE INDEX fk_refproduit ON panier_produit (ref_produit)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6EDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FD52B4B97');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FE173B1B8');
        $this->addSql('ALTER TABLE participation CHANGE id_event id_event INT NOT NULL, CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT fk_event FOREIGN KEY (id_event) REFERENCES evenement (id_event) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT NOT NULL, CHANGE quantite quantite INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B');
        $this->addSql('ALTER TABLE promotion CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E2F32E690');
        $this->addSql('ALTER TABLE question CHANGE id_quiz id_quiz INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT CLE_ETRANG FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557C66DB00');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE reservation CHANGE id_bonplan id_bonplan INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT cle_etrangere FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
