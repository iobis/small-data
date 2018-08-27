<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180825231556 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('CREATE TABLE phylum_validator (inputter_id INT NOT NULL, phylum_id INT NOT NULL, PRIMARY KEY(inputter_id, phylum_id))');
        $this->addSql('CREATE INDEX IDX_1D072AF01A534654 ON phylum_validator (inputter_id)');
        $this->addSql('CREATE INDEX IDX_1D072AF0721C6AEF ON phylum_validator (phylum_id)');
        $this->addSql('ALTER TABLE phylum_validator ADD CONSTRAINT FK_1D072AF01A534654 FOREIGN KEY (inputter_id) REFERENCES inputter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phylum_validator ADD CONSTRAINT FK_1D072AF0721C6AEF FOREIGN KEY (phylum_id) REFERENCES phylum (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE inputter_phylum');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('CREATE TABLE inputter_phylum (inputter_id INT NOT NULL, phylum_id INT NOT NULL, PRIMARY KEY(inputter_id, phylum_id))');
        $this->addSql('CREATE INDEX idx_aa50b2201a534654 ON inputter_phylum (inputter_id)');
        $this->addSql('CREATE INDEX idx_aa50b220721c6aef ON inputter_phylum (phylum_id)');
        $this->addSql('ALTER TABLE inputter_phylum ADD CONSTRAINT fk_aa50b2201a534654 FOREIGN KEY (inputter_id) REFERENCES inputter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inputter_phylum ADD CONSTRAINT fk_aa50b220721c6aef FOREIGN KEY (phylum_id) REFERENCES phylum (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE phylum_validator');
    }
}
