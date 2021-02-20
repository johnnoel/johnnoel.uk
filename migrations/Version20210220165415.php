<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210220165415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_responses (id UUID NOT NULL, service_id UUID NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, response JSONB NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C8B3AEFED5CA9E6 ON api_responses (service_id)');
        $this->addSql('COMMENT ON COLUMN api_responses.date_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE services (id UUID NOT NULL, name VARCHAR(255) NOT NULL, credentials JSONB NOT NULL, enabled BOOLEAN DEFAULT \'true\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE api_responses ADD CONSTRAINT FK_4C8B3AEFED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_responses DROP CONSTRAINT FK_4C8B3AEFED5CA9E6');
        $this->addSql('DROP TABLE api_responses');
        $this->addSql('DROP TABLE services');
    }
}
