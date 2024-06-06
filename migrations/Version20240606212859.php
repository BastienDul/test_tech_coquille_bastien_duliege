<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606212859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer AS SELECT id, firstname, lastname, mail, society_name, date_add FROM customer');
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, societyname VARCHAR(255) NOT NULL, date_add DATETIME NOT NULL)');
        $this->addSql('INSERT INTO customer (id, firstname, lastname, mail, societyname, date_add) SELECT id, firstname, lastname, mail, society_name, date_add FROM __temp__customer');
        $this->addSql('DROP TABLE __temp__customer');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer AS SELECT id, firstname, lastname, mail, societyname, date_add FROM customer');
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, society_name VARCHAR(255) NOT NULL, date_add DATETIME NOT NULL)');
        $this->addSql('INSERT INTO customer (id, firstname, lastname, mail, society_name, date_add) SELECT id, firstname, lastname, mail, societyname, date_add FROM __temp__customer');
        $this->addSql('DROP TABLE __temp__customer');
    }
}
