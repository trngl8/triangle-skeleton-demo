<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620184241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE app_topics ADD profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_topics ADD CONSTRAINT FK_74F52BB9CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_74F52BB9CCFA12B8 ON app_topics (profile_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_topics DROP CONSTRAINT FK_74F52BB9CCFA12B8');
        $this->addSql('DROP INDEX IDX_74F52BB9CCFA12B8');
        $this->addSql('ALTER TABLE app_topics DROP profile_id');

    }
}
