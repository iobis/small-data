<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180808174250 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('CREATE TABLE species (id INT NOT NULL, worms_aphia_id TEXT NOT NULL, species_name_worms TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A50FF712E087F00 ON species (worms_aphia_id)');
        $this->addSql('CREATE TABLE occurrence (id INT NOT NULL, species_id INT DEFAULT NULL, event_date DATE NOT NULL, vernacular_name TEXT DEFAULT NULL, scientific_name_at_collection TEXT DEFAULT NULL, decimal_longitude DOUBLE PRECISION NOT NULL, decimal_latitude DOUBLE PRECISION NOT NULL, locality TEXT DEFAULT NULL, location_id TEXT DEFAULT NULL, occurrence_remarks TEXT DEFAULT NULL, associated_media_url TEXT DEFAULT NULL, occurrence_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEFD81F3B2A1D860 ON occurrence (species_id)');
        $this->addSql('CREATE TABLE inputter (id INT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE occurrence ADD CONSTRAINT FK_BEFD81F3B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE occurrence DROP CONSTRAINT FK_BEFD81F3B2A1D860');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE occurrence');
        $this->addSql('DROP TABLE inputter');
    }
}
