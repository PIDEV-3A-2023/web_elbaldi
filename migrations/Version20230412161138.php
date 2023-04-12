<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230412161138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6EDB1BFF7');
        $this->addSql('DROP INDEX fk_refproduit ON panier_produit');
        $this->addSql('CREATE INDEX IDX_D31F28A6EDB1BFF7 ON panier_produit (ref_produit)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6EDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY fk_event');
        $this->addSql('ALTER TABLE participation CHANGE id_client id_client INT DEFAULT NULL, CHANGE id_event id_event INT DEFAULT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FD52B4B97 FOREIGN KEY (id_event) REFERENCES evenement (id_event)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FE173B1B8 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user)');
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
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6EDB1BFF7');
        $this->addSql('DROP INDEX idx_d31f28a6edb1bff7 ON panier_produit');
        $this->addSql('CREATE INDEX fk_refproduit ON panier_produit (ref_produit)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6EDB1BFF7 FOREIGN KEY (ref_produit) REFERENCES produit (ref_produit)');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FD52B4B97');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FE173B1B8');
        $this->addSql('ALTER TABLE participation CHANGE id_event id_event INT NOT NULL, CHANGE id_client id_client INT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (id_client) REFERENCES utilisateur (id_user) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT fk_event FOREIGN KEY (id_event) REFERENCES evenement (id_event) ON UPDATE CASCADE ON DELETE CASCADE');
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
