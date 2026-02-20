-- Doctrine Migration File Generated on 2026-02-07 19:10:47

-- Version DoctrineMigrations\Version20260206211901
ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBE2ECF88 FOREIGN KEY (id_player) REFERENCES user (id);
ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4FC0BA1D FOREIGN KEY (id_team) REFERENCES team (id);
DROP INDEX fk_agent_user ON agent;
CREATE INDEX IDX_268B9C9DBE2ECF88 ON agent (id_player);
DROP INDEX fk_agent_team ON agent;
CREATE INDEX IDX_268B9C9D4FC0BA1D ON agent (id_team);
ALTER TABLE questionnaire_agent DROP FOREIGN KEY fk_questionnaire_agent_agent;
DROP INDEX fk_questionnaire_agent_agent ON questionnaire_agent;
ALTER TABLE questionnaire_agent CHANGE id_agent id_agent INT NOT NULL;
-- Version DoctrineMigrations\Version20260206211901 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20260206211901', '2026-02-07 19:10:47', 0);
