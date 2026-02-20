<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206115008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_AGENT_PLAYER');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_AGENT_TEAM');
        $this->addSql('DROP INDEX FK_AGENT_TEAM ON agent');
        $this->addSql('DROP INDEX FK_AGENT_PLAYER ON agent');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_AGENT_PLAYER FOREIGN KEY (id_player) REFERENCES player (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_AGENT_TEAM FOREIGN KEY (id_team) REFERENCES team (id)');
        $this->addSql('CREATE INDEX FK_AGENT_TEAM ON agent (id_team)');
        $this->addSql('CREATE INDEX FK_AGENT_PLAYER ON agent (id_player)');
    }
}
