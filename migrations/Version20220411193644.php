<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411193644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_checks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_options_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_checks (id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE app_options (id INT NOT NULL, parent_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D687ACA727ACA70 ON app_options (parent_id)');
        $this->addSql('ALTER TABLE app_options ADD CONSTRAINT FK_3D687ACA727ACA70 FOREIGN KEY (parent_id) REFERENCES app_checks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_options DROP CONSTRAINT FK_3D687ACA727ACA70');
        $this->addSql('DROP SEQUENCE app_checks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_options_id_seq CASCADE');
        $this->addSql('DROP TABLE app_checks');
        $this->addSql('DROP TABLE app_options');
    }
}
