<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220813081055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_product (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE app_topics ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_topics ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_topics ADD CONSTRAINT FK_74F52BB9166D1F9C FOREIGN KEY (project_id) REFERENCES app_project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_topics ADD CONSTRAINT FK_74F52BB94584665A FOREIGN KEY (product_id) REFERENCES app_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_74F52BB9166D1F9C ON app_topics (project_id)');
        $this->addSql('CREATE INDEX IDX_74F52BB94584665A ON app_topics (product_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_topics DROP CONSTRAINT FK_74F52BB94584665A');
        $this->addSql('DROP SEQUENCE app_product_id_seq CASCADE');
        $this->addSql('DROP TABLE app_product');
        $this->addSql('ALTER TABLE app_topics DROP CONSTRAINT FK_74F52BB9166D1F9C');
        $this->addSql('DROP INDEX IDX_74F52BB9166D1F9C');
        $this->addSql('DROP INDEX IDX_74F52BB94584665A');
        $this->addSql('ALTER TABLE app_topics DROP project_id');
        $this->addSql('ALTER TABLE app_topics DROP product_id');
    }
}
