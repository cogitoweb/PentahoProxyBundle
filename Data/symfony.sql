CREATE TABLE pentaho_proxy_bundle_db
(
    id SERIAL NOT NULL,
    release character varying(255),
    host character varying(255) NOT NULL,
    port integer DEFAULT 8080,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    initialization_vector character varying(255),
	
	PRIMARY KEY (id)
);

CREATE TABLE pentaho_proxy_bundle_report
(
    id SERIAL NOT NULL,
    db_id integer NOT NULL,
    output_format character varying(255),
    output_type character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    params character varying(255),
    form character varying(255),
	
	PRIMARY KEY(id)
);
CREATE INDEX idx_db_id ON pentaho_proxy_bundle_report USING btree (db_id);

ALTER TABLE ONLY pentaho_proxy_bundle_report ADD CONSTRAINT FK_pentaho_proxy_bundle_report_db_id_pentaho_proxy_bundle_db_id FOREIGN KEY (db_id) REFERENCES pentaho_proxy_bundle_db(id);