PGDMP                      }           GymBroAnalytics    17.4    17.4 v    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            �           1262    24733    GymBroAnalytics    DATABASE     w   CREATE DATABASE "GymBroAnalytics" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'es-ES';
 !   DROP DATABASE "GymBroAnalytics";
                     postgres    false            �           0    0    SCHEMA public    ACL     %   GRANT ALL ON SCHEMA public TO admin;
                        pg_database_owner    false    5            �            1259    24734    cache    TABLE     �   CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);
    DROP TABLE public.cache;
       public         heap r       admin    false            �            1259    24739    cache_locks    TABLE     �   CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);
    DROP TABLE public.cache_locks;
       public         heap r       admin    false            �            1259    24744    clientes    TABLE     �   CREATE TABLE public.clientes (
    id integer NOT NULL,
    usuario_id integer,
    entrenador_id integer,
    altura numeric(5,2),
    peso numeric(5,2),
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.clientes;
       public         heap r       postgres    false            �           0    0    TABLE clientes    ACL     -   GRANT ALL ON TABLE public.clientes TO admin;
          public               postgres    false    219            �            1259    24748    clientes_id_seq    SEQUENCE     �   CREATE SEQUENCE public.clientes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.clientes_id_seq;
       public               postgres    false    219            �           0    0    clientes_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.clientes_id_seq OWNED BY public.clientes.id;
          public               postgres    false    220            �           0    0    SEQUENCE clientes_id_seq    ACL     7   GRANT ALL ON SEQUENCE public.clientes_id_seq TO admin;
          public               postgres    false    220            �            1259    24749    ejercicios_rutina    TABLE     �  CREATE TABLE public.ejercicios_rutina (
    id integer NOT NULL,
    rutina_id integer NOT NULL,
    nombre_ejercicio character varying(100) NOT NULL,
    repeticiones integer NOT NULL,
    series integer NOT NULL,
    dia_semana character varying(20) NOT NULL,
    descanso_segundos integer DEFAULT 60,
    orden integer NOT NULL,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    rpe integer,
    CONSTRAINT ejercicios_rutina_descanso_segundos_check CHECK ((descanso_segundos >= 0)),
    CONSTRAINT ejercicios_rutina_orden_check CHECK ((orden > 0)),
    CONSTRAINT ejercicios_rutina_repeticiones_check CHECK ((repeticiones > 0)),
    CONSTRAINT ejercicios_rutina_series_check CHECK ((series > 0))
);
 %   DROP TABLE public.ejercicios_rutina;
       public         heap r       postgres    false            �           0    0    TABLE ejercicios_rutina    ACL     6   GRANT ALL ON TABLE public.ejercicios_rutina TO admin;
          public               postgres    false    221            �            1259    24759    ejercicios_rutina_id_seq    SEQUENCE     �   CREATE SEQUENCE public.ejercicios_rutina_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.ejercicios_rutina_id_seq;
       public               postgres    false    221            �           0    0    ejercicios_rutina_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.ejercicios_rutina_id_seq OWNED BY public.ejercicios_rutina.id;
          public               postgres    false    222            �           0    0 !   SEQUENCE ejercicios_rutina_id_seq    ACL     @   GRANT ALL ON SEQUENCE public.ejercicios_rutina_id_seq TO admin;
          public               postgres    false    222            �            1259    24760    entrenadores    TABLE     A  CREATE TABLE public.entrenadores (
    id integer NOT NULL,
    usuario_id integer,
    especialidad character varying(100),
    experiencia integer,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    ishabilitado boolean,
    CONSTRAINT entrenadores_experiencia_check CHECK ((experiencia >= 0))
);
     DROP TABLE public.entrenadores;
       public         heap r       postgres    false            �           0    0    TABLE entrenadores    ACL     1   GRANT ALL ON TABLE public.entrenadores TO admin;
          public               postgres    false    223            �            1259    24765    entrenadores_id_seq    SEQUENCE     �   CREATE SEQUENCE public.entrenadores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.entrenadores_id_seq;
       public               postgres    false    223            �           0    0    entrenadores_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.entrenadores_id_seq OWNED BY public.entrenadores.id;
          public               postgres    false    224            �           0    0    SEQUENCE entrenadores_id_seq    ACL     ;   GRANT ALL ON SEQUENCE public.entrenadores_id_seq TO admin;
          public               postgres    false    224            �            1259    24766    failed_jobs    TABLE     &  CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);
    DROP TABLE public.failed_jobs;
       public         heap r       admin    false            �            1259    24772    failed_jobs_id_seq    SEQUENCE     {   CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.failed_jobs_id_seq;
       public               admin    false    225            �           0    0    failed_jobs_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;
          public               admin    false    226            �            1259    24773    job_batches    TABLE     d  CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);
    DROP TABLE public.job_batches;
       public         heap r       admin    false            �            1259    24778    jobs    TABLE     �   CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);
    DROP TABLE public.jobs;
       public         heap r       admin    false            �            1259    24783    jobs_id_seq    SEQUENCE     t   CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.jobs_id_seq;
       public               admin    false    228            �           0    0    jobs_id_seq    SEQUENCE OWNED BY     ;   ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;
          public               admin    false    229            �            1259    24784 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap r       admin    false            �            1259    24787    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public               admin    false    230            �           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public               admin    false    231            �            1259    24788    password_reset_tokens    TABLE     �   CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);
 )   DROP TABLE public.password_reset_tokens;
       public         heap r       admin    false            �            1259    24920    progresos_fisicos    TABLE     W  CREATE TABLE public.progresos_fisicos (
    id integer NOT NULL,
    cliente_id integer,
    fecha date NOT NULL,
    peso numeric(5,2),
    grasa_corporal numeric(5,2),
    circunferencia_brazo numeric(5,2),
    circunferencia_cintura numeric(5,2),
    imc numeric(5,2),
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
 %   DROP TABLE public.progresos_fisicos;
       public         heap r       postgres    false            �           0    0    TABLE progresos_fisicos    ACL     6   GRANT ALL ON TABLE public.progresos_fisicos TO admin;
          public               postgres    false    241            �            1259    24919    progresos_fisicos_id_seq    SEQUENCE     �   CREATE SEQUENCE public.progresos_fisicos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.progresos_fisicos_id_seq;
       public               postgres    false    241            �           0    0    progresos_fisicos_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.progresos_fisicos_id_seq OWNED BY public.progresos_fisicos.id;
          public               postgres    false    240            �           0    0 !   SEQUENCE progresos_fisicos_id_seq    ACL     @   GRANT ALL ON SEQUENCE public.progresos_fisicos_id_seq TO admin;
          public               postgres    false    240            �            1259    24800    rutinas_entrenamiento    TABLE     x  CREATE TABLE public.rutinas_entrenamiento (
    id integer NOT NULL,
    cliente_id integer,
    entrenador_id integer,
    nombre character varying(100) NOT NULL,
    descripcion text,
    duracion_semana integer,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT rutinas_entrenamiento_duracion_semana_check CHECK ((duracion_semana > 0))
);
 )   DROP TABLE public.rutinas_entrenamiento;
       public         heap r       postgres    false            �           0    0    TABLE rutinas_entrenamiento    ACL     :   GRANT ALL ON TABLE public.rutinas_entrenamiento TO admin;
          public               postgres    false    233            �            1259    24807    rutinas_entrenamiento_id_seq    SEQUENCE     �   CREATE SEQUENCE public.rutinas_entrenamiento_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE public.rutinas_entrenamiento_id_seq;
       public               postgres    false    233            �           0    0    rutinas_entrenamiento_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE public.rutinas_entrenamiento_id_seq OWNED BY public.rutinas_entrenamiento.id;
          public               postgres    false    234            �           0    0 %   SEQUENCE rutinas_entrenamiento_id_seq    ACL     D   GRANT ALL ON SEQUENCE public.rutinas_entrenamiento_id_seq TO admin;
          public               postgres    false    234            �            1259    24808    sessions    TABLE     �   CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);
    DROP TABLE public.sessions;
       public         heap r       admin    false            �            1259    24813    users    TABLE     x  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.users;
       public         heap r       admin    false            �            1259    24818    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               admin    false    236            �           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               admin    false    237            �            1259    24819    usuarios    TABLE     �  CREATE TABLE public.usuarios (
    id integer NOT NULL,
    nombre character varying(100) NOT NULL,
    email character varying(100) NOT NULL,
    contrasenya text NOT NULL,
    rol character varying(20) NOT NULL,
    creado_en timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    "fotoURL" character varying(255),
    CONSTRAINT usuarios_rol_check CHECK (((rol)::text = ANY (ARRAY[('cliente'::character varying)::text, ('entrenador'::character varying)::text, ('admin'::character varying)::text])))
);
    DROP TABLE public.usuarios;
       public         heap r       postgres    false            �           0    0    TABLE usuarios    ACL     -   GRANT ALL ON TABLE public.usuarios TO admin;
          public               postgres    false    238            �            1259    24826    usuarios_id_seq    SEQUENCE     �   CREATE SEQUENCE public.usuarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.usuarios_id_seq;
       public               postgres    false    238            �           0    0    usuarios_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.usuarios_id_seq OWNED BY public.usuarios.id;
          public               postgres    false    239            �           0    0    SEQUENCE usuarios_id_seq    ACL     7   GRANT ALL ON SEQUENCE public.usuarios_id_seq TO admin;
          public               postgres    false    239            �           2604    24827    clientes id    DEFAULT     j   ALTER TABLE ONLY public.clientes ALTER COLUMN id SET DEFAULT nextval('public.clientes_id_seq'::regclass);
 :   ALTER TABLE public.clientes ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    219            �           2604    24828    ejercicios_rutina id    DEFAULT     |   ALTER TABLE ONLY public.ejercicios_rutina ALTER COLUMN id SET DEFAULT nextval('public.ejercicios_rutina_id_seq'::regclass);
 C   ALTER TABLE public.ejercicios_rutina ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    222    221            �           2604    24829    entrenadores id    DEFAULT     r   ALTER TABLE ONLY public.entrenadores ALTER COLUMN id SET DEFAULT nextval('public.entrenadores_id_seq'::regclass);
 >   ALTER TABLE public.entrenadores ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    224    223            �           2604    24830    failed_jobs id    DEFAULT     p   ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);
 =   ALTER TABLE public.failed_jobs ALTER COLUMN id DROP DEFAULT;
       public               admin    false    226    225            �           2604    24831    jobs id    DEFAULT     b   ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);
 6   ALTER TABLE public.jobs ALTER COLUMN id DROP DEFAULT;
       public               admin    false    229    228            �           2604    24832    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public               admin    false    231    230            �           2604    24923    progresos_fisicos id    DEFAULT     |   ALTER TABLE ONLY public.progresos_fisicos ALTER COLUMN id SET DEFAULT nextval('public.progresos_fisicos_id_seq'::regclass);
 C   ALTER TABLE public.progresos_fisicos ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    240    241    241            �           2604    24834    rutinas_entrenamiento id    DEFAULT     �   ALTER TABLE ONLY public.rutinas_entrenamiento ALTER COLUMN id SET DEFAULT nextval('public.rutinas_entrenamiento_id_seq'::regclass);
 G   ALTER TABLE public.rutinas_entrenamiento ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    234    233            �           2604    24835    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               admin    false    237    236            �           2604    24836    usuarios id    DEFAULT     j   ALTER TABLE ONLY public.usuarios ALTER COLUMN id SET DEFAULT nextval('public.usuarios_id_seq'::regclass);
 :   ALTER TABLE public.usuarios ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    239    238            u          0    24734    cache 
   TABLE DATA           7   COPY public.cache (key, value, expiration) FROM stdin;
    public               admin    false    217   ��       v          0    24739    cache_locks 
   TABLE DATA           =   COPY public.cache_locks (key, owner, expiration) FROM stdin;
    public               admin    false    218   Ќ       w          0    24744    clientes 
   TABLE DATA           Z   COPY public.clientes (id, usuario_id, entrenador_id, altura, peso, creado_en) FROM stdin;
    public               postgres    false    219   �       y          0    24749    ejercicios_rutina 
   TABLE DATA           �   COPY public.ejercicios_rutina (id, rutina_id, nombre_ejercicio, repeticiones, series, dia_semana, descanso_segundos, orden, creado_en, rpe) FROM stdin;
    public               postgres    false    221   q�       {          0    24760    entrenadores 
   TABLE DATA           j   COPY public.entrenadores (id, usuario_id, especialidad, experiencia, creado_en, ishabilitado) FROM stdin;
    public               postgres    false    223   ,�       }          0    24766    failed_jobs 
   TABLE DATA           a   COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
    public               admin    false    225   ��                 0    24773    job_batches 
   TABLE DATA           �   COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
    public               admin    false    227   ώ       �          0    24778    jobs 
   TABLE DATA           c   COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
    public               admin    false    228   �       �          0    24784 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public               admin    false    230   	�       �          0    24788    password_reset_tokens 
   TABLE DATA           I   COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
    public               admin    false    232   ^�       �          0    24920    progresos_fisicos 
   TABLE DATA           �   COPY public.progresos_fisicos (id, cliente_id, fecha, peso, grasa_corporal, circunferencia_brazo, circunferencia_cintura, imc, creado_en) FROM stdin;
    public               postgres    false    241   {�       �          0    24800    rutinas_entrenamiento 
   TABLE DATA              COPY public.rutinas_entrenamiento (id, cliente_id, entrenador_id, nombre, descripcion, duracion_semana, creado_en) FROM stdin;
    public               postgres    false    233   $�       �          0    24808    sessions 
   TABLE DATA           _   COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
    public               admin    false    235   ��       �          0    24813    users 
   TABLE DATA           u   COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
    public               admin    false    236   �       �          0    24819    usuarios 
   TABLE DATA           ]   COPY public.usuarios (id, nombre, email, contrasenya, rol, creado_en, "fotoURL") FROM stdin;
    public               postgres    false    238   �       �           0    0    clientes_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.clientes_id_seq', 16, true);
          public               postgres    false    220            �           0    0    ejercicios_rutina_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.ejercicios_rutina_id_seq', 46, true);
          public               postgres    false    222            �           0    0    entrenadores_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.entrenadores_id_seq', 2, true);
          public               postgres    false    224            �           0    0    failed_jobs_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);
          public               admin    false    226            �           0    0    jobs_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);
          public               admin    false    229            �           0    0    migrations_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.migrations_id_seq', 3, true);
          public               admin    false    231            �           0    0    progresos_fisicos_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.progresos_fisicos_id_seq', 10, true);
          public               postgres    false    240            �           0    0    rutinas_entrenamiento_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('public.rutinas_entrenamiento_id_seq', 8, true);
          public               postgres    false    234            �           0    0    users_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.users_id_seq', 1, false);
          public               admin    false    237            �           0    0    usuarios_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.usuarios_id_seq', 26, true);
          public               postgres    false    239            �           2606    24838    cache_locks cache_locks_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);
 F   ALTER TABLE ONLY public.cache_locks DROP CONSTRAINT cache_locks_pkey;
       public                 admin    false    218            �           2606    24840    cache cache_pkey 
   CONSTRAINT     O   ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);
 :   ALTER TABLE ONLY public.cache DROP CONSTRAINT cache_pkey;
       public                 admin    false    217            �           2606    24842    clientes clientes_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.clientes DROP CONSTRAINT clientes_pkey;
       public                 postgres    false    219            �           2606    24844     clientes clientes_usuario_id_key 
   CONSTRAINT     a   ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_usuario_id_key UNIQUE (usuario_id);
 J   ALTER TABLE ONLY public.clientes DROP CONSTRAINT clientes_usuario_id_key;
       public                 postgres    false    219            �           2606    24846 (   ejercicios_rutina ejercicios_rutina_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY public.ejercicios_rutina
    ADD CONSTRAINT ejercicios_rutina_pkey PRIMARY KEY (id);
 R   ALTER TABLE ONLY public.ejercicios_rutina DROP CONSTRAINT ejercicios_rutina_pkey;
       public                 postgres    false    221            �           2606    24848    entrenadores entrenadores_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.entrenadores
    ADD CONSTRAINT entrenadores_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.entrenadores DROP CONSTRAINT entrenadores_pkey;
       public                 postgres    false    223            �           2606    24850 (   entrenadores entrenadores_usuario_id_key 
   CONSTRAINT     i   ALTER TABLE ONLY public.entrenadores
    ADD CONSTRAINT entrenadores_usuario_id_key UNIQUE (usuario_id);
 R   ALTER TABLE ONLY public.entrenadores DROP CONSTRAINT entrenadores_usuario_id_key;
       public                 postgres    false    223            �           2606    24852    failed_jobs failed_jobs_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_pkey;
       public                 admin    false    225            �           2606    24854 #   failed_jobs failed_jobs_uuid_unique 
   CONSTRAINT     ^   ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);
 M   ALTER TABLE ONLY public.failed_jobs DROP CONSTRAINT failed_jobs_uuid_unique;
       public                 admin    false    225            �           2606    24856    job_batches job_batches_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.job_batches DROP CONSTRAINT job_batches_pkey;
       public                 admin    false    227            �           2606    24858    jobs jobs_pkey 
   CONSTRAINT     L   ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);
 8   ALTER TABLE ONLY public.jobs DROP CONSTRAINT jobs_pkey;
       public                 admin    false    228            �           2606    24860    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public                 admin    false    230            �           2606    24862 0   password_reset_tokens password_reset_tokens_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);
 Z   ALTER TABLE ONLY public.password_reset_tokens DROP CONSTRAINT password_reset_tokens_pkey;
       public                 admin    false    232            �           2606    24926 (   progresos_fisicos progresos_fisicos_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY public.progresos_fisicos
    ADD CONSTRAINT progresos_fisicos_pkey PRIMARY KEY (id);
 R   ALTER TABLE ONLY public.progresos_fisicos DROP CONSTRAINT progresos_fisicos_pkey;
       public                 postgres    false    241            �           2606    24866 0   rutinas_entrenamiento rutinas_entrenamiento_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY public.rutinas_entrenamiento
    ADD CONSTRAINT rutinas_entrenamiento_pkey PRIMARY KEY (id);
 Z   ALTER TABLE ONLY public.rutinas_entrenamiento DROP CONSTRAINT rutinas_entrenamiento_pkey;
       public                 postgres    false    233            �           2606    24868    sessions sessions_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.sessions DROP CONSTRAINT sessions_pkey;
       public                 admin    false    235            �           2606    24870    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public                 admin    false    236            �           2606    24872    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 admin    false    236            �           2606    24874    usuarios usuarios_email_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_email_key UNIQUE (email);
 E   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_email_key;
       public                 postgres    false    238            �           2606    24876    usuarios usuarios_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_pkey;
       public                 postgres    false    238            �           1259    24877    jobs_queue_index    INDEX     B   CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);
 $   DROP INDEX public.jobs_queue_index;
       public                 admin    false    228            �           1259    24878    sessions_last_activity_index    INDEX     Z   CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);
 0   DROP INDEX public.sessions_last_activity_index;
       public                 admin    false    235            �           1259    24879    sessions_user_id_index    INDEX     N   CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);
 *   DROP INDEX public.sessions_user_id_index;
       public                 admin    false    235            �           2606    24880 !   clientes clientes_usuario_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuarios(id) ON DELETE CASCADE;
 K   ALTER TABLE ONLY public.clientes DROP CONSTRAINT clientes_usuario_id_fkey;
       public               postgres    false    4828    238    219            �           2606    24885 2   ejercicios_rutina ejercicios_rutina_rutina_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.ejercicios_rutina
    ADD CONSTRAINT ejercicios_rutina_rutina_id_fkey FOREIGN KEY (rutina_id) REFERENCES public.rutinas_entrenamiento(id) ON DELETE CASCADE;
 \   ALTER TABLE ONLY public.ejercicios_rutina DROP CONSTRAINT ejercicios_rutina_rutina_id_fkey;
       public               postgres    false    221    233    4816            �           2606    24890 )   entrenadores entrenadores_usuario_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.entrenadores
    ADD CONSTRAINT entrenadores_usuario_id_fkey FOREIGN KEY (usuario_id) REFERENCES public.usuarios(id) ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.entrenadores DROP CONSTRAINT entrenadores_usuario_id_fkey;
       public               postgres    false    223    238    4828            �           2606    24914 (   rutinas_entrenamiento fk_cliente_usuario    FK CONSTRAINT     �   ALTER TABLE ONLY public.rutinas_entrenamiento
    ADD CONSTRAINT fk_cliente_usuario FOREIGN KEY (cliente_id) REFERENCES public.clientes(usuario_id) ON DELETE CASCADE;
 R   ALTER TABLE ONLY public.rutinas_entrenamiento DROP CONSTRAINT fk_cliente_usuario;
       public               postgres    false    233    219    4795            �           2606    24905 >   rutinas_entrenamiento rutinas_entrenamiento_entrenador_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.rutinas_entrenamiento
    ADD CONSTRAINT rutinas_entrenamiento_entrenador_id_fkey FOREIGN KEY (entrenador_id) REFERENCES public.entrenadores(id) ON DELETE SET NULL;
 h   ALTER TABLE ONLY public.rutinas_entrenamiento DROP CONSTRAINT rutinas_entrenamiento_entrenador_id_fkey;
       public               postgres    false    223    4799    233            =           826    24910     DEFAULT PRIVILEGES FOR SEQUENCES    DEFAULT ACL     ]   ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON SEQUENCES TO admin;
          public               postgres    false            >           826    24911     DEFAULT PRIVILEGES FOR FUNCTIONS    DEFAULT ACL     ]   ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON FUNCTIONS TO admin;
          public               postgres    false            ?           826    24912    DEFAULT PRIVILEGES FOR TABLES    DEFAULT ACL     Z   ALTER DEFAULT PRIVILEGES FOR ROLE postgres IN SCHEMA public GRANT ALL ON TABLES TO admin;
          public               postgres    false            u      x������ � �      v      x������ � �      w   t   x�U��1D�3�b0b�ײ��۫"����!F���4�1�yw\�DNR���!C�>���>s��۳l��]�c����n[MuG��)��qY����GN���l��־_>!,      y   �   x����
�@�뽧��؟����H�J�i�$� L��{�P�m�e��� �0�Ew�	(#k�Z2���.I
ыD��w��t����o	��i�:I�����̰�h��f[t���)W)�	H$��@Ӂ�5�$�;x��TCt���X租��ZXg��w�=��?�      {   v   x�%�A
�  ���
?���j��^JO!�E�)�	b(���<���,�����-R  9
ƱaԄ}�d��4D�GEaھ���t���~�U��.��`�4��e���#']�V)��F      }      x������ � �            x������ � �      �      x������ � �      �   E   x�3�4000��"0�O.JM,I�/-N-*�/IL�I�4�2�PhS�����
Wh����0+?	a`� ��$      �      x������ � �      �   �   x����	�0��h�.���CCt��?G���(X�+�7�Q�]��m�YƇ�c;��Z��J��U����Z���k�f������|��+Nƭ��諊6P[�N_.�l7��ޅ{
�z��G$Ɣ��
h�W@�����]�U:��^��H      �   �   x�U�;�0�Z:�/�%�#�� 	.�0��c
�Sm��Zv0^`z����/ͭ�z�ۼ���iWv���+�m�AS�LG�!xn{��="ӄ�"ٲF���/�󌤉��K�
���B:�����(1+�?��8H      �   "  x�%�Ms�0 D��c,&ȡZ��X�@/�h	Ė(¯ojgoog��~;�ł�<���B��x�����U�}[n�n~��o�>5lǛX:����j���*ޜ����kJ>�[5��D���Q�����B��?d�6��S�Y$i3��Ƚ�ť�@�޲5�6-6�,Z����̗g/kč�c'����׍��i�s�"�� &Me�8��avG5��Ŵ_��aУ�oS7���P�>���Sv�HeR�
�	<=\����?4��7��@0��{��?ֳa{S�@8Ss?1M�ERqn      �      x������ � �      �   �  x���˒�����S�`O7$�UG% ��hq���@@��E.�~����G9OҔZ{w���ԉ rɀ���+��-�|^���?��G���ˡKvE�zY������nY6Y�#AZA��Y�@ ���N���Ę��ð��M{��$u�����y�����^y���1Q(�$�22Ȳ���{/���k�o����o g�%4�,i��|���A���tr"I��-�Y���R��n˝��S�q���c��F��VqP��{|W6#0�`�$=���mϤn*Q8�-�ʪw\��=,�8|�|&����;��Gp��U��W�e�7b+ %8T6�nMk)Z����AY0u��r�ӝ�'`�C�
&������mE~�uhc3UZ�&չ�	q���ì�K~/i��[�y8y�ӟ��}W0y����3�KO�f�
�/VPG]�D�c�rFڂ"'��sY�.h���'`
�!s���#�}�b�8�Z([���4��hw"�q�p�~���a�k��W�g`8�7s�w�{�#X,0��E5Q��{N��`�9p}Q�q��re�z)�`�vF��<0&�+xt2��L{ݩ�-/R���l�9XИ�-�i�y*��5഻\jF���a�)xP�cU�c�3ύ�YY�Y  VVY��VH���P�,ɆB<o!3�	`+��v�;�Z���}��yzx�8�p�J߇97���J�[�����nΧY��Aʋ���x�zh�$y1\�:�6ZG�o>�����=<_T�3$�B�ũ��s�V/@�l�H���\��K`�z��n� =���lS�<�Y�n�q�X4%��9�:��������K?ʅ�r!������+�X2��*).g��"L�:=���뷬މ�*`�i�eF�Ҳ�n�p�8 |k�P��ɯ�څ{^�BR?՛vJ�E�WY�j]n̽�N7MS���Mq-�.��c[S�^�MT��\�5�� ǕhW�eq�����I���8On�`��<��yk�/�6�­]���� C٭��m�e�"i̅S/7�
?u�t�yU��*��/Ly�Ù��)�G��Q	t9Z��QK�}�J���zV��Ҙ�:G#~���.~�zsl6ի��Gt�fle��`�������z��מ�V0ڗ��59���{C�I8��/�a��%6p</���ī��7J����<����>ԗ��
7L��mO
/+�8˃�%h�$����Z���X>��2��5�s�HFk�RQ���w��eO����އ��l���~~G�g��B>$�Q(��C$qZD}L
�QE_&	���Tun�D�������/6JL�9k�a�-:+���,�	��b
m�5��|R�H렍��g���0������I�� ����Ɋ�d$/Α�!�Ai������*�7����oq�L     