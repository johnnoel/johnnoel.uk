<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211009134919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_2FB3D0EE6BF700BD');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EEE16C6B94');
        $this->addSql('CREATE TEMPORARY TABLE __temp__project AS SELECT id, status_id, name, alias, url, rss_url, code_url, description FROM project');
        $this->addSql('DROP TABLE project');
        $this->addSql('CREATE TABLE project (id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , status_id CHAR(36) NOT NULL COLLATE BINARY --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL COLLATE BINARY, alias VARCHAR(255) NOT NULL COLLATE BINARY, url CLOB DEFAULT NULL COLLATE BINARY, rss_url CLOB DEFAULT NULL COLLATE BINARY, code_url CLOB DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, uptime_robot_id CLOB DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_2FB3D0EE6BF700BD FOREIGN KEY (status_id) REFERENCES project_status (id) ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO project (id, status_id, name, alias, url, rss_url, code_url, description) SELECT id, status_id, name, alias, url, rss_url, code_url, description FROM __temp__project');
        $this->addSql('DROP TABLE __temp__project');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE6BF700BD ON project (status_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EEE16C6B94 ON project (alias)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_2FB3D0EEE16C6B94');
        $this->addSql('DROP INDEX IDX_2FB3D0EE6BF700BD');
        $this->addSql('CREATE TEMPORARY TABLE __temp__project AS SELECT id, status_id, name, alias, url, description, rss_url, code_url FROM project');
        $this->addSql('DROP TABLE project');
        $this->addSql('CREATE TABLE project (id CHAR(36) NOT NULL --(DC2Type:guid)
        , status_id CHAR(36) NOT NULL --(DC2Type:guid)
        , name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, url CLOB DEFAULT NULL, description CLOB DEFAULT NULL, rss_url CLOB DEFAULT NULL, code_url CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO project (id, status_id, name, alias, url, description, rss_url, code_url) SELECT id, status_id, name, alias, url, description, rss_url, code_url FROM __temp__project');
        $this->addSql('DROP TABLE __temp__project');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EEE16C6B94 ON project (alias)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE6BF700BD ON project (status_id)');
    }
}
