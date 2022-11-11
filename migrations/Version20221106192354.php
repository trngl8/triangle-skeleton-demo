<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221106192354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders ADD status VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_orders ADD delivery_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_orders ADD delivery_phone VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders DROP status');
        $this->addSql('ALTER TABLE app_orders DROP delivery_email');
        $this->addSql('ALTER TABLE app_orders DROP delivery_phone');
    }
}
