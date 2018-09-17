<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180917205917 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE avis (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recette_id INTEGER DEFAULT NULL, pseudo VARCHAR(255) NOT NULL, contenu CLOB NOT NULL, date_creation DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_8F91ABF089312FE9 ON avis (recette_id)');
        $this->addSql('CREATE TABLE recette (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, description CLOB NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE recette');
    }
}
