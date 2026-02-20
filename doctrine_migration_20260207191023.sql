-- Doctrine Migration File Generated on 2026-02-07 19:10:23

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
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20260206211901', '2026-02-07 19:10:23', 0);

-- Version DoctrineMigrations\Version20260207180646
ALTER TABLE agent DROP FOREIGN KEY fk_agent_questionnaire_agent;
DROP INDEX IDX_268B9C9D232B318C ON agent;
ALTER TABLE agent CHANGE game game VARCHAR(255) NOT NULL;
ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBE2ECF88 FOREIGN KEY (id_player) REFERENCES user (id);
ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4FC0BA1D FOREIGN KEY (id_team) REFERENCES team (id);
DROP INDEX fk_agent_user ON agent;
CREATE INDEX IDX_268B9C9DBE2ECF88 ON agent (id_player);
DROP INDEX fk_agent_team ON agent;
CREATE INDEX IDX_268B9C9D4FC0BA1D ON agent (id_team);
ALTER TABLE questionnaire_agent DROP FOREIGN KEY fk_questionnaire_agent_agent;
ALTER TABLE questionnaire_agent CHANGE id_agent id_agent INT NOT NULL;
CREATE UNIQUE INDEX UNIQ_89D51F9E232B318C ON questionnaire_agent (game);
DROP INDEX fk_questionnaire_agent_agent ON questionnaire_agent;
CREATE INDEX IDX_89D51F9EC80EDDAD ON questionnaire_agent (id_agent);
ALTER TABLE questionnaire_agent ADD CONSTRAINT fk_questionnaire_agent_agent FOREIGN KEY (id_agent) REFERENCES agent (id) ON DELETE CASCADE;
-- Version DoctrineMigrations\Version20260207180646 update table metadata;
INSERT INTO doctrine_migration_versions (version, executed_at, execution_time) VALUES ('DoctrineMigrations\\Version20260207180646', '2026-02-07 19:10:23', 0);
