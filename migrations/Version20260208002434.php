<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208002434 extends AbstractMigration
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
        $this->addSql('ALTER TABLE questionnaire_agent ADD CONSTRAINT FK_89D51F9EC80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_89D51F9E232B318C ON questionnaire_agent (game)');
        $this->addSql('CREATE INDEX IDX_89D51F9EC80EDDAD ON questionnaire_agent (id_agent)');
        $this->addSql('ALTER TABLE reponse_questionnaire DROP FOREIGN KEY fk_reponse_questionnaire');
        $this->addSql('ALTER TABLE reponse_questionnaire DROP FOREIGN KEY fk_reponse_questionnaire');
        $this->addSql('ALTER TABLE reponse_questionnaire ADD questionnaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse_questionnaire ADD CONSTRAINT FK_2C627462C80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_questionnaire ADD CONSTRAINT FK_2C627462CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire_agent (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2C627462CE07E8FF ON reponse_questionnaire (questionnaire_id)');
        $this->addSql('DROP INDEX fk_reponse_questionnaire ON reponse_questionnaire');
        $this->addSql('CREATE INDEX IDX_2C627462C80EDDAD ON reponse_questionnaire (id_agent)');
        $this->addSql('ALTER TABLE reponse_questionnaire ADD CONSTRAINT fk_reponse_questionnaire FOREIGN KEY (id_agent) REFERENCES agent (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBE2ECF88');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D4FC0BA1D');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBE2ECF88');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D4FC0BA1D');
        $this->addSql('DROP INDEX idx_268b9c9d4fc0ba1d ON agent');
        $this->addSql('CREATE INDEX fk_agent_team ON agent (id_team)');
        $this->addSql('DROP INDEX idx_268b9c9dbe2ecf88 ON agent');
        $this->addSql('CREATE INDEX fk_agent_user ON agent (id_player)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBE2ECF88 FOREIGN KEY (id_player) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4FC0BA1D FOREIGN KEY (id_team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE questionnaire_agent DROP FOREIGN KEY FK_89D51F9EC80EDDAD');
        $this->addSql('DROP INDEX UNIQ_89D51F9E232B318C ON questionnaire_agent');
        $this->addSql('DROP INDEX IDX_89D51F9EC80EDDAD ON questionnaire_agent');
        $this->addSql('ALTER TABLE reponse_questionnaire DROP FOREIGN KEY FK_2C627462C80EDDAD');
        $this->addSql('ALTER TABLE reponse_questionnaire DROP FOREIGN KEY FK_2C627462CE07E8FF');
        $this->addSql('DROP INDEX IDX_2C627462CE07E8FF ON reponse_questionnaire');
        $this->addSql('ALTER TABLE reponse_questionnaire DROP FOREIGN KEY FK_2C627462C80EDDAD');
        $this->addSql('ALTER TABLE reponse_questionnaire DROP questionnaire_id');
        $this->addSql('ALTER TABLE reponse_questionnaire ADD CONSTRAINT fk_reponse_questionnaire FOREIGN KEY (id_agent) REFERENCES agent (id)');
        $this->addSql('DROP INDEX idx_2c627462c80eddad ON reponse_questionnaire');
        $this->addSql('CREATE INDEX fk_reponse_questionnaire ON reponse_questionnaire (id_agent)');
        $this->addSql('ALTER TABLE reponse_questionnaire ADD CONSTRAINT FK_2C627462C80EDDAD FOREIGN KEY (id_agent) REFERENCES agent (id) ON DELETE CASCADE');
    }
}
