<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613112114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE app_result_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE app_results_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_results (id INT NOT NULL, check_item_id INT NOT NULL, check_option_id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, is_valid BOOLEAN DEFAULT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72FE645954731CDE ON app_results (check_item_id)');
        $this->addSql('CREATE INDEX IDX_72FE6459D23AFAF ON app_results (check_option_id)');
        $this->addSql('ALTER TABLE app_results ADD CONSTRAINT FK_72FE645954731CDE FOREIGN KEY (check_item_id) REFERENCES app_checks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_results ADD CONSTRAINT FK_72FE6459D23AFAF FOREIGN KEY (check_option_id) REFERENCES app_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE app_result');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE app_results_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE app_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_result (id INT NOT NULL, check_item_id INT NOT NULL, check_option_id INT NOT NULL, value VARCHAR(255) DEFAULT NULL, is_valid BOOLEAN DEFAULT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f669ac9354731cde ON app_result (check_item_id)');
        $this->addSql('CREATE INDEX idx_f669ac93d23afaf ON app_result (check_option_id)');
        $this->addSql('ALTER TABLE app_result ADD CONSTRAINT fk_136ac11354731cde FOREIGN KEY (check_item_id) REFERENCES app_checks (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_result ADD CONSTRAINT fk_136ac113d23afaf FOREIGN KEY (check_option_id) REFERENCES app_options (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE app_results');
    }
}
