<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260206211901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBE2ECF88 FOREIGN KEY (id_player) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4FC0BA1D FOREIGN KEY (id_team) REFERENCES team (id)');
        $this->addSql('DROP INDEX fk_agent_user ON agent');
        $this->addSql('CREATE INDEX IDX_268B9C9DBE2ECF88 ON agent (id_player)');
        $this->addSql('DROP INDEX fk_agent_team ON agent');
        $this->addSql('CREATE INDEX IDX_268B9C9D4FC0BA1D ON agent (id_team)');
        $this->addSql('ALTER TABLE questionnaire_agent DROP FOREIGN KEY fk_questionnaire_agent_agent');
        $this->addSql('DROP INDEX fk_questionnaire_agent_agent ON questionnaire_agent');
        $this->addSql('ALTER TABLE questionnaire_agent CHANGE id_agent id_agent INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBE2ECF88');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D4FC0BA1D');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBE2ECF88');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D4FC0BA1D');
        $this->addSql('DROP INDEX idx_268b9c9dbe2ecf88 ON agent');
        $this->addSql('CREATE INDEX fk_agent_user ON agent (id_player)');
        $this->addSql('DROP INDEX idx_268b9c9d4fc0ba1d ON agent');
        $this->addSql('CREATE INDEX fk_agent_team ON agent (id_team)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBE2ECF88 FOREIGN KEY (id_player) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4FC0BA1D FOREIGN KEY (id_team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE questionnaire_agent CHANGE id_agent id_agent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE questionnaire_agent ADD CONSTRAINT fk_questionnaire_agent_agent FOREIGN KEY (id_agent) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX fk_questionnaire_agent_agent ON questionnaire_agent (id_agent)');
    }
}
