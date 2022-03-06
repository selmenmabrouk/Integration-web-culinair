<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220227171516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP INDEX FK_B87555151E4A7B3A, ADD UNIQUE INDEX UNIQ_B87555151E4A7B3A (type_transport_id)');
        $this->addSql('ALTER TABLE transport DROP nombre_participant');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP INDEX UNIQ_B87555151E4A7B3A, ADD INDEX FK_B87555151E4A7B3A (type_transport_id)');
        $this->addSql('ALTER TABLE transport ADD nombre_participant INT NOT NULL');
    }
}
