<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210926100551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO project_status (id, name, alias) VALUES (:id1, :name1, :alias1), (:id2, :name2, :alias2), (:id3, :name3, :alias3)', [
            'id1' => Uuid::uuid4()->toString(),
            'name1' => 'Active',
            'alias1' => 'active',
            'id2' => Uuid::uuid4()->toString(),
            'name2' => 'Archived',
            'alias2' => 'archived',
            'id3' => Uuid::uuid4()->toString(),
            'name3' => 'On Hold',
            'alias3' => 'on-hold',
        ]);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM project_status WHERE alias IN (:alias1, :alias2, :alias3)', [
            'alias1' => 'active',
            'alias2' => 'archived',
            'alias3' => 'on-hold',
        ]);
    }
}
