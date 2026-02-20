<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206152535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY fk_agent_team');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY fk_agent_user');
        $this->addSql('DROP INDEX fk_agent_user ON agent');
        $this->addSql('DROP INDEX fk_agent_team ON agent');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT fk_agent_team FOREIGN KEY (id_team) REFERENCES team (id) ON UPDATE CASCADE ON DELETE SET NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT fk_agent_user FOREIGN KEY (id_player) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX fk_agent_user ON agent (id_player)');
        $this->addSql('CREATE INDEX fk_agent_team ON agent (id_team)');
    }
}
