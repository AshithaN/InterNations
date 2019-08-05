<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190803085418 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE user_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user_group_user (user_group_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(user_group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_3AE4BD51ED93D47 ON user_group_user (user_group_id)');
        $this->addSql('CREATE INDEX IDX_3AE4BD5A76ED395 ON user_group_user (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, name FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, name) SELECT id, name FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_group_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, name FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO user (id, name) SELECT id, name FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
