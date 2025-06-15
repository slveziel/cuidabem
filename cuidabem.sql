CREATE DATABASE cuidabem;
USE cuidabem;
SET foreign_key_checks = 0;

-- ==============================
-- TABELAS DE SEGURANÇA E USUÁRIOS
-- ==============================

-- Tabela de papéis que define permissões para usuários (ex: administrador, médico)
CREATE TABLE cuidabem.papeis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de permissões que podem ser atribuídas aos papéis
CREATE TABLE cuidabem.permissoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela intermediária de relação entre papéis e permissões
CREATE TABLE cuidabem.papel_permissao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    papel_id INT NOT NULL,
    permissao_id INT NOT NULL,
    FOREIGN KEY (papel_id) REFERENCES papeis(id),
    FOREIGN KEY (permissao_id) REFERENCES permissoes(id)
);

-- Tabela de usuários do sistema (administradores, profissionais, etc.)
CREATE TABLE cuidabem.usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    papel_id INT,
    FOREIGN KEY (papel_id) REFERENCES papeis(id)
);

-- Logs de auditoria para ações críticas do sistema (LGPD e compliance)
CREATE TABLE cuidabem.logs_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    acao VARCHAR(255) NOT NULL,
    data_hora DATETIME NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- ==============================
-- TABELAS DE PACIENTES E ATENDIMENTO
-- ==============================

-- Cadastro de pacientes
CREATE TABLE cuidabem.pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf VARCHAR(14) UNIQUE,
    email VARCHAR(150)
);

-- Endereços vinculados aos pacientes
CREATE TABLE cuidabem.enderecos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    rua VARCHAR(150),
    cidade VARCHAR(100),
    estado VARCHAR(50),
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);

-- Registro de consultas médicas (presenciais ou online)
CREATE TABLE cuidabem.consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    profissional_id INT NOT NULL,
    data_hora DATETIME NOT NULL,
    status VARCHAR(50),
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id)
);

-- Prontuário com histórico médico do paciente
CREATE TABLE cuidabem.prontuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    anotacoes TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);

-- Prescrições médicas realizadas por profissionais
CREATE TABLE cuidabem.prescricoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    profissional_id INT NOT NULL,
    descricao TEXT,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id)
);

-- Pedidos de exames realizados durante consultas
CREATE TABLE cuidabem.pedidos_exames (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    profissional_id INT NOT NULL,
    tipo VARCHAR(100),
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id),
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id)
);

-- Resultados de exames vinculados a pedidos
CREATE TABLE cuidabem.resultados_exames (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_exame_id INT NOT NULL,
    resultado TEXT,
    FOREIGN KEY (pedido_exame_id) REFERENCES pedidos_exames(id)
);

-- ==============================
-- TABELAS DE PROFISSIONAIS DE SAÚDE
-- ==============================

-- Especialidades médicas (cardiologia, ortopedia, etc.)
CREATE TABLE cuidabem.especialidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Profissionais da saúde vinculados à especialidades
CREATE TABLE cuidabem.profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    crm VARCHAR(20),
    especialidade_id INT,
    FOREIGN KEY (especialidade_id) REFERENCES especialidades(id)
);

-- Agenda de atendimento dos profissionais
CREATE TABLE cuidabem.agendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profissional_id INT NOT NULL,
    dia_semana INT NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id)
);

-- ==============================
-- TABELAS DE TELEMEDICINA
-- ==============================

-- Sessões de telemedicina associadas a consultas
CREATE TABLE cuidabem.sessoes_telemedicina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT NOT NULL,
    link_acesso VARCHAR(255),
    inicio DATETIME,
    FOREIGN KEY (consulta_id) REFERENCES consultas(id)
);

-- ==============================
-- ADMINISTRAÇÃO HOSPITALAR
-- ==============================

-- Cadastro de unidades hospitalares (hospitais, clínicas)
CREATE TABLE cuidabem.unidades_hospitalares (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    localizacao VARCHAR(150)
);

-- Leitos hospitalares disponíveis nas unidades
CREATE TABLE cuidabem.leitos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unidade_id INT NOT NULL,
    numero VARCHAR(10),
    status VARCHAR(50),
    FOREIGN KEY (unidade_id) REFERENCES unidades_hospitalares(id)
);

-- ==============================
-- SUPRIMENTOS
-- ==============================

-- Itens disponíveis no estoque hospitalar
CREATE TABLE cuidabem.itens_estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    quantidade INT NOT NULL
);

-- Movimentações de entrada e saída de estoque
CREATE TABLE cuidabem.movimentos_estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    tipo VARCHAR(10) NOT NULL, -- entrada ou saida
    quantidade INT NOT NULL,
    data DATETIME NOT NULL,
    FOREIGN KEY (item_id) REFERENCES itens_estoque(id)
);

-- ==============================
-- FINANCEIRO
-- ==============================

-- Transações financeiras realizadas por pacientes
CREATE TABLE cuidabem.transacoes_financeiras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    descricao VARCHAR(255),
    data DATETIME NOT NULL,
    FOREIGN KEY (paciente_id) REFERENCES pacientes(id)
);

SET foreign_key_checks = 1;
