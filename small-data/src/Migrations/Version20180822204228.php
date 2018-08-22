<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180822204228 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('CREATE SEQUENCE phylum_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE species (id INT NOT NULL, phylum_id INT DEFAULT NULL, worms_aphia_id TEXT NOT NULL, species_name_worms TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A50FF712E087F00 ON species (worms_aphia_id)');
        $this->addSql('CREATE INDEX IDX_A50FF712721C6AEF ON species (phylum_id)');
        $this->addSql('CREATE TABLE inputter (id INT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN inputter.roles IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE occurrence (id INT NOT NULL, species_id INT DEFAULT NULL, inputter_id INT DEFAULT NULL, last_modifier_id INT NOT NULL, event_date DATE NOT NULL, vernacular_name TEXT DEFAULT NULL, scientific_name_at_collection TEXT DEFAULT NULL, decimal_longitude DOUBLE PRECISION NOT NULL, decimal_latitude DOUBLE PRECISION NOT NULL, locality TEXT DEFAULT NULL, location_id TEXT DEFAULT NULL, occurrence_remarks TEXT DEFAULT NULL, associated_media_url TEXT DEFAULT NULL, occurrence_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_modified_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEFD81F3B2A1D860 ON occurrence (species_id)');
        $this->addSql('CREATE INDEX IDX_BEFD81F31A534654 ON occurrence (inputter_id)');
        $this->addSql('CREATE INDEX IDX_BEFD81F3904F8A5F ON occurrence (last_modifier_id)');
        $this->addSql('CREATE TABLE phylum (id INT NOT NULL, phylum_name_worms VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF712721C6AEF FOREIGN KEY (phylum_id) REFERENCES phylum (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE occurrence ADD CONSTRAINT FK_BEFD81F3B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE occurrence ADD CONSTRAINT FK_BEFD81F31A534654 FOREIGN KEY (inputter_id) REFERENCES inputter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE occurrence ADD CONSTRAINT FK_BEFD81F3904F8A5F FOREIGN KEY (last_modifier_id) REFERENCES inputter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE occurrence DROP CONSTRAINT FK_BEFD81F3B2A1D860');
        $this->addSql('ALTER TABLE occurrence DROP CONSTRAINT FK_BEFD81F31A534654');
        $this->addSql('ALTER TABLE occurrence DROP CONSTRAINT FK_BEFD81F3904F8A5F');
        $this->addSql('ALTER TABLE species DROP CONSTRAINT FK_A50FF712721C6AEF');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('DROP SEQUENCE phylum_id_seq CASCADE');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE inputter');
        $this->addSql('DROP TABLE occurrence');
        $this->addSql('DROP TABLE phylum');
    }
}
