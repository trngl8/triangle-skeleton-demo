<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231214224140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'template for blocks';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_blocks ADD controller VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_blocks DROP controller');
    }
}
