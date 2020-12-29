<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201204090326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence_valides (id INT AUTO_INCREMENT NOT NULL, aprenant_id INT DEFAULT NULL, referentiel_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, competence_id INT DEFAULT NULL, INDEX IDX_4180A7E79B43182 (aprenant_id), INDEX IDX_4180A7E7805DB139 (referentiel_id), INDEX IDX_4180A7E7D0C07AFF (promo_id), INDEX IDX_4180A7E715761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competence_valides ADD CONSTRAINT FK_4180A7E79B43182 FOREIGN KEY (aprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competence_valides ADD CONSTRAINT FK_4180A7E7805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiels (id)');
        $this->addSql('ALTER TABLE competence_valides ADD CONSTRAINT FK_4180A7E7D0C07AFF FOREIGN KEY (promo_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE competence_valides ADD CONSTRAINT FK_4180A7E715761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE competence_valides');
    }
}
