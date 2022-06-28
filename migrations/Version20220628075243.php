<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628075243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_invites ADD profile_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_invites ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE app_invites ALTER started_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE app_invites ALTER started_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN app_invites.started_at IS NULL');
        $this->addSql('ALTER TABLE app_invites ADD CONSTRAINT FK_EE23EA21CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EE23EA21CCFA12B8 ON app_invites (profile_id)');
        $this->addSql('ALTER TABLE app_topics ALTER started_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE app_topics ALTER started_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN app_topics.started_at IS NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_topics ALTER started_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE app_topics ALTER started_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN app_topics.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE app_invites DROP CONSTRAINT FK_EE23EA21CCFA12B8');
        $this->addSql('DROP INDEX IDX_EE23EA21CCFA12B8');
        $this->addSql('ALTER TABLE app_invites DROP profile_id');
        $this->addSql('ALTER TABLE app_invites ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE app_invites ALTER started_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE app_invites ALTER started_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN app_invites.started_at IS \'(DC2Type:datetime_immutable)\'');
    }
}
