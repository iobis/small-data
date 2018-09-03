<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180903123247 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_image_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE species ADD main_image_species_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF71235E77ECD FOREIGN KEY (main_image_species_id) REFERENCES species_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A50FF71235E77ECD ON species (main_image_species_id)');
        $this->addSql('ALTER TABLE species_image DROP main_image_species');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_image_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE species_image ADD main_image_species BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE species DROP CONSTRAINT FK_A50FF71235E77ECD');
        $this->addSql('DROP INDEX UNIQ_A50FF71235E77ECD');
        $this->addSql('ALTER TABLE species DROP main_image_species_id');
    }
}
