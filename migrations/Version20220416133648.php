<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220416133648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_options ADD open BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE app_options ADD correct BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_options DROP open');
        $this->addSql('ALTER TABLE app_options DROP correct');
    }
}
