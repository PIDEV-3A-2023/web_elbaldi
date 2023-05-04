<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504190432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE command_produuit (id INT AUTO_INCREMENT NOT NULL, id_cmd INT DEFAULT NULL, ref_produit VARCHAR(30) NOT NULL, date_cmd DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, INDEX command_produit_ibfk_2 (id_cmd), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command_produuit ADD CONSTRAINT FK_F3F5A5B6B1F8F49A FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd)');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK2');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK1');
        $this->addSql('ALTER TABLE avis CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_bonplan id_bonplan INT DEFAULT NULL, CHANGE note_avis note_avis DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF06B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF07C66DB00 FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan)');
        $this->addSql('ALTER TABLE bonplan ADD type_bonplan VARCHAR(255) DEFAULT NULL, CHANGE description_bonplan description_bonplan VARCHAR(255) DEFAULT NULL, CHANGE image_bonplan image_bonplan VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX nom_categorie ON categorie');
        $this->addSql('ALTER TABLE categorie CHANGE nom_categorie nom_categorie VARCHAR(30) NOT NULL, CHANGE Description Description VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE command_produit DROP FOREIGN KEY command_produit_ibfk_2');
        $this->addSql('ALTER TABLE command_produit DROP FOREIGN KEY command_produit_ibfk_2');
        $this->addSql('ALTER TABLE command_produit ADD CONSTRAINT FK_C6CFBFC4B1F8F49A FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd)');
        $this->addSql('DROP INDEX command_produit_ibfk_2 ON command_produit');
        $this->addSql('CREATE INDEX id_cmd ON command_produit (id_cmd)');
        $this->addSql('ALTER TABLE command_produit ADD CONSTRAINT command_produit_ibfk_2 FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande CHANGE id_panier id_panier INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT DEFAULT NULL, CHANGE ref_produit ref_produit VARCHAR(30) DEFAULT NULL, CHANGE contenu contenu VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCEDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('ALTER TABLE event CHANGE image_event image_event VARCHAR(255) NOT NULL, CHANGE time_event time_event DATETIME NOT NULL');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_1');
        $this->addSql('ALTER TABLE livraison CHANGE id_cmd id_cmd INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FB1F8F49A FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_3');
        $this->addSql('ALTER TABLE panier CHANGE id_user id_user INT DEFAULT NULL, CHANGE nombre_article nombre_article INT DEFAULT NULL, CHANGE total_panier total_panier DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF26B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_idpanier');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_refproduit');
        $this->addSql('DROP INDEX id_user ON panier_produit');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY fk_refproduit');
        $this->addSql('ALTER TABLE panier_produit DROP Quantite');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A62FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6EDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('DROP INDEX fk_refproduit ON panier_produit');
        $this->addSql('CREATE INDEX IDX_D31F28A6EDB1BFF7 ON panier_produit (ref_produit)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_refproduit FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C115A61');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F99DED506');
        $this->addSql('DROP INDEX idx_ab55e24f2c115a61 ON participation');
        $this->addSql('CREATE INDEX id_evenement_id ON participation (id_evenement_id)');
        $this->addSql('DROP INDEX idx_ab55e24f99ded506 ON participation');
        $this->addSql('CREATE INDEX id_client_id ON participation (id_client_id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C115A61 FOREIGN KEY (id_evenement_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F99DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY produit_ibfk_1');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE quantite quantite INT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY promotion_ibfk_1');
        $this->addSql('ALTER TABLE promotion CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD16B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY CLE_ETRANG');
        $this->addSql('ALTER TABLE question CHANGE id_quiz id_quiz INT DEFAULT NULL, CHANGE reponse3 reponse3 VARCHAR(255) DEFAULT NULL, CHANGE solution solution VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E2F32E690 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY cle_etrangere');
        $this->addSql('ALTER TABLE reservation CHANGE id_reservation id_reservation INT AUTO_INCREMENT NOT NULL, CHANGE id_bonplan id_bonplan INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557C66DB00 FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan)');
        $this->addSql('ALTER TABLE utilisateur DROP image, DROP etat, DROP reset_token, DROP dateDeNaissance, CHANGE email email VARCHAR(50) NOT NULL, CHANGE numTel numTel INT NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id_event INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_debut_event VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COLLATE `utf8mb4_general_ci`, date_fin_event VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL COLLATE `utf8mb4_general_ci`, nb_participant INT DEFAULT NULL, awards VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE command_produuit DROP FOREIGN KEY FK_F3F5A5B6B1F8F49A');
        $this->addSql('DROP TABLE command_produuit');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF06B3CA4B');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF07C66DB00');
        $this->addSql('ALTER TABLE avis CHANGE id_user id_user INT NOT NULL, CHANGE id_bonplan id_bonplan INT NOT NULL, CHANGE note_avis note_avis DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK2 FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bonplan DROP type_bonplan, CHANGE description_bonplan description_bonplan VARCHAR(255) NOT NULL, CHANGE image_bonplan image_bonplan VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE nom_categorie nom_categorie VARCHAR(255) NOT NULL, CHANGE Description Description VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX nom_categorie ON categorie (nom_categorie)');
        $this->addSql('ALTER TABLE commande CHANGE id_panier id_panier INT NOT NULL');
        $this->addSql('ALTER TABLE command_produit DROP FOREIGN KEY FK_C6CFBFC4B1F8F49A');
        $this->addSql('ALTER TABLE command_produit DROP FOREIGN KEY FK_C6CFBFC4B1F8F49A');
        $this->addSql('ALTER TABLE command_produit ADD CONSTRAINT command_produit_ibfk_2 FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX id_cmd ON command_produit');
        $this->addSql('CREATE INDEX command_produit_ibfk_2 ON command_produit (id_cmd)');
        $this->addSql('ALTER TABLE command_produit ADD CONSTRAINT FK_C6CFBFC4B1F8F49A FOREIGN KEY (id_cmd) REFERENCES commande (id_cmd)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC6B3CA4B');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCEDB1BFF7');
        $this->addSql('ALTER TABLE commentaire CHANGE id_user id_user INT NOT NULL, CHANGE ref_produit ref_produit VARCHAR(30) NOT NULL, CHANGE contenu contenu VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event CHANGE image_event image_event VARCHAR(255) DEFAULT NULL, CHANGE time_event time_event DATE NOT NULL');
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
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_idpanier FOREIGN KEY (id_panier) REFERENCES panier (id_panier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT fk_refproduit FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX id_user ON panier_produit (id_panier, ref_produit)');
        $this->addSql('DROP INDEX idx_d31f28a6edb1bff7 ON panier_produit');
        $this->addSql('CREATE INDEX fk_refproduit ON panier_produit (ref_produit)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6EDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C115A61');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F99DED506');
        $this->addSql('DROP INDEX id_client_id ON participation');
        $this->addSql('CREATE INDEX IDX_AB55E24F99DED506 ON participation (id_client_id)');
        $this->addSql('DROP INDEX id_evenement_id ON participation');
        $this->addSql('CREATE INDEX IDX_AB55E24F2C115A61 ON participation (id_evenement_id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C115A61 FOREIGN KEY (id_evenement_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F99DED506 FOREIGN KEY (id_client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE produit CHANGE id_categorie id_categorie INT NOT NULL, CHANGE quantite quantite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT produit_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD16B3CA4B');
        $this->addSql('ALTER TABLE promotion CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT promotion_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E2F32E690');
        $this->addSql('ALTER TABLE question CHANGE id_quiz id_quiz INT NOT NULL, CHANGE reponse3 reponse3 VARCHAR(255) NOT NULL, CHANGE solution solution VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT CLE_ETRANG FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557C66DB00');
        $this->addSql('ALTER TABLE reservation CHANGE id_reservation id_reservation INT NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE id_bonplan id_bonplan INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT cle_etrangere FOREIGN KEY (id_bonplan) REFERENCES bonplan (id_bonplan) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur ADD image VARCHAR(255) DEFAULT NULL, ADD etat LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD reset_token VARCHAR(10) DEFAULT NULL, ADD dateDeNaissance DATE DEFAULT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE numTel numTel INT DEFAULT NULL, CHANGE ville ville VARCHAR(20) DEFAULT NULL');
    }
}
