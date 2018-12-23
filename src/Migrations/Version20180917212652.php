<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180917212652 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_8F91ABF089312FE9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avis AS SELECT id, recette_id, pseudo, contenu, date_creation FROM avis');
        $this->addSql('DROP TABLE avis');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recette_id INTEGER DEFAULT NULL, pseudo VARCHAR(255) NOT NULL COLLATE BINARY, contenu CLOB NOT NULL COLLATE BINARY, date_creation DATETIME NOT NULL, CONSTRAINT FK_8F91ABF089312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO avis (id, recette_id, pseudo, contenu, date_creation) SELECT id, recette_id, pseudo, contenu, date_creation FROM __temp__avis');
        $this->addSql('DROP TABLE __temp__avis');
        $this->addSql('CREATE INDEX IDX_8F91ABF089312FE9 ON avis (recette_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recette AS SELECT id, titre, date_creation, description FROM recette');
        $this->addSql('DROP TABLE recette');
        $this->addSql('CREATE TABLE recette (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL COLLATE BINARY, date_creation DATETIME NOT NULL, description CLOB NOT NULL COLLATE BINARY, slug VARCHAR(128) NOT NULL)');
        $this->addSql('INSERT INTO recette (id, titre, date_creation, description) SELECT id, titre, date_creation, description FROM __temp__recette');
        $this->addSql('DROP TABLE __temp__recette');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49BB6390989D9B62 ON recette (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_8F91ABF089312FE9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__avis AS SELECT id, recette_id, pseudo, contenu, date_creation FROM avis');
        $this->addSql('DROP TABLE avis');
        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recette_id INTEGER DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, contenu CLOB NOT NULL, date_creation DATETIME NOT NULL)');
        $this->addSql('INSERT INTO avis (id, recette_id, pseudo, contenu, date_creation) SELECT id, recette_id, pseudo, contenu, date_creation FROM __temp__avis');
        $this->addSql('DROP TABLE __temp__avis');
        $this->addSql('CREATE INDEX IDX_8F91ABF089312FE9 ON avis (recette_id)');
        $this->addSql('DROP INDEX UNIQ_49BB6390989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__recette AS SELECT id, titre, date_creation, description FROM recette');
        $this->addSql('DROP TABLE recette');
        $this->addSql('CREATE TABLE recette (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO recette (id, titre, date_creation, description) SELECT id, titre, date_creation, description FROM __temp__recette');
        $this->addSql('DROP TABLE __temp__recette');
    }
}
