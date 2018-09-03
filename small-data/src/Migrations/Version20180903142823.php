<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180903142823 extends AbstractMigration
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
        $this->addSql('ALTER TABLE species DROP CONSTRAINT fk_a50ff712e4873418');
        $this->addSql('DROP INDEX uniq_a50ff712e4873418');
        $this->addSql('ALTER TABLE species DROP main_image_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_image_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER TABLE species ADD main_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT fk_a50ff712e4873418 FOREIGN KEY (main_image_id) REFERENCES species_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_a50ff712e4873418 ON species (main_image_id)');
    }
}
