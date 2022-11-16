<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221116220651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER SEQUENCE app_product_id_seq RENAME TO app_products_id_seq');
        $this->addSql('ALTER TABLE app_product RENAME TO app_products');

        $this->addSql('ALTER TABLE app_topics DROP CONSTRAINT FK_74F52BB94584665A');
        $this->addSql('ALTER TABLE app_topics ADD CONSTRAINT FK_74F52BB94584665A FOREIGN KEY (product_id) REFERENCES app_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER SEQUENCE app_products_id_seq RENAME TO app_product_id_seq');
        $this->addSql('ALTER TABLE app_products RENAME TO app_product');

        $this->addSql('ALTER TABLE app_topics DROP CONSTRAINT FK_74F52BB94584665A');
        $this->addSql('ALTER TABLE app_topics ADD CONSTRAINT fk_74f52bb94584665a FOREIGN KEY (product_id) REFERENCES app_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
