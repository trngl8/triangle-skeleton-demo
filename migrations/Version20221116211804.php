<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221116211804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE app_orders ALTER delivery_email SET NOT NULL');
        $this->addSql('ALTER TABLE app_orders ALTER uuid SET NOT NULL');
        $this->addSql('ALTER TABLE app_product ADD image_tag VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_product ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_product ADD abilities JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE app_product ADD level INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders ALTER uuid DROP NOT NULL');
        $this->addSql('ALTER TABLE app_orders ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE app_orders ALTER delivery_email DROP NOT NULL');
        $this->addSql('ALTER TABLE app_product DROP image_tag');
        $this->addSql('ALTER TABLE app_product DROP description');
        $this->addSql('ALTER TABLE app_product DROP abilities');
        $this->addSql('ALTER TABLE app_product DROP level');
    }
}
