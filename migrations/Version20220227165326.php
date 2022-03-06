<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220227165326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activitelike (id INT AUTO_INCREMENT NOT NULL, activitelike_id INT DEFAULT NULL, transportlike_id INT DEFAULT NULL, INDEX IDX_3621BD485CAD9D5 (activitelike_id), INDEX IDX_3621BD48CF536838 (transportlike_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activitelike ADD CONSTRAINT FK_3621BD485CAD9D5 FOREIGN KEY (activitelike_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE activitelike ADD CONSTRAINT FK_3621BD48CF536838 FOREIGN KEY (transportlike_id) REFERENCES transport (id)');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B87555151E4A7B3A FOREIGN KEY (type_transport_id) REFERENCES transport (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B87555151E4A7B3A ON activite (type_transport_id)');
        $this->addSql('ALTER TABLE transport DROP nombre_participant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activitelike');
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B87555151E4A7B3A');
        $this->addSql('DROP INDEX UNIQ_B87555151E4A7B3A ON activite');
        $this->addSql('ALTER TABLE transport ADD nombre_participant INT NOT NULL');
    }
}
