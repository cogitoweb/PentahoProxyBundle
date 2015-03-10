--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: pentaho_proxy_bundle_db; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pentaho_proxy_bundle_db (
    id integer NOT NULL,
    release character varying(255),
    host character varying(255) NOT NULL,
    port integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    initialization_vector character varying(255)
);


ALTER TABLE pentaho_proxy_bundle_db OWNER TO postgres;

--
-- Name: pentaho_proxy_bundle_db_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pentaho_proxy_bundle_db_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pentaho_proxy_bundle_db_id_seq OWNER TO postgres;

--
-- Name: pentaho_proxy_bundle_db_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pentaho_proxy_bundle_db_id_seq OWNED BY pentaho_proxy_bundle_db.id;


--
-- Name: pentaho_proxy_bundle_report; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE pentaho_proxy_bundle_report (
    id integer NOT NULL,
    db_id integer NOT NULL,
    output_format character varying(255),
    output_type character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    params character varying(255),
    form character varying(255)
);


ALTER TABLE pentaho_proxy_bundle_report OWNER TO postgres;

--
-- Name: pentaho_proxy_bundle_report_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE pentaho_proxy_bundle_report_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE pentaho_proxy_bundle_report_id_seq OWNER TO postgres;

--
-- Name: pentaho_proxy_bundle_report_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE pentaho_proxy_bundle_report_id_seq OWNED BY pentaho_proxy_bundle_report.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pentaho_proxy_bundle_db ALTER COLUMN id SET DEFAULT nextval('pentaho_proxy_bundle_db_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pentaho_proxy_bundle_report ALTER COLUMN id SET DEFAULT nextval('pentaho_proxy_bundle_report_id_seq'::regclass);


--
-- Data for Name: pentaho_proxy_bundle_db; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pentaho_proxy_bundle_db (id, release, host, port, username, password, initialization_vector) FROM stdin;
1	5.2.0.0.209	192.168.22.6	8080	admin	wKe2SH61x12T8SXwXM+8qg==	qfRYJSoB3G8DV7GnsekdMA==
\.


--
-- Name: pentaho_proxy_bundle_db_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pentaho_proxy_bundle_db_id_seq', 1, true);


--
-- Data for Name: pentaho_proxy_bundle_report; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY pentaho_proxy_bundle_report (id, db_id, output_format, output_type, path, params, form) FROM stdin;
3	1	html	view	:home:admin:openerp:openerp_attivita.prpt	progetto_id=266&annomese=$	\N
1	1	html	view	:home:admin:openerp:openerp_attivita.prpt	progetto_id=266&annomese=01/2014	\N
2	1	pdf	download	:home:admin:openerp:openerp_attivita.prpt	progetto_id=266&annomese=01/2014	\N
\.


--
-- Name: pentaho_proxy_bundle_report_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('pentaho_proxy_bundle_report_id_seq', 3, true);


--
-- Name: pentaho_proxy_bundle_db_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pentaho_proxy_bundle_db
    ADD CONSTRAINT pentaho_proxy_bundle_db_pkey PRIMARY KEY (id);


--
-- Name: pentaho_proxy_bundle_report_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY pentaho_proxy_bundle_report
    ADD CONSTRAINT pentaho_proxy_bundle_report_pkey PRIMARY KEY (id);


--
-- Name: idx_db_id; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_db_id ON pentaho_proxy_bundle_report USING btree (db_id);


--
-- Name: fk_db_id; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY pentaho_proxy_bundle_report
    ADD CONSTRAINT fk_db_id FOREIGN KEY (db_id) REFERENCES pentaho_proxy_bundle_db(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

