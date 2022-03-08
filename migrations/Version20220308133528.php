<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220308133528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add inventory tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_cards_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_customers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE app_vendors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_cards (id INT NOT NULL, uuid UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_cards.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_customers (id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_customers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE app_vendors (id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_vendors.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE app_cards_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_customers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE app_vendors_id_seq CASCADE');
        $this->addSql('DROP TABLE app_cards');
        $this->addSql('DROP TABLE app_customers');
        $this->addSql('DROP TABLE app_vendors');
    }
}
