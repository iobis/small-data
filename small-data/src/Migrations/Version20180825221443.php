<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180825221443 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER SEQUENCE inputter_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE occurrence_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE phylum_id_seq INCREMENT BY 1');
        $this->addSql('ALTER SEQUENCE species_id_seq INCREMENT BY 1');
        $this->addSql('CREATE TABLE occurrence_validator (occurrence_id INT NOT NULL, inputter_id INT NOT NULL, PRIMARY KEY(occurrence_id, inputter_id))');
        $this->addSql('CREATE INDEX IDX_294F272530572FAC ON occurrence_validator (occurrence_id)');
        $this->addSql('CREATE INDEX IDX_294F27251A534654 ON occurrence_validator (inputter_id)');
        $this->addSql('ALTER TABLE occurrence_validator ADD CONSTRAINT FK_294F272530572FAC FOREIGN KEY (occurrence_id) REFERENCES occurrence (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE occurrence_validator ADD CONSTRAINT FK_294F27251A534654 FOREIGN KEY (inputter_id) REFERENCES inputter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE occurrence_inputter');
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
        $this->addSql('CREATE TABLE occurrence_inputter (occurrence_id INT NOT NULL, inputter_id INT NOT NULL, PRIMARY KEY(occurrence_id, inputter_id))');
        $this->addSql('CREATE INDEX idx_61785fed30572fac ON occurrence_inputter (occurrence_id)');
        $this->addSql('CREATE INDEX idx_61785fed1a534654 ON occurrence_inputter (inputter_id)');
        $this->addSql('ALTER TABLE occurrence_inputter ADD CONSTRAINT fk_61785fed30572fac FOREIGN KEY (occurrence_id) REFERENCES occurrence (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE occurrence_inputter ADD CONSTRAINT fk_61785fed1a534654 FOREIGN KEY (inputter_id) REFERENCES inputter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE occurrence_validator');
    }
}
