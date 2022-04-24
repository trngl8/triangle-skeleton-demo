<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424191403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE profile_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE profile (id INT NOT NULL, name VARCHAR(255) NOT NULL, locale VARCHAR(5) NOT NULL, timezone VARCHAR(32) NOT NULL, active BOOLEAN NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER INDEX idx_136ac11354731cde RENAME TO IDX_F669AC9354731CDE');
        $this->addSql('ALTER INDEX idx_136ac113d23afaf RENAME TO IDX_F669AC93D23AFAF');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE profile_id_seq CASCADE');
        $this->addSql('DROP TABLE profile');
        $this->addSql('ALTER INDEX idx_f669ac93d23afaf RENAME TO idx_136ac113d23afaf');
        $this->addSql('ALTER INDEX idx_f669ac9354731cde RENAME TO idx_136ac11354731cde');
    }
}
