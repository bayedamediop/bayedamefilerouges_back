<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120130514 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referentiels DROP FOREIGN KEY FK_590B3B47A660B158');
        $this->addSql('DROP INDEX IDX_590B3B47A660B158 ON referentiels');
        $this->addSql('ALTER TABLE referentiels DROP competences_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE referentiels ADD competences_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE referentiels ADD CONSTRAINT FK_590B3B47A660B158 FOREIGN KEY (competences_id) REFERENCES competence (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_590B3B47A660B158 ON referentiels (competences_id)');
    }
}
