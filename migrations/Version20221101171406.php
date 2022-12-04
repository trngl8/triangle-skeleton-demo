<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101171406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_offers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_offers (id INT NOT NULL, title VARCHAR(255) NOT NULL, amount INT NOT NULL, currency VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_offers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_orders (id INT NOT NULL, offer_id INT DEFAULT NULL, action VARCHAR(32) NOT NULL, amount INT NOT NULL, currency VARCHAR(32) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2C906E53C674EE ON app_orders (offer_id)');
        $this->addSql('ALTER TABLE app_orders ADD CONSTRAINT FK_2C906E53C674EE FOREIGN KEY (offer_id) REFERENCES app_offers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_orders DROP CONSTRAINT FK_2C906E53C674EE');
        $this->addSql('DROP SEQUENCE app_offers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_orders_id_seq CASCADE');
        $this->addSql('DROP TABLE app_offers');
        $this->addSql('DROP TABLE app_orders');
    }
}
