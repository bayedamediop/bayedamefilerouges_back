<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120154925 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referentiels ADD grpe_competence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE referentiels ADD CONSTRAINT FK_590B3B4714343FFE FOREIGN KEY (grpe_competence_id) REFERENCES groupe_competence (id)');
        $this->addSql('CREATE INDEX IDX_590B3B4714343FFE ON referentiels (grpe_competence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referentiels DROP FOREIGN KEY FK_590B3B4714343FFE');
        $this->addSql('DROP INDEX IDX_590B3B4714343FFE ON referentiels');
        $this->addSql('ALTER TABLE referentiels DROP grpe_competence_id');
    }
}
