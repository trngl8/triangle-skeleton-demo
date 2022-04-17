<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417162318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE app_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_result (id INT NOT NULL, check_item_id INT NOT NULL, check_option_id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, is_valid BOOLEAN DEFAULT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_136AC11354731CDE ON app_result (check_item_id)');
        $this->addSql('CREATE INDEX IDX_136AC113D23AFAF ON app_result (check_option_id)');
        $this->addSql('ALTER TABLE app_result ADD CONSTRAINT FK_136AC11354731CDE FOREIGN KEY (check_item_id) REFERENCES app_checks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_result ADD CONSTRAINT FK_136AC113D23AFAF FOREIGN KEY (check_option_id) REFERENCES app_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE app_result_id_seq CASCADE');
        $this->addSql('DROP TABLE app_result');
    }
}
