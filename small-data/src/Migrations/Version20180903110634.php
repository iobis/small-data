<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180903110634 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('CREATE SEQUENCE species_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE species_image (id INT NOT NULL, species_id INT NOT NULL, species_image_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CDF48C3B2A1D860 ON species_image (species_id)');
        $this->addSql('ALTER TABLE species_image ADD CONSTRAINT FK_7CDF48C3B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE species DROP image_species');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('DROP SEQUENCE species_image_id_seq CASCADE');
        $this->addSql('DROP TABLE species_image');
        $this->addSql('ALTER TABLE species ADD image_species VARCHAR(255) DEFAULT NULL');
    }
}
